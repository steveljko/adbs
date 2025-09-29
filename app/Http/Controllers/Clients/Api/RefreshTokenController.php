<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients\Api;

use App\Actions\Auth\GenerateTokenPairsAction;
use App\Http\Requests\RefreshTokenRequest;
use App\Models\TokenBrowserInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class RefreshTokenController
{
    /*
     *
     */
    public function __invoke(
        RefreshTokenRequest $request,
        GenerateTokenPairsAction $generateTokenPairs,
    ) {
        $user = $request->refreshToken->tokenable;
        $browserIdentifier = $request->browser_identifier;

        $newAccessToken = null;
        $newRefreshToken = null;

        $tbi = TokenBrowserInfo::query()
            ->where('browser_identifier', $browserIdentifier)
            ->where('refresh_token_id', $request->refreshToken->id)
            ->first();

        if (! $tbi->exists()) {
            return new JsonResponse([
                'message' => 'Invalid identifier or refresh token',
                'error' => 'INVALID_REFRESH_TOKEN',
            ], Response::HTTP_UNAUTHORIZED);
        }

        DB::transaction(function () use (
            $user,
            $generateTokenPairs,
            $tbi,
            &$newAccessToken,
            &$newRefreshToken
        ) {
            [$newAccessToken, $newRefreshToken] = $generateTokenPairs->execute($user);

            $tbi->update([
                'access_token_id' => $newAccessToken->accessToken->id,
                'refresh_token_id' => $newRefreshToken->accessToken->id,
            ]);

            $user->tokens()
                ->where('name', 'access_token')
                ->where('id', '!=', $newAccessToken->accessToken->id)
                ->where('tokenable_id', $user->id)
                ->delete();

            $user->tokens()
                ->where('name', 'refresh_token')
                ->where('id', '!=', $newRefreshToken->accessToken->id)
                ->where('tokenable_id', $user->id)
                ->delete();
        });

        return new JsonResponse([
            'access_token' => $newAccessToken?->plainTextToken,
            'refresh_token' => $newRefreshToken?->plainTextToken,
            'user' => $user->only(['name', 'email']),
            'expires_in' => 900,
        ]);
    }
}
