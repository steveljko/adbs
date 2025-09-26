<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

use App\Models\User;

final class GenerateTokenPairsAction
{
    public function execute(User $user): array
    {
        $accessToken = $user->createToken('access_token', ['*'], now()->addMinutes(1));
        $refreshToken = $user->createToken('refresh_token', ['*'], now()->addDays(30));

        return [$accessToken, $refreshToken];
    }
}
