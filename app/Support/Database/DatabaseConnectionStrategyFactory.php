<?php

declare(strict_types=1);

namespace App\Support\Database;

use Exception;

final class DatabaseConnectionStrategyFactory
{
    private const STRATEGY_MAP = [
        'pgsql' => PostgreSQLConnectionStrategy::class,
        'mysql' => MySQLConnectionStrategy::class,
        'sqlite' => SQLiteConnectionStrategy::class,
    ];

    public static function create(string $driver): DatabaseConnectionStrategy
    {
        if (! isset(self::STRATEGY_MAP[$driver])) {
            throw new Exception("Unsupported database driver: {$driver}");
        }

        $strategyClass = self::STRATEGY_MAP[$driver];

        return new $strategyClass();
    }

    public static function getSupportedDrivers(): array
    {
        return array_keys(self::STRATEGY_MAP);
    }
}
