<?php

declare(strict_types=1);

namespace App\Actions\AddonClients;

use App\Enums\TokenStatus;
use App\Models\PersonalAccessToken;

final class ActivateClientAction
{
    /*
     * @param PersonalAccessToken $client
     */
    public function execute(PersonalAccessToken $client): bool
    {
        $info = $client->info;

        if ($info->isPending() || $info->isInactive()) {
            $info->status = TokenStatus::ACTIVE;

            return $info->save();
        }

        return false;
    }
}
