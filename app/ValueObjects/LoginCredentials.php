<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Http\Requests\LoginRequest;

final readonly class LoginCredentials
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
            $request->input('email'),
            $request->input('password')
        );
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
