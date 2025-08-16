<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\AddonClientStatus;
use App\Enums\ApiResponseStatus;
use App\Models\AddonClients;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

final class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        if (empty($request->header('X-Addon-Version'))) {
            return new JsonResponse([
                'status' => ApiResponseStatus::FAILED,
                'message' => 'X-Addon-Version header is required',
            ], HttpResponse::HTTP_FORBIDDEN);
        }

        $token = $this->extractToken($request);

        if (empty($token)) {
            return new JsonResponse([
                'status' => ApiResponseStatus::FAILED,
                'message' => 'Authentication token is required',
            ], HttpResponse::HTTP_UNAUTHORIZED);
        }

        $addonClient = $this->validateToken($token);

        if (! $addonClient) {
            return new JsonResponse([
                'status' => ApiResponseStatus::FAILED,
                'message' => 'Invalid authentication token',
            ], HttpResponse::HTTP_UNAUTHORIZED);
        }

        if ($addonClient->status === AddonClientStatus::REVOKED || $addonClient->status === AddonClientStatus::INACTIVE) {
            return new JsonResponse([
                'status' => ApiResponseStatus::FAILED,
                'message' => 'Token has been revoked or blocked',
            ], HttpResponse::HTTP_FORBIDDEN);
        }

        $addonClient->update(['last_activity_at' => now()]);

        $request->merge([
            'token' => $addonClient->token,
            'user' => $addonClient->user,
        ]);

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        if ($token = $request->bearerToken()) {
            return str_replace('Bearer ', '', $token);
        }

        return null;
    }

    private function validateToken(string $token): ?AddonClients
    {
        $addonClients = AddonClients::all();

        foreach ($addonClients as $client) {
            if (Hash::check($token, $client->token)) {
                return $client;
            }
        }

        return null;
    }
}
