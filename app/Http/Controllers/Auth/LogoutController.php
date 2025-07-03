<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Actions\Auth\LogoutAction;

final class LogoutController
{
    public function __invoke(LogoutAction $action): mixed
    {
        $action->execute();

        return htmx()
            ->redirect(route('auth.login'))
            ->response();
    }
}
