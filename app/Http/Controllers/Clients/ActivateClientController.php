<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Http\Actions\AddonClients\ActivateClientAction;
use App\Models\AddonClients;
use Illuminate\Support\Facades\Auth;

final class ActivateClientController
{
    public function __invoke(AddonClients $addonClient, ActivateClientAction $activateClient)
    {
        if (! $addonClient->user()->is(Auth::user())) {
            return htmx()->toast(type: 'warning', text: 'Not authorized to activate this token.')->response();
        }

        if (! $activateClient->execute($addonClient)) {
            return htmx()->toast(type: 'warning', text: 'Token cannot be activated from current status')->response();
        }

        return htmx()
            ->toast(type: 'success', text: 'Token is active now!', afterSwap: true)
            ->target('#clients')
            ->swap('outerHTML')
            ->response(view('partials.settings.clients')->fragment('clients'));
    }
}
