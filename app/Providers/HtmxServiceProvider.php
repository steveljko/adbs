<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Htmx;
use Illuminate\Support\ServiceProvider;

final class HtmxServiceProvider extends ServiceProvider
{
    /**
     * Register HTMX service.
     */
    public function register(): void
    {
        $this->app->singleton(Htmx::class);

        if (! function_exists('htmx')) {
            $this->app->bind('htmx', function ($app) {
                return $app->make(Htmx::class);
            });
        }
    }
}
