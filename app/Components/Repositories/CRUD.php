<?php

namespace App\Components\Repositories;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Mongodb\Eloquent\Model;

abstract class CRUD implements \App\Contracts\Repositores\CRUD
{
    public function __construct(private Model $model)
    {

    }

    public function paginate(): LengthAwarePaginator
    {
        $fillables = $this->model->getFillable();
        $filters = [];

        foreach ($fillables as $fillable) {
            $filters[$fillable] = request()->input($fillable);
        }

        return $this->model->withAll()->filterBy($filters)->latest()->paginate();
    }

    public function paginate_cache(): LengthAwarePaginator
    {
        $request = json_encode(request()->all());
        $request = hash('sha256',$request);
        return Cache::tags($this->model->cache_tags)->rememberForever('index_paginate' . $request, function () {
            return $this->paginate();
        });
    }

    public function show(string $id): Model
    {
        return $this->model->withAll()->findOrFail($id);
    }

    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): Model
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete(string $id): bool
    {
        $model = $this->model->findOrFail($id);
        $model->delete();
        return true;
    }

    public function make_array_for_insert(Request $request): array
    {
        $fillables = $this->model->getFillable();
        $data = [];

        foreach ($fillables as $fillable) {
            if ($request->input($fillable))
                $data[$fillable] = $request->input($fillable);
        }

        return $data;
    }
}
