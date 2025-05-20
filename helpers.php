<?php

declare(strict_types=1);

if (! function_exists('htmx')) {
    /**
     * Get the HTMX helper instance.
     */
    function htmx(): App\Support\Htmx
    {
        return app(App\Support\Htmx::class);
    }
}
