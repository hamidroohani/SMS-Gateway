<?php

namespace App\Http\Repositories;

use App\Components\Repositories\CRUD;
use App\Models\Provider;

class ProviderRepository extends CRUD
{

    public function __construct(private Provider $provider)
    {
        parent::__construct($this->provider);
    }
}
