<?php

declare(strict_types=1);

namespace App\Actions\AddonClients;

use App\Models\PersonalAccessToken;

final class DeleteClientAction
{
    public function execute(PersonalAccessToken $client)
    {
        $client->delete();
    }
}
