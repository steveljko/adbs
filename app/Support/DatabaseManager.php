<?php

declare(strict_types=1);

namespace App\Support;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

// TODO: add mysql and sqlite support
final class DatabaseManager
{
    public function testAndSetConnection(array $data)
    {
        try {
            $this->testConnection($data);
            $this->setConnection($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Tests database connection using provided connection params.
     */
    public function testConnection(array $conn): void
    {
        Config::set('database.connections.test', [
            'driver' => 'pgsql',
            'host' => $conn['db_host'],
            'port' => $conn['db_port'],
            'database' => $conn['db_database'],
            'username' => $conn['db_username'],
            'password' => $conn['db_password'],
        ]);

        $connection = DB::connection('test');
        $connection->getPdo();

        if (! $connection->getDatabaseName()) {
            throw new Exception('Unable to connect to the specified database.');
        }

        DB::purge('test');
    }

    /**
     * Sets production database connection.
     */
    private function setConnection(array $conn)
    {
        Artisan::call('config:clear');

        DB::purge('pgsql');

        Config::set('database.connections.pgsql', [
            'driver' => 'pgsql',
            'host' => $conn['db_host'],
            'port' => $conn['db_port'],
            'database' => $conn['db_database'],
            'username' => $conn['db_username'],
            'password' => $conn['db_password'],
        ]);

        $this->migrate();

        $this->updateEnv([
            'DB_HOST' => $conn['db_host'],
            'DB_PORT' => $conn['db_port'],
            'DB_DATABASE' => $conn['db_database'],
            'DB_USERNAME' => $conn['db_username'],
            'DB_PASSWORD' => $conn['db_password'],
        ]);

        Artisan::call('config:cache');
    }

    private function updateEnv(array $data): void
    {
        $envFile = base_path('.env');
        $envContent = File::get($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        File::put($envFile, $envContent);
    }

    private function migrate()
    {
        Artisan::call('migrate', ['--force' => true]);
    }
}
