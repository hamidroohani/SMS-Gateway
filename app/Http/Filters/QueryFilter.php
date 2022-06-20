<?php


namespace App\Http\Filters;

abstract class QueryFilter
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }
}
