<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\ValueObjects\LoginCredentials;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class LoginAction
{
    public function execute(LoginCredentials $creds): bool
    {
        $normalizeEmail = $this->normalizeEmail($creds->getEmail());

        Log::info('Login attempt initiated', ['email' => $normalizeEmail, 'ip' => request()->ip()]);

        $this->ensureIsNotRateLimited($normalizeEmail);

        if (! $this->attemptLogin(
            email: $normalizeEmail,
            password: $creds->getPassword()
        )) {
            RateLimiter::hit($this->throttleKey($normalizeEmail), 300);

            Log::warning('Login attempt failed', ['email' => $normalizeEmail, 'ip' => request()->ip()]);

            $this->throwFailedException();
        }

        Log::info('Login successful', ['email' => $normalizeEmail, 'ip' => request()->ip()]);

        session()->regenerate();
        RateLimiter::clear($this->throttleKey($normalizeEmail));

        return true;
    }

    private function normalizeEmail(string $email): string
    {
        return Str::lower(Str::trim($email));
    }

    private function attemptLogin(string $email, string $password): bool
    {
        return Auth::attempt([
            'email' => $email,
            'password' => $password,
        ]);
    }

    private function throwFailedException(): never
    {
        throw ValidationException::withMessages([
            'email' => ['Either username or password are incorrect.'],
        ]);
    }

    private function ensureIsNotRateLimited(string $email): void
    {
        $key = $this->throttleKey($email);

        if (! RateLimiter::tooManyAttempts($key, 3)) {
            return;
        }

        Log::warning('Login rate limit exceeded', ['email' => $email, 'ip' => request()->ip()]);

        throw ValidationException::withMessages(['email' => [__('auth.throttle')]]);
    }

    private function throttleKey(string $email): string
    {
        return 'login:'.$email.'|'.request()->ip();
    }
}
