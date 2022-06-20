<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Api\ResponseController;
use App\Http\Repositories\MessageRepository;
use App\Models\Message;
use Illuminate\Contracts\View\View;

class MessagesController extends ResponseController
{
    private MessageRepository $messageRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository(new Message());
    }

    public function index(): view
    {
        if (request()->input('key')) request()->request->add([request()->input('key') => request()->input('value')]);
        $messages = $this->messageRepository->paginate_cache();
        $stats = $this->messageRepository->stats_cache();
        return view('pages.messages', compact('messages','stats'));
    }
}
