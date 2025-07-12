<?php

declare(strict_types=1);

namespace App\Http\Actions\AddonClients;

use App\Enums\AddonClientStatus;
use App\Models\AddonClients;

final class ActivateClientAction
{
    public function execute(AddonClients $client): bool
    {
        if ($client->isPending() || $client->isInactive()) {
            $client->update(['status' => AddonClientStatus::ACTIVE]);

            return true;
        }

        return false;
    }
}
