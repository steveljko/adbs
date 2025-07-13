<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Requests\Installer\DatabaseConnectionRequest;
use App\Support\Database\DatabaseConnectionStrategyFactory;
use App\Support\DatabaseManager;
use App\ValueObjects\DatabaseConnectionInfo;
use Exception;
use Illuminate\View\View;

final class DatabaseController
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $manager)
    {
        $this->databaseManager = $manager;
    }

    public function __invoke(): View
    {
        $supportedDrivers = DatabaseConnectionStrategyFactory::getSupportedDrivers();

        $options = [];
        foreach ($supportedDrivers as $driver) {
            $extensionName = "pdo_{$driver}";
            $options[$driver] = [
                'label' => $this->getDriverLabel($driver).
                    (! extension_loaded($extensionName) ? ' (Extension not installed)' : ''),
                'disabled' => ! extension_loaded($extensionName),
            ];
        }

        return view('pages.installer.database', compact('options'));
    }

    public function select(): View
    {
        $dbDriver = request()->query('db_driver');

        if (! in_array($dbDriver, DatabaseConnectionStrategyFactory::getSupportedDrivers())) {
            abort(400, 'Invalid database driver');
        }

        return view("pages.installer.database_{$dbDriver}");
    }

    public function run(DatabaseConnectionRequest $request)
    {
        try {
            $connectionInfo = new DatabaseConnectionInfo(
                $request->input('db_driver'),
                $request->validated()
            );

            $this->databaseManager->install(
                $connectionInfo->getDriver(),
                $connectionInfo->getConnectionData()
            );

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

    private function getDriverLabel(string $driver): string
    {
        return match ($driver) {
            'pgsql' => 'PostgreSQL',
            'mysql' => 'MySQL',
            'sqlite' => 'SQLite',
            default => ucfirst($driver),
        };
    }
}
