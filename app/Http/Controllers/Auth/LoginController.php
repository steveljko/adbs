<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\Http\Requests\LoginRequest;
use App\ValueObjects\LoginCredentials;

final class LoginController
{
    public const REDIRECT_TO = 'dashboard';

    public function __invoke(
        LoginRequest $request,
        LoginAction $action
    ) {
        $creds = LoginCredentials::fromRequest($request);

        $ok = $action->execute($creds);

        if ($ok) {
            return htmx()->redirect(route(self::REDIRECT_TO))->response(null);
        }
    }
}
