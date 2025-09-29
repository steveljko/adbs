<?php

declare(strict_types=1);

namespace App\Actions\AddonClients;

use App\Enums\TokenStatus;
use App\Models\PersonalAccessToken;

final class DeactivateClientAction
{
    public function execute(PersonalAccessToken $client): bool
    {
        $info = $client->info;

        if ($info->isActive()) {
            $info->status = TokenStatus::INACTIVE;

            return $info->save();
        }

        return false;
    }
}
