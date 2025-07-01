<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAppIsInstalledMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! file_exists(storage_path('installed'))) {
            if ($request->routeIs('installer*')) {
                return $next($request);
            }

            return redirect()->route('installer.welcome');
        }

        return $next($request);
    }
}
