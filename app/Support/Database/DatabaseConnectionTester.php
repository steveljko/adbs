<?php

declare(strict_types=1);

namespace App\Support\Database;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

final class DatabaseConnectionTester
{
    public function testConnection(array $connectionData, DatabaseConnectionStrategy $strategy): void
    {
        $strategy->validateConnection($connectionData);

        $config = $strategy->buildConfig($connectionData);

        Config::set('database.connections.test', $config);

        $connection = DB::connection('test');
        $connection->getPdo();

        if ($config['driver'] !== 'sqlite') {
            if (! $connection->getDatabaseName()) {
                throw new Exception('Unable to connect to the specified database.');
            }
        }

        DB::purge('test');
    }
}
