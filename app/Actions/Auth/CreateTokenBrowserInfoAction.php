<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\TokenBrowserInfo;
use Illuminate\Http\Request;
use Laravel\Sanctum\NewAccessToken;

final class CreateTokenBrowserInfoAction
{
    public function execute(
        NewAccessToken $accessToken,
        NewAccessToken $refreshToken,
        Request $request,
        ParseUserAgentAction $parseUserAgent,
    ) {
        $userAgent = $request->header('User-Agent');
        [$name, $version] = $parseUserAgent->execute($userAgent);

        TokenBrowserInfo::create([
            'access_token_id' => $accessToken->accessToken->id,
            'refresh_token_id' => $refreshToken->accessToken->id,
            'browser_identifier' => $request->browser_identifier,
            'browser' => $name,
            'browser_version' => $version,
            'user_agent' => $userAgent,
            'ip_address' => $request->ip(),
        ]);
    }
}
