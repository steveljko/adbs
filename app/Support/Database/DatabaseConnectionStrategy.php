<?php

declare(strict_types=1);

namespace App\Support\Database;

abstract class DatabaseConnectionStrategy
{
    abstract public function buildConfig(array $connectionData): array;

    abstract public function validateConnection(array $connectionData): void;

    abstract public function getRequiredFields(): array;

    abstract public function getDefaultPort(): ?int;
}
