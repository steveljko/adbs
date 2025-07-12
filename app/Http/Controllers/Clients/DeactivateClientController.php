<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Http\Actions\AddonClients\DeactivateClientAction;
use App\Models\AddonClients;
use Illuminate\Support\Facades\Auth;

final class DeactivateClientController
{
    public function __invoke(AddonClients $addonClient, DeactivateClientAction $deactivateClient)
    {
        if (! $addonClient->user()->is(Auth::user())) {
            return htmx()->toast(type: 'warning', text: 'Not authorized to deactivate this token.')->response();
        }

        if (! $deactivateClient->execute($addonClient)) {
            return htmx()->toast(type: 'warning', text: 'Token cannot be deactivated from current status.')->response();
        }

        return htmx()
            ->toast(type: 'success', text: 'Token has been deactivated!', afterSwap: true)
            ->target('#clients')
            ->swap('outerHTML')
            ->response(view('partials.settings.clients')->fragment('clients'));
    }
}
