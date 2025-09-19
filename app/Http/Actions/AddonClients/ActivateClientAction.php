<?php

declare(strict_types=1);

namespace App\Http\Actions\AddonClients;

use App\Enums\TokenStatus;
use App\Models\PersonalAccessToken;

final class ActivateClientAction
{
    public function execute(PersonalAccessToken $client): bool
    {
        if ($client->isPending() || $client->isInactive()) {
            return $client->update(['status' => TokenStatus::ACTIVE]);
        }

        return false;
    }
}
