<?php

declare(strict_types=1);

namespace App\ValueObjects;

final class TagAttachOrCreateResult
{
    public function __construct(
        public bool $success,
        public int $attached,
    ) {}

    public function isSuccessful(): bool
    {
        return $this->success;
    }
}
