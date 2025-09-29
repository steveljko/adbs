<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Actions\AddonClients\DeactivateClientAction;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

final class DeactivateClientController
{
    public function __invoke(
        PersonalAccessToken $personalAccessToken,
        DeactivateClientAction $deactivateClient
    ) {
        if (! $personalAccessToken->tokenable->is(Auth::user())) {
            return htmx()->toast(type: 'warning', text: 'Not authorized to deactivate this token.')->response();
        }

        if (! $deactivateClient->execute($personalAccessToken)) {
            return htmx()->toast(type: 'warning', text: 'Token cannot be deactivated from current status.')->response();
        }

        return htmx()
            ->toast(type: 'success', text: 'Token has been deactivated!')
            ->target('#clients')
            ->swap('outerHTML')
            ->response(view('partials.settings.clients')->fragment('clients'));
    }
}
