<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients\Api;

use App\Http\Actions\Auth\GenerateTokenPairsAction;
use App\Models\PersonalAccessToken;
use App\Models\TokenBrowserInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class RefreshAccessTokenController
{
    public function __invoke(
        Request $request,
        GenerateTokenPairsAction $generateTokenPairs,
    ) {
        $request->validate([
            'browser_identifier' => ['required', 'string'],
            'refresh_token' => ['required', 'string'],
        ]);

        $user = $refreshToken->tokenable;
        $browserIdentifier = $request->browser_identifier;

        $newAccessToken = null;
        $newRefreshToken = null;

        $tbi = TokenBrowserInfo::query()
            ->where('browser_identifier', $browserIdentifier)
            ->where('refresh_token_id', $refreshToken->id)
            ->first();

        DB::transaction(function () use (
            $user,
            $generateTokenPairs,
            $tbi,
            &$newAccessToken,
            &$newRefreshToken
        ) {
            $oldAccessTokenId = $tbi->access_token_id;

            [$newAccessToken, $newRefreshToken] = $generateTokenPairs->execute($user);

            $tbi->update([
                'access_token_id' => $newAccessToken->accessToken->id,
                'refresh_token_id' => $newRefreshToken->accessToken->id,
            ]);

            if ($oldAccessTokenId) {
                PersonalAccessToken::where('id', $oldAccessTokenId)->delete();
            }

            $user->tokens()
                ->where('name', 'access_token')
                ->where('id', '!=', $newAccessToken->accessToken->id)
                ->delete();

            $user->tokens()
                ->where('name', 'refresh_token')
                ->where('id', '!=', $newRefreshToken->accessToken->id)
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
