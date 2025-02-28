<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class ShowDashboardController
{
    public function __invoke(): View
    {
        $bookmarks = Auth::user()
            ->bookmarks()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resources.dashboard.home', ['bookmarks' => $bookmarks]);
    }
}
