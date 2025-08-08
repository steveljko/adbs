<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Http\Actions\AddonClients\DeleteClientAction;
use App\Models\AddonClients;
use Illuminate\Http\Response;

final class DestroyClientController
{
    public function __invoke(AddonClients $addonClient, DeleteClientAction $deleteClient): Response
    {
        $deleteClient->execute($addonClient);

        return htmx()
            ->trigger('hideModal')
            ->toast(type: 'success', text: 'Token successfully deleted')
            ->target('#clients')
            ->swap('outerHTML')
            ->response(view('partials.settings.clients')->fragment('clients'));
    }
}
