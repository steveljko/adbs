<?php

declare(strict_types=1);

namespace App\Support\Database;

use Exception;

final class SQLiteConnectionStrategy extends DatabaseConnectionStrategy
{
    public const DBPATH = 'database/database.sqlite';

    public function buildConfig(array $connectionData): array
    {
        return [
            'driver' => 'sqlite',
            'database' => base_path(self::DBPATH),
            'prefix' => '',
            'foreign_key_constraints' => true,
        ];
    }

    public function validateConnection(array $connectionData): void
    {
        $dbPath = base_path(self::DBPATH);
        $directory = dirname($dbPath);

        if (! is_dir($directory)) {
            if (! mkdir($directory, 0755, true)) {
                throw new Exception("Cannot create directory: {$directory}");
            }
        }

        if (! file_exists($dbPath)) {
            if (! touch($dbPath)) {
                throw new Exception("Cannot create SQLite database file: {$dbPath}");
            }
            chmod($dbPath, 0644);
        }

        if (! is_readable($dbPath)) {
            throw new Exception("SQLite database file is not readable: {$dbPath}");
        }

        if (! is_writable($dbPath)) {
            throw new Exception("SQLite database file is not writable: {$dbPath}");
        }
    }

    public function getRequiredFields(): array
    {
        return [];
    }

    public function getDefaultPort(): ?int
    {
        return null;
    }
}
