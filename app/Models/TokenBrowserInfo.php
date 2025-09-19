<?php

namespace App\Models;

use App\Enums\TokenStatus;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;

class TokenBrowserInfo extends Model
{
    protected $fillable = [
        'personal_access_token_id',
        'browser',
        'browser_version',
        'addon_version',
        'user_agent',
        'ip_address',
        'status',
        'notes',
    ];

    public function token()
    {
        return $this->belongsTo(PersonalAccessToken::class, 'personal_access_token_id');
    }
}
