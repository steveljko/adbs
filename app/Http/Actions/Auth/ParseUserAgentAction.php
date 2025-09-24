<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

final class ParseUserAgentAction
{
    public function execute(?string $userAgent): array
    {
        if (! $userAgent) {
            return [null, null];
        }

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
