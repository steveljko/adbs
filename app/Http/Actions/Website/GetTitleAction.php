<?php

declare(strict_types=1);

namespace App\Http\Actions\Website;

use DOMDocument;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use HeadlessChromium\BrowserFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final class GetTitleAction
{
    private const HTTP_TIMEOUT = 10; // time in seconds to wait for HTTP request

    private const DEFAULT_TITLE = 'No title';

    private const USER_AGENTS = [
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0',
    ];

    private Client $httpClient;

    private BrowserFactory $browserFactory;

    private string $userAgent;

    public function __construct()
    {
        $this->userAgent = self::USER_AGENTS[array_rand(self::USER_AGENTS)];

        $this->httpClient = new Client([
            'timeout' => self::HTTP_TIMEOUT,
            'connect_timeout' => self::HTTP_TIMEOUT,
            'headers' => [
                'User-Agent' => $this->userAgent,
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'none',
                'Sec-Fetch-User' => '?1',
                'Accept-Language' => 'en-US,en;q=0.5',
            ],
            'allow_redirects' => [
                'max' => 10,
                'strict' => false,
                'referer' => true,
                'protocols' => ['http', 'https'],
                'track_redirects' => true,
            ],
            'http_errors' => false, // don't throw exceptions for HTTP error status codes
            'verify' => true, // ssl verification
            'cookies' => true, // enable cookie support
        ]);

        $this->browserFactory = new BrowserFactory();
    }

    public function execute(string $url): string
    {
        try {
            return $this->getTitle($url);
        } catch (ClientException $e) {
            Log::error("Failed to get title for {$url}: {$e->getMessage()}");

            return self::DEFAULT_TITLE;
        }
    }

    /**
     * Get title from URL with proper error handling
     */
    private function getTitle(string $url): string
    {
        try {
            $response = $this->httpClient->get($url);

            $statusCode = $response->getStatusCode();
            if ($statusCode < 200 || $statusCode >= 300) {
                Log::error("HTTP request failed with status code: {$statusCode} for {$url}");

                return self::DEFAULT_TITLE;
            }

            $html = (string) $response->getBody();

            // if content type is not html return fallback title
            $contentType = $response->getHeaderLine('Content-Type');
            if (! $this->isHtmlContentType($contentType)) {
                return self::DEFAULT_TITLE;
            }

            $title = $this->extractTitleFromHtml($html);

            if (empty($title)) {
                $title = $this->getTitleFromHeadlessBrowser($url);
            }

            return ! empty($title) ? $title : self::DEFAULT_TITLE;

        } catch (GuzzleException $e) {
            $statusCode = $e->getCode();
            if ($statusCode === Response::HTTP_FORBIDDEN) {
                $title = $this->getTitleFromHeadlessBrowser($url);

                return ! empty($title) ? $title : self::DEFAULT_TITLE;
            }

            throw new RuntimeException("HTTP request failed: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * Check if content type indicates HTML content
     */
    private function isHtmlContentType(string $contentType): bool
    {
        return str_contains(mb_strtolower($contentType), 'html');
    }

    /**
     * Extract title from HTML content using various methods
     */
    private function extractTitleFromHtml(string $html): string
    {
        if (empty($html)) {
            return '';
        }

        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', mb_detect_encoding($html) ?: 'UTF-8');
        $success = @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOBLANKS | LIBXML_NONET); // load HTML without adding DOCTYPE, html and body tags

        libxml_clear_errors();

        if (! $success) {
            return '';
        }

        // try <title> tag first
        $titleTags = $dom->getElementsByTagName('title');
        if ($titleTags->length > 0) {
            $title = $titleTags->item(0)->textContent;
            if (! empty($title)) {
                return $title;
            }
        }

        // try meta tags
        $metaTags = $dom->getElementsByTagName('meta');
        foreach ($metaTags as $metaTag) {
            if ($metaTag->getAttribute('property') === 'og:title' ||
                $metaTag->getAttribute('name') === 'title' ||
                $metaTag->getAttribute('name') === 'twitter:title') {
                $title = $metaTag->getAttribute('content');
                if (! empty($title)) {
                    return $title;
                }
            }
        }

        // try h1 as last resort
        $h1Tags = $dom->getElementsByTagName('h1');
        if ($h1Tags->length > 0) {
            $title = $h1Tags->item(0)->textContent;
            if (! empty($title)) {
                return $title;
            }
        }

        return '';
    }

    /**
     * Get title using headless browser
     */
    private function getTitleFromHeadlessBrowser(string $url): string
    {
        // create browser instance
        $browser = $this->browserFactory->createBrowser([
            'userAgent' => $this->userAgent,
            'windowSize' => [1280, 800],
            'sendSyncDefaultTimeout' => self::HTTP_TIMEOUT * 1000,
            'ignoreCertificateErrors' => true,
            'headers' => ['Accept-Language' => 'en-US,en;q=0.5'],
            'noSandbox' => true,
        ]);

        try {
            $page = $browser->createPage();

            $navigation = $page->navigate($url);
            $navigation->waitForNavigation(timeout: self::HTTP_TIMEOUT * 1000);

            // wait for JS to initialize
            usleep(500000); // 50ms

            try {
                $title = $page->evaluate('document.title')->getReturnValue();

                if (empty($title)) {
                    $title = $page->evaluate('
                        document.querySelector("meta[property=\'og:title\']")?.content ||
                        document.querySelector("meta[name=\'title\']")?.content ||
                        document.querySelector("meta[name=\'twitter:title\']")?.content ||
                        document.querySelector("h1")?.textContent ||
                        ""
                    ')->getReturnValue();
                }

                return $title;
            } catch (Exception $e) {
                return '';
            }
        } catch (Exception $e) {
            Log::error("Headless browser error: {$e->getMessage()}");

            return '';
        } finally {
            if (isset($browser)) {
                $browser->close();
            }
        }
    }
}
