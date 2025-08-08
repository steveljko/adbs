<?php

declare(strict_types=1);

if (! function_exists('htmx')) {
    /**
     * Get HTMX helper instance.
     */
    function htmx(): App\Support\Htmx
    {
        return app(App\Support\Htmx::class);
    }
}

if (! function_exists('preferences')) {
    /**
     * Get preferences for logged in user.
     */
    function preferences(): App\Support\PreferencesManager
    {
        if (auth()->check()) {
            return app(App\Support\PreferencesManager::class)::for(auth()->user());
        }

        return app(App\Support\PreferencesManager::class);
    }
}
