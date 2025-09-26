<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureRefreshTokenIsValidMiddleware
{
    /**
     * Check if refresh token is present and not expired.
     *
     * @param  Closure(Request): (Response|JsonResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $refreshToken = $request->input('refresh_token');
        $refreshToken = PersonalAccessToken::findToken($refreshToken);

        if (! $refreshToken || $refreshToken->name !== 'refresh_token') {
            return new JsonResponse([
                'message' => 'Invalid refresh token',
                'error' => 'INVALID_REFRESH_TOKEN',
            ], 401);
        }

        if ($refreshToken->expires_at && $refreshToken->expires_at->isPast()) {
            $refreshToken->delete();

            return new JsonResponse([
                'message' => 'Refresh token expired',
                'error' => 'REFRESH_TOKEN_EXPIRED',
            ], 401);
        }

        return $next($request);
    }
}
