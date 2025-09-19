<?php

namespace App\Models;

use App\Enums\TokenStatus;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $fillable = ['status'];

    public function info()
    {
        return $this->hasOne(TokenBrowserInfo::class);
    }

    // Status helper methods
    public function isActive(): bool
    {
        return $this->status === TokenStatus::ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === TokenStatus::PENDING;
    }

    public function isInactive(): bool
    {
        return $this->status === TokenStatus::INACTIVE;
    }

    protected function casts(): array
    {
        return [
            'status' => TokenStatus::class,
        ];
    }
}
