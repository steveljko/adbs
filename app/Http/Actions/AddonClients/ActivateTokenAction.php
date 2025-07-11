<?php

declare(strict_types=1);

namespace App\Http\Actions\AddonClients;

use App\Enums\AddonClientStatus;
use App\Models\AddonClients;

final class ActivateTokenAction
{
    public function execute(AddonClients $addonClient): bool
    {
        if ($addonClient->status === AddonClientStatus::PENDING ||
            $addonClient->status === AddonClientStatus::INACTIVE) {

            $addonClient->update(['status' => AddonClientStatus::ACTIVE]);

            return true;
        }

        return false;
    }
}
