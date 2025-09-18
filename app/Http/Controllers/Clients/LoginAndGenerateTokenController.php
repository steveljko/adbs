<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Http\Requests\LoginRequest;
use App\Models\TokenBrowserInfo;
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
        $accessToken = $user->createToken('extension-token', ['*'], now()->addMinutes(15));
        $refreshToken = $user->createToken('extension-refresh-token', ['*'], now()->addDays(30))->plainTextToken;

        $userAgent = $request->header('User-Agent');
        [$name, $version] = $this->getAgentInfo($userAgent);

        TokenBrowserInfo::create([
            'personal_access_token_id' => $accessToken->accessToken->id,
            'browser' => $name,
            'browser_version' => $version,
            'user_agent' => $userAgent,
            'ip_address' => $request->ip(),
        ]);

        return new JsonResponse([
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken,
            'user' => $user->only(['name', 'email']),
            'expires_in' => 900 // 15 minutes
        ]);
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
