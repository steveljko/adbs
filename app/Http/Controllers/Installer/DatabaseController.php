<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Requests\Installer\DatabaseConnectionRequest;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

final class DatabaseController
{
    public function __invoke(): View
    {
        return view('resources.installer.database');
    }

    public function run(DatabaseConnectionRequest $request)
    {
        try {
            $this->testDatabaseConnection($request->validated());
            $this->updateEnv([
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_database,
                'DB_USERNAME' => $request->db_username,
                'DB_PASSWORD' => $request->db_password,
            ]);

            Artisan::call('config:clear');
            Artisan::call('config:cache');
            Artisan::call('migrate');

            $dateTimestamp = date('Y/m/d h:i:sa');
            File::put(storage_path('installed'), $dateTimestamp);

            return htmx()
                ->redirect(route('auth.login'))
                ->response(null);
        } catch (Exception $e) {
            return htmx()
                ->toast(type: 'error', text: $this->getUserFriendlyMessage($e))
                ->response(null);
        }
    }

    private function testDatabaseConnection(array $config): void
    {
        Config::set('database.connections.test', [
            'driver' => 'pgsql',
            'host' => $config['db_host'],
            'port' => $config['db_port'],
            'database' => $config['db_database'],
            'username' => $config['db_username'],
            'password' => $config['db_password'],
        ]);

        $connection = DB::connection('test');
        $connection->getPdo();

        if (! $connection->getDatabaseName()) {
            throw new Exception('Unable to connect to the specified database.');
        }
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
