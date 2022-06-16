<?php

namespace App\Models;

use App\Enums\MessageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    protected $casts = [
        'status' => MessageStatus::class,
    ];

    protected $dates = ['sent_at'];

    public function Provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
