<?php

namespace App\Http\Controllers;

use App\Enums\MessageStatus;
use App\Http\Repositories\MessageRepository;
use App\Http\Requests\Message\MessageStoreRequest;
use App\Models\Message;
use App\Models\Provider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class MessagesController extends ResponseController
{
    private MessageRepository $messageRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository(new Message());
    }

    public function index(): view
    {
        $messages = $this->messageRepository->paginate_cache();
        return view('pages.messages', compact('messages'));
    }

    public function store(MessageStoreRequest $request): JsonResponse
    {
        $provider = Provider::query()->first(); // TODO it must be change with a logic to find a provider
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
        return $this->success(['track_id' => $result->_id]);
    }

    public function show(Message $message): JsonResponse
    {
        return $this->success(['status' => $message->status]);
    }
}
