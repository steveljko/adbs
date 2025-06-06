<?php

declare(strict_types=1);

namespace App\Http\Controllers\AddonClients;

use App\Models\AddonClients;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class LoginAndGenerateTokenController
{
    public function __invoke(Request $request): JsonResponse
    {
        if (empty($request->header('X-Addon-Version'))) {
            return new JsonResponse([
                'status' => 'failed',
            ], Response::HTTP_FORBIDDEN);
        }

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($data)) {
            return new JsonResponse([
                'status' => 'failed',
            ], Response::HTTP_FORBIDDEN);
        }

        $token = Str::random(48);

        $userAgent = $request->header('User-Agent');
        [$name, $version] = $this->getAgentInfo($userAgent);

        $ac = new AddonClients();
        $ac->token = Hash::make($token);
        $ac->browser = $name;
        $ac->browser_version = $version;
        $ac->user_agent = $userAgent;
        $ac->addon_version = $request->header('X-Addon-Version', null);
        $ac->ip_address = $request->ip();
        $ac->status = 'unaccepted';
        $ac->save();

        return new JsonResponse([
            'status' => 'ok',
            'token' => $token,
        ], Response::HTTP_OK);
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
