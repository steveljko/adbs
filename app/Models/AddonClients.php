<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AddonClientStatus;
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Query scopes
    public function scopeUnaccepted($query)
    {
        return $query->where('status', 'unaccepted');
    }

    // Status helper methods
    public function isActive(): bool
    {
        return $this->status === AddonClientStatus::ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === AddonClientStatus::PENDING;
    }

    public function isInactive(): bool
    {
        return $this->status === AddonClientStatus::INACTIVE;
    }

    // Attribute casting
    protected function casts(): array
    {
        return [
            'status' => AddonClientStatus::class,
            'last_activity_at' => 'datetime',
        ];
    }
}
