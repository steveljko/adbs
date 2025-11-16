<?php

declare(strict_types=1);

use App\Models\User;

describe('rendering', function () {
    it('renders login page correctly', function () {
        visit(route('auth.login'))
            ->assertSee('Email Address')
            ->assertSee('Password')
            ->assertSee('Sign In');
    });
});

describe('validation', function () {
    it('validates required fields on login', function () {
        visit(route('auth.login'))
            ->press('Sign In')
            ->assertSee('The email field is required.')
            ->assertSee('The password field is required.');
    });

    it('validates email format', function () {
        visit(route('auth.login'))
            ->type('email', 'invalid-email')
            ->type('password', 'password')
            ->press('Sign In')
            ->assertSee('The email field must be a valid email address.');
    });

    it('requires minimum password length', function () {
        visit(route('auth.login'))
            ->type('email', 'test@example.com')
            ->type('password', '123')
            ->press('Sign In')
            ->assertSee('The password field must be at least');
    });

    it('clears validation errors on successful login', function () {
        $user = User::factory()->create();

        visit(route('auth.login'))
            ->press('Sign In')
            ->assertSee('The email field is required.')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Sign In')
            ->assertPathIs('/dashboard')
            ->assertDontSee('The email field is required.');
    });
});

describe('successful authentication', function () {
    it('can login successfully', function () {
        $user = User::factory()->create();

        visit(route('auth.login'))
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Sign In')
            ->assertPathIs('/dashboard');
    });

    it('handles case-insensitive email login', function () {
        User::factory()->create(['email' => 'test@example.com']);

        visit(route('auth.login'))
            ->type('email', 'TEST@EXAMPLE.COM')
            ->type('password', 'password')
            ->press('Sign In')
            ->assertPathIs('/dashboard');
    });

    it('authenticates user with correct credentials', function () {
        $user = User::factory()->create();

        visit(route('auth.login'))
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Sign In');

        expect(auth()->check())->toBeTrue();
        expect(auth()->id())->toBe($user->id);
    });
});

describe('failed authentication', function () {
    it('fails login with invalid credentials', function () {
        $user = User::factory()->create();

        visit(route('auth.login'))
            ->type('email', $user->email)
            ->type('password', 'wrongpassword')
            ->press('Sign In')
            ->assertSee('Either username or password are incorrect.');
    });
});
