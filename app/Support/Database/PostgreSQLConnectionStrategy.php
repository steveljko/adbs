<?php

declare(strict_types=1);

namespace App\Support\Database;

use Exception;

final class PostgreSQLConnectionStrategy extends DatabaseConnectionStrategy
{
    public function buildConfig(array $connectionData): array
    {
        return [
            'driver' => 'pgsql',
            'host' => $connectionData['db_host'],
            'port' => $connectionData['db_port'] ?? $this->getDefaultPort(),
            'database' => $connectionData['db_database'],
            'username' => $connectionData['db_username'],
            'password' => $connectionData['db_password'],
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
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
        return 5432;
    }
}
