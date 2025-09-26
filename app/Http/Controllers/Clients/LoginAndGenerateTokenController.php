<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Http\Actions\Auth\CreateTokenBrowserInfoAction;
use App\Http\Actions\Auth\GenerateTokenPairsAction;
use App\Http\Actions\Auth\ParseUserAgentAction as AuthParseUserAgentAction;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class LoginAndGenerateTokenController
{
    /**
     * Handle user login and generate access/refresh tokens with browser information.
     */
    public function __invoke(
        LoginRequest $request,
        AuthParseUserAgentAction $parseUserAgent,
        GenerateTokenPairsAction $generateTokenPairs,
        CreateTokenBrowserInfoAction $createTokenBrowserInfo,
    ): JsonResponse {
        $request->validate(['browser_identifier' => ['required', 'string']]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $accessToken = null;
        $refreshToken = null;

        DB::transaction(function () use (
            $user,
            $request,
            $parseUserAgent,
            $generateTokenPairs,
            $createTokenBrowserInfo,
            &$accessToken,
            &$refreshToken
        ) {
            [$accessToken, $refreshToken] = $generateTokenPairs->execute($user);

            $createTokenBrowserInfo->execute(
                accessToken: $accessToken,
                refreshToken: $refreshToken,
                request: $request,
                parseUserAgent: $parseUserAgent
            );
        });

        return new JsonResponse([
            'access_token' => $accessToken?->plainTextToken,
            'refresh_token' => $refreshToken?->plainTextToken,
            'user' => $user->only(['name', 'email']),
            'expires_in' => 900, // 15 minutes
        ]);
    }
}
