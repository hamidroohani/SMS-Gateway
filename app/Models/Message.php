<?php

namespace App\Models;

use App\Components\Filters\FilterBuilder;
use App\Enums\MessageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'provider_id',
        'from',
        'receiver_mobile',
        'body',
        'status',
        'sent_at',
        'ref_code',
        'err_msg',
    ];

    public array $cache_tags = ['messages'];

    protected $casts = [
        'status' => MessageStatus::class,
    ];

    protected $dates = ['sent_at'];

    public function Provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function scopeWithAll($query)
    {
        $query->with(['Provider']);
    }

    public function scopeFilterBy($query, $filters): Builder
    {
        $namespace = 'App\Http\Filters\Message';
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
