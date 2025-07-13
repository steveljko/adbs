<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Support\Database\DatabaseConnectionStrategyFactory;

final class DatabaseConnectionInfo
{
    private string $driver;

    private array $connectionData;

    public function __construct(string $driver, array $connectionData)
    {
        $this->driver = $driver;
        $this->connectionData = $connectionData;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getConnectionData(): array
    {
        return $this->connectionData;
    }

    public function getRequiredFields(): array
    {
        $strategy = DatabaseConnectionStrategyFactory::create($this->driver);

        return $strategy->getRequiredFields();
    }

    public function validate(): void
    {
        $strategy = DatabaseConnectionStrategyFactory::create($this->driver);
        $strategy->validateConnection($this->connectionData);
    }
}
