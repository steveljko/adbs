<?php

declare(strict_types=1);

namespace App\Http\Actions\AddonClients;

use App\Enums\TokenStatus;
use App\Models\PersonalAccessToken;

final class DeactivateClientAction
{
    public function execute(PersonalAccessToken $client): bool
    {
        if ($client->isActive()) {
            return $client->update(['status' => TokenStatus::INACTIVE]);
        }

        return false;
    }
}
