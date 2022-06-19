<?php

namespace App\Http\Repositories;

use App\Models\Message;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class MessageRepository
{
    public function __construct(private Message $message)
    {
    }

    public function paginate(): LengthAwarePaginator
    {
        $fillables = $this->message->getFillable();
        $filters = [];

        foreach ($fillables as $fillable) {
            $filters[$fillable] = request()->input($fillable);
        }

        return $this->message->withAll()->filterBy($filters)->latest()->paginate();
    }

    public function paginate_cache(): LengthAwarePaginator
    {
        $request = json_encode(request()->all());
        $request = hash('sha256',$request);
        return Cache::tags($this->message->cache_tags)->rememberForever('index_paginate' . $request, function () {
            return $this->paginate();
        });
    }

    public function store(array $data): Message
    {
        return $this->message->create($data);
    }
}
