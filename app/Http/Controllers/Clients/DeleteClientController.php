<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Models\AddonClients;

final class DeleteClientController
{
    public function __invoke(AddonClients $addonClient)
    {
        return view('partials.clients.delete', compact('addonClient'));
    }
}
