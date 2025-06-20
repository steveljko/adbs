<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

use Illuminate\Support\Facades\Auth;

final class LogoutAction
{
    public function execute()
    {
        return Auth::logout();
    }
}
