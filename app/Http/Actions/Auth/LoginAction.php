<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

use Illuminate\Support\Facades\Auth;

final class LoginAction
{
    public function execute(array $credentials): bool
    {
        if (! Auth::attempt(credentials: $credentials)) {
            return false;
        }

        return true;
    }
}
