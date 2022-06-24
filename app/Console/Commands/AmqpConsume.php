<?php

namespace App\Console\Commands;

use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\AMQP\AMQP;
use Carbon\Carbon;
use GuzzleHttp\Handler\CurlMultiHandler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AmqpConsume extends Command
{
    public $handler;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amqp:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(AMQP $AMQP)
    {
        $this->handler = new CurlMultiHandler();
        app()->singleton("GuzzleClientHandler", function () {
            return $this->handler;
        });

        $channel = $AMQP->connection->channel();

        $channel->queue_declare('send-sms', false, false, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $channel->basic_consume('send-sms', '', false, true, false, false, array($this,
            'process_msg'));

        while ($channel->is_open()) {
            $channel->wait();
        }
    }

    public function process_msg($msg)
    {
        try {
            $message = json_decode($msg->body, true);
            $id = $message['_id'];
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
        }
        $message = Message::query()->find($id);
        if ($message->status == MessageStatus::PENDING) {
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
            $this->handler->execute();
        }
    }
}
