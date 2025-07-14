<?php

declare(strict_types=1);

namespace App\Support;

use App\Support\Database\DatabaseConnectionStrategy;
use App\Support\Database\DatabaseConnectionStrategyFactory;
use App\Support\Database\DatabaseConnectionTester;
use App\Support\Database\SQLiteConnectionStrategy;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

final class DatabaseManager
{
    private DatabaseConnectionTester $connectionTester;

    private EnvironmentManager $environmentManager;

    public function __construct(
        DatabaseConnectionTester $connectionTester,
        EnvironmentManager $environmentManager,
    ) {
        $this->connectionTester = $connectionTester;
        $this->environmentManager = $environmentManager;
    }

    public function install(string $driver, array $connectionData): void
    {
        $strategy = DatabaseConnectionStrategyFactory::create($driver);

        $this->connectionTester->testConnection($connectionData, $strategy);

        $this->setupConnection($driver, $connectionData, $strategy);

        $this->updateEnvironment($driver, $connectionData);

        Artisan::call('migrate', ['--force' => true]);

        Artisan::call('config:clear');
        Artisan::call('config:cache');
    }

    private function setupConnection(string $driver, array $connectionData, DatabaseConnectionStrategy $strategy): void
    {
        $config = $strategy->buildConfig($connectionData);

        Artisan::call('config:clear');
        DB::purge($driver);
        Config::set("database.connections.{$driver}", $config);
    }

    private function updateEnvironment(string $driver, array $connectionData): void
    {
        $config = $this->normalizeConnectionData($driver, $connectionData);
        $this->environmentManager->updateDatabaseEnvironment($driver, $config);
    }

    private function normalizeConnectionData(string $driver, array $connectionData): array
    {
        if ($driver === 'sqlite') {
            return ['database' => base_path(SQLiteConnectionStrategy::DBPATH)];
        }

        return [
            'host' => $connectionData['db_host'],
            'port' => $connectionData['db_port'] ?? DatabaseConnectionStrategyFactory::create($driver)->getDefaultPort(),
            'database' => $connectionData['db_database'],
            'username' => $connectionData['db_username'],
            'password' => $connectionData['db_password'],
        ];
    }
}
