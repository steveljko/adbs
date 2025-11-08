<?php

declare(strict_types=1);

namespace App\Actions\Website;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class GetFaviconAction
{
    private const DIR = 'images/favicons';

    private const API = 'https://www.google.com/s2/favicons';

    /**
     * Get website favicon using Google API.
     */
    public function execute(string $url, int $size = 64): ?string
    {
        $domain = $this->extractDomain($url);
        $filename = $this->generateFilename($domain, $size);
        $storagePath = self::DIR.'/'.$filename;

        // return public path if favicon already exists on disk
        if (Storage::disk('public')->exists($storagePath)) {
            return $this->getPublicPath($storagePath);
        }

        $faviconContent = $this->fetchFavicon($url, $size);
        $this->storeFavicon($storagePath, $faviconContent);

        return $this->getPublicPath($storagePath);
    }

    private function extractDomain(string $url): string
    {
        $domain = parse_url($url, PHP_URL_HOST);

        if ($domain === null || $domain === false) {
            throw new RuntimeException("Invalid URL provided: {$url}");
        }

        return $domain;
    }

    private function generateFilename(string $domain, int $size): string
    {
        return sprintf('%s-%d.png', $domain, $size);
    }

    private function fetchFavicon(string $url, int $size): string
    {
        try {
            $response = Http::timeout(10)
                ->retry(2, 100)
                ->get(self::API, [
                    'domain' => $url,
                    'sz' => $size,
                ]);

            if ($response->failed()) {
                throw new RuntimeException(
                    "Failed to fetch favicon for {$url}. Status: {$response->status()}"
                );
            }

            return $response->body();
        } catch (Exception $e) {
            Log::error('Favicon fetch failed', [
                'url' => $url,
                'size' => $size,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException("Unable to fetch favicon: {$e->getMessage()}", 0, $e);
        }
    }

    private function storeFavicon(string $path, string $content): void
    {
        if (! Storage::disk('public')->put($path, $content)) {
            throw new RuntimeException("Failed to store favicon at {$path}");
        }
    }

    private function getPublicPath(string $storagePath): string
    {
        return '/storage/'.$storagePath;
    }
}
