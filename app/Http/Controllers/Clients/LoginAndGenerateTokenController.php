<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Http\Requests\LoginRequest;
use App\Models\TokenBrowserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class LoginAndGenerateTokenController
{
    /**
     * Handle user login and generate access/refresh tokens with browser information.
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $accessToken = null;
        $refreshToken = null;

        $userAgent = $request->header('User-Agent');
        [$name, $version] = $this->getAgentInfo($userAgent);

        DB::transaction(function () use (
            $user,
            $request,
            $userAgent,
            $name,
            $version,
            &$accessToken,
            &$refreshToken
        ) {
            $accessToken = $user->createToken('access_token', ['*'], now()->addMinutes(15));
            $refreshToken = $user->createToken('refresh_token', ['*'], now()->addDays(30));

            TokenBrowserInfo::create([
                'personal_access_token_id' => $accessToken->accessToken->id,
                'browser' => $name,
                'browser_version' => $version,
                'user_agent' => $userAgent,
                'ip_address' => $request->ip(),
            ]);
        });

        if ($accessToken != null && $refreshToken != null) {
            return new JsonResponse([
                'access_token' => $accessToken?->plainTextToken,
                'refresh_token' => $refreshToken?->plainTextToken,
                'user' => $user->only(['name', 'email']),
                'expires_in' => 900 // 15 minutes
            ]);
        }
    }

    private function getAgentInfo($userAgent)
    {
        $trimmedString = mb_trim($userAgent);
        $words = preg_split('/\s+/', $trimmedString);
        $lastWord = array_pop($words);

        if (mb_strpos($lastWord, '/') !== false) {
            [$name, $version] = explode('/', $lastWord);

            return [$name, $version];
        }

        return [null, null];
    }
}
