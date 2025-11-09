<?php

declare(strict_types=1);

namespace App\ValueObjects;

final class TagSyncResult
{
    public function __construct(
        public bool $success,
        public array $attached,
        public array $detached
    ) {}

    public function isSuccessful(): bool
    {
        return $this->success;
    }
}
