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
        $envData = ['DB_CONNECTION' => $driver];

        switch ($driver) {
            case 'sqlite':
                $envData['DB_DATABASE'] = SQLiteConnectionStrategy::DBPATH;
                break;
            case 'pgsql':
                $envData = array_merge($envData, [
                    'DB_HOST' => $connectionData['db_host'],
                    'DB_PORT' => $connectionData['db_port'] ?? DatabaseConnectionStrategyFactory::create($driver)->getDefaultPort(),
                    'DB_DATABASE' => $connectionData['db_database'],
                    'DB_USERNAME' => $connectionData['db_username'],
                    'DB_PASSWORD' => $connectionData['db_password'],
                ]);
            case 'mysql':
                $envData = array_merge($envData, [
                    'DB_HOST' => $connectionData['db_host'],
                    'DB_PORT' => $connectionData['db_port'] ?? DatabaseConnectionStrategyFactory::create($driver)->getDefaultPort(),
                    'DB_DATABASE' => $connectionData['db_database'],
                    'DB_USERNAME' => $connectionData['db_username'],
                    'DB_PASSWORD' => $connectionData['db_password'],
                ]);
                break;
        }

        $this->environmentManager->updateEnvironment($envData);
    }
}
