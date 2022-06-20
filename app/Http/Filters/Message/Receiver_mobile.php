<?php

namespace App\Http\Filters\Message;

use App\Http\Filters\QueryFilter;

class Receiver_mobile extends QueryFilter
{
    public function handle($value): void
    {
        $this->query->where('receiver_mobile', '=', $value);
    }
}
