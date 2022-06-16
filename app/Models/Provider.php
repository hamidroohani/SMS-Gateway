<?php

namespace App\Models;

use App\Components\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Mongodb\Eloquent\Builder;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'info',
    ];

    public array $cache_tags = ['providers'];

    public function scopeWithAll($query)
    {
        $query->with([]);
    }

    public function scopeFilterBy($query, $filters): Builder
    {
        $namespace = 'App\Http\Filters\Provider';
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }

    protected static function booted()
    {
        $events = ['creating', 'updating', 'saving', 'deleting'];
        foreach ($events as $event) {
            static::{$event}(function ($model) {
                Cache::tags($model->cache_tags)->flush();
            });
        }
    }
}
