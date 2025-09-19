<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Http\Actions\AddonClients\ActivateClientAction;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

final class ActivateClientController
{
    public function __invoke(
        ActivateClientAction $activateClient,
        PersonalAccessToken $personalAccessToken
    ) {
        if (! $personalAccessToken->tokenable->is(Auth::user())) {
            return htmx()->toast(type: 'warning', text: 'Not authorized to activate this token.')->response();
        }

        if (! $activateClient->execute($personalAccessToken)) {
            return htmx()->toast(type: 'warning', text: 'Token cannot be activated from current status')->response();
        }

        return htmx()
            ->toast(type: 'success', text: 'Token is active now!')
            ->target('#clients')
            ->swap('outerHTML')
            ->response(view('partials.settings.clients')->fragment('tokens'));
    }
}
