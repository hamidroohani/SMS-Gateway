<?php

namespace App\Http\Filters\Message;

use App\Http\Filters\QueryFilter;

class Status extends QueryFilter
{
    public function handle($value): void
    {
        $this->query->where('status', '=', $value);
    }
}
