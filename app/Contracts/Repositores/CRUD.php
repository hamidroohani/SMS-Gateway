<?php

namespace App\Contracts\Repositores;

use Illuminate\Pagination\LengthAwarePaginator;
use Jenssegers\Mongodb\Eloquent\Model;

interface CRUD
{
    public function paginate(): LengthAwarePaginator;

    public function paginate_cache(): LengthAwarePaginator;

    public function store(array $data): Model;

    public function show(string $id): Model;

    public function update(string $id, array $data): Model;

    public function delete(string $id): bool;
}
