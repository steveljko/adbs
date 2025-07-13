<?php

declare(strict_types=1);

namespace App\Support\Database;

use Exception;
use PDO;

final class MySQLConnectionStrategy extends DatabaseConnectionStrategy
{
    public function buildConfig(array $connectionData): array
    {
        return [
            'driver' => 'mysql',
            'host' => $connectionData['db_host'],
            'port' => $connectionData['db_port'] ?? $this->getDefaultPort(),
            'database' => $connectionData['db_database'],
            'username' => $connectionData['db_username'],
            'password' => $connectionData['db_password'],
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ];
    }

    public function validateConnection(array $connectionData): void
    {
        $requiredFields = $this->getRequiredFields();
        foreach ($requiredFields as $field) {
            if (empty($connectionData[$field])) {
                throw new Exception("Required field {$field} is missing");
            }
        }
    }

    public function getRequiredFields(): array
    {
        return ['db_host', 'db_database', 'db_username', 'db_password'];
    }

    public function getDefaultPort(): ?int
    {
        return 3306;
    }
}
