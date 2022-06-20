<?php

namespace App\Http\Filters\Message;

use App\Http\Filters\QueryFilter;

class From extends QueryFilter
{
    public function handle($value): void
    {
        $this->query->where('from', '=', $value);
    }
}
