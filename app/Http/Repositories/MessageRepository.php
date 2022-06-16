<?php

namespace App\Http\Repositories;

use App\Models\Message;

class MessageRepository
{
    public function __construct(private Message $message)
    {
    }

    public function store(array $data): Message
    {
        return $this->message->create($data);
    }
}
