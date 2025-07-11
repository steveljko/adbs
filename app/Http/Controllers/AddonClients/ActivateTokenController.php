<?php

declare(strict_types=1);

namespace App\Http\Controllers\AddonClients;

use App\Http\Actions\AddonClients\ActivateTokenAction;
use App\Models\AddonClients;
use Illuminate\Support\Facades\Auth;

final class ActivateTokenController
{
    public function __invoke(AddonClients $addonClient, ActivateTokenAction $activateToken)
    {
        if (! $addonClient->user()->is(Auth::user())) {
            return htmx()->toast(type: 'warning', text: 'Not authorized to activate this token.')->response();
        }

        if (! $activateToken->execute($addonClient)) {
            return htmx()->toast(type: 'warning', text: 'Token cannot be activated from current status')->response();
        }

        return htmx()->toast(type: 'success', text: 'Token is active now!')->response();
    }
}
