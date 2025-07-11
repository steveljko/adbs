<?php

declare(strict_types=1);

namespace App\Http\Actions\AddonClients;

use App\Enums\AddonClientStatus;
use App\Models\AddonClients;

final class DeactivateTokenAction
{
    public function execute(AddonClients $addonClient): bool
    {
        if ($addonClient->status === AddonClientStatus::ACTIVE) {

            $addonClient->update(['status' => AddonClientStatus::INACTIVE]);

            return true;
        }

        return false;
    }
}
