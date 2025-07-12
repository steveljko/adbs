<?php

declare(strict_types=1);

namespace App\Http\Actions\AddonClients;

use App\Models\AddonClients;

final class DeleteClientAction
{
    public function execute(AddonClients $client)
    {
        $client->delete();
    }
}
