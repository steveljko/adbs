<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AddonClients extends Model
{
    protected $fillable = [
        'token',
        'browser',
        'browser_version',
        'addon_version',
        'user_agent',
        'ip_address',
        'status',
        'last_activity_at',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnaccepted($query)
    {
        return $query->where('status', 'unaccepted');
    }
}
