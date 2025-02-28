<?php

declare(strict_types=1);

namespace App\Http\Actions\Website;

use Illuminate\Support\Facades\Storage;

final class GetFaviconAction
{
    /**
     * Get website favicon using Google API with URL and specified size.
     */
    public function execute(string $url, int $size): string
    {
        $site = parse_url($url, PHP_URL_HOST); // get domain from url
        $name = "$site-$size.png";

        // If file exists in storage, than return path.
        if (Storage::disk('public')->exists("images/$name")) {
            return "/storage/images/{$name}";
        }

        // Fetch favicon with Google API and save to local storage
        $fetchUrl = "https://www.google.com/s2/favicons?domain={$url}&sz={$size}";
        $contents = file_get_contents($fetchUrl);
        Storage::disk('public')->put("images/${name}", $contents);

        return "/storage/images/{$name}";
    }
}
