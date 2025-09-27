<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

final class EnsureRefreshTokenIsValidMiddleware
{
    private const REFRESH_TOKEN_NAME = 'refresh_token';

    private const ERROR_INVALID_TOKEN = 'INVALID_REFRESH_TOKEN';

    private const ERROR_EXPIRED_TOKEN = 'REFRESH_TOKEN_EXPIRED';

    /**
     * Ensure refresh token is present, valid, and not expired.
     *
     * @param  Closure(Request): (Response|JsonResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $tokenString = $request->input('refresh_token');

        if (empty($tokenString)) {
            return $this->unauthorizedResponse('Refresh token is required', self::ERROR_INVALID_TOKEN);
        }

        $refreshToken = PersonalAccessToken::findToken($tokenString);

        if (! $this->isValidRefreshToken($refreshToken)) {
            return $this->unauthorizedResponse('Invalid refresh token', self::ERROR_INVALID_TOKEN);
        }

        if ($this->isExpired($refreshToken)) {
            $refreshToken->delete();

            return $this->unauthorizedResponse('Refresh token has expired', self::ERROR_EXPIRED_TOKEN);
        }

        $request->merge(['refreshToken' => $refreshToken]);

        return $next($request);
    }

    /**
     * Checks if token has correct name.
     */
    private function isValidRefreshToken(?PersonalAccessToken $token): bool
    {
        return $token !== null && $token->name === self::REFRESH_TOKEN_NAME;
    }

    /**
     * Checks if token is expired.
     */
    private function isExpired(PersonalAccessToken $token): bool
    {
        return $token->expires_at !== null && $token->expires_at->isPast();
    }

    /**
     * Sends JSON response.
     */
    private function unauthorizedResponse(string $message, string $errorCode): JsonResponse
    {
        return new JsonResponse([
            'message' => $message,
            'error' => $errorCode,
        ], HttpResponse::HTTP_UNAUTHORIZED);
    }
}
