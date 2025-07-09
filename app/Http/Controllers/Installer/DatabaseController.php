<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Requests\Installer\DatabaseConnectionRequest;
use App\Support\DatabaseManager;
use Exception;
use Illuminate\View\View;

final class DatabaseController
{
    public function __invoke(): View
    {
        return view('pages.installer.database');
    }

    public function run(
        DatabaseConnectionRequest $request,
        DatabaseManager $dbManager,
    ) {
        try {
            $dbManager->testAndSetConnection($request->validated());

            return htmx()
                ->redirect(route('installer.user'))
                ->response(null);
        } catch (Exception $e) {
            return htmx()
                ->toast(type: 'error', text: $this->getUserFriendlyMessage($e))
                ->swap('none')
                ->response(null);
        }
    }

    private function getUserFriendlyMessage(Exception $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'Connection refused')) {
            return 'Unable to connect to database server. Please check host and port.';
        }

        if (str_contains($message, 'authentication failed')) {
            return 'Database authentication failed. Please check username and password.';
        }

        if (str_contains($message, 'database')) {
            return 'The specified database does not exist.';
        }

        return 'Database connection failed';
    }
}
