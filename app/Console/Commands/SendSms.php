<?php

namespace App\Console\Commands;

use App\Enums\MessageStatus;
use App\Models\Message;
use Carbon\Carbon;
use GuzzleHttp\Handler\CurlMultiHandler;
use Illuminate\Console\Command;

class SendSms extends Command
{
    private object $messages;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get new messages and send them in a loop';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $handler = new CurlMultiHandler();
        app()->singleton("GuzzleClientHandler", function () use ($handler) {
            return $handler;
        });
        while (true) {

            // find new messages and save as a new record
            $this->get_new_messages();

            // send sms
            $this->send();

            // track the messages that sent
            $this->track();


            // sleep if there aren't any new messages
            if (!$this->messages->count()) {
                usleep((intval(config('sms.sleep_duration', 100))) * 1000);
            }
            $handler->execute();
        }
    }

    /**
     * Find and returns new messages
     * and also failed messages that must send again
     *
     * @return void
     */
    public function get_new_messages(): void
    {
        $this->messages = Message::query()
            ->where('status', MessageStatus::PENDING->value)
            ->limit(config('sms.limit_per_loop', 30))->get();
    }

    /**
     * Read the messages property and in a loop send them
     *
     * @return void
     */
    public function send(): void
    {
        foreach ($this->messages as $message) {

            $message->update(['status' => MessageStatus::SENDING]);
            $sms_provider = $message->Provider->sms_provider;
            $sms_provider->send($message->body, $message->receiver_mobile)
                ->then(function ($ref_code) use ($message) {
                    $message->update([
                        'status' => MessageStatus::SENT,
                        'ref_code' => $ref_code,
                        'sent_on' => Carbon::now(),
                    ]);
                })
                ->otherwise(function ($reason) use ($message) {
                    $message->update([
                        'status' => MessageStatus::FAILED_ON_SEND,
                        'err_msg' => $reason->getMessage(),
                    ]);
                });
        }
    }

    /**
     * Execute the track process.
     *
     */
    public function track()
    {
        // choose messages
        $sends = Message::query()
            ->where('status', MessageStatus::SENT->value)
            ->whereNotNull('ref_code')
            ->limit(config('sms.limit_delivery', 30))->get();

        // request to providers
        foreach ($sends as $send) {
            $send->update(['status' => MessageStatus::GETTING_DELIVERY]);

            $sms_provider = $send->Provider->sms_provider;
            $sms_provider->track($send->ref_code)
                ->then(function ($delivery_status) use ($send) {
                    $send->update(['status' => $delivery_status]);
                })
                ->otherwise(function ($reason) use ($send) {
                    $send->update(['err_msg' => $reason->getMessage()]);
                });
        }
    }
}
