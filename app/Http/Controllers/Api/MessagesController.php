<?php

namespace App\Http\Controllers\Api;

use App\Enums\MessageStatus;
use App\Http\Repositories\MessageRepository;
use App\Http\Requests\Message\MessageStoreRequest;
use App\Models\Message;
use App\Models\Provider;
use App\Services\AMQP\AMQP;
use Illuminate\Http\JsonResponse;

class MessagesController extends ResponseController
{
    private MessageRepository $messageRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository(new Message());
    }

    public function store(MessageStoreRequest $request, AMQP $AMQP): JsonResponse
    {
        $provider = Provider::query()->where('name', config('sms.default'))->first();
        if (!$provider) {
            $provider = Provider::query()->first();
        }
        if (!$provider) {
            return $this->failed('Unable to find a SMS Provider');
        }
        $data = [
            'provider_id' => $provider->_id,
            'from' => $provider->number,
            'receiver_mobile' => $request->input('receiver_mobile'),
            'body' => $request->input('body'),
            'status' => MessageStatus::PENDING,
        ];
        $result = $this->messageRepository->store($data);
        $AMQP->send('send-sms', $result);
        return $this->success(['track_id' => $result->_id]);
    }

    public function show(Message $message): JsonResponse
    {
        return $this->success(['status' => $message->status]);
    }
}
