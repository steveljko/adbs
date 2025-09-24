<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TokenStatus;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;

final class TokenBrowserInfo extends Model
{
    protected $fillable = [
        'personal_access_token_id',
        'browser_identifier',
        'browser',
        'browser_version',
        'addon_version',
        'user_agent',
        'ip_address',
        'status',
        'notes',
    ];

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

    public function token()
    {
        return $this->belongsTo(PersonalAccessToken::class, 'personal_access_token_id');
    }

    protected function casts(): array
    {
        return [
            'status' => TokenStatus::class,
        ];
    }
}
