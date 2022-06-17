<?php

namespace App\Models;

use App\Components\Filters\FilterBuilder;
use App\Services\SmsProviders\KavehNegar;
use App\Services\SmsProviders\Qasedak;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;

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

    /**
     * This method works with the type property,
     * and do the mapping process to find the class of the target provider
     * @return Qasedak|KavehNegar|null
     * @throws \Exception
     */
    function getSmsProviderAttribute(): Qasedak|KavehNegar|null
    {
        $handler = App::make('GuzzleClientHandler');
        if ($this->name == 'qasedak') {
            return new Qasedak($this->number, $this->info, $handler);
        } elseif ($this->name == 'kaveh-negar') {
            return new KavehNegar($this->number, $this->info, $handler);
        }
        return null;
    }
}
