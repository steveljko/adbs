<?php

declare(strict_types=1);

namespace App\Http\Actions\Website;

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Exception\OperationTimedOut;

final class GetTitleAction
{
    public const TIMEOUT = 10; // time in seconds to wait for website to respond

    /**
     * Get website title using URL.
     */
    public function execute(string $url): string
    {
        $browser = (new BrowserFactory(null))->createBrowser([
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:133.0) Gecko/20100101 Firefox/133.0', // set user-agent to bypass cloudflare
        ]);

        $page = $browser->createPage();

        try {
            $page
                ->navigate($url)
                ->waitForNavigation(timeout: self::TIMEOUT * 1000);

            $title = $page->evaluate('document.title')->getReturnValue();
            $browser->close();

            return $title;
        } catch (OperationTimedOut $e) {
            // If page is not loaded.
            $browser->close();

            return 'No title';
        }
    }
}
