<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class LoginAction
{
    public function execute(array $credentials): bool
    {
        if (! Auth::attempt(credentials: $credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Either username or password are incorrect.'],
            ]);
        }

        return true;
    }
}
