<?php

declare(strict_types=1);

namespace App\Http\Controllers\AddonClients;

use App\Http\Actions\AddonClients\DeactivateTokenAction;
use App\Models\AddonClients;
use Illuminate\Support\Facades\Auth;

final class DeactivateTokenController
{
    public function __invoke(AddonClients $addonClient, DeactivateTokenAction $deactivateToken)
    {
        if (! $addonClient->user()->is(Auth::user())) {
            return htmx()->toast(type: 'warning', text: 'Not authorized to deactivate this token.')->response();
        }

        if (! $deactivateToken->execute($addonClient)) {
            return htmx()->toast(type: 'warning', text: 'Token cannot be deactivated from current status.')->response();
        }

        return htmx()->toast(type: 'success', text: 'Token has been deactivated!')->response();
    }
}
