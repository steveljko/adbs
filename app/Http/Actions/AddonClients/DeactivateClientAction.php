<?php

declare(strict_types=1);

namespace App\Http\Actions\AddonClients;

use App\Enums\AddonClientStatus;
use App\Models\AddonClients;

final class DeactivateClientAction
{
    public function execute(AddonClients $client): bool
    {
        if ($client->isActive()) {
            $client->update(['status' => AddonClientStatus::INACTIVE]);

            return true;
        }

        return false;
    }
}
