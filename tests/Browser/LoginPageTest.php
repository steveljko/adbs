<?php

declare(strict_types=1);

use App\Models\User;

it('renders login page correctly', function () {
    visit(route('auth.login'))
        ->assertSee('Email Address')
        ->assertSee('Password')
        ->assertSee('Sign In')
        ->press('Sign In')
        ->assertSee('The email field is required.')
        ->assertSee('The password field is required.');
});

it('can login successfully', function () {
    $user = User::factory()->create();

    visit(route('auth.login'))
        ->type('email', $user->email)
        ->type('password', 'password')
        ->press('Sign In')
        ->assertPathIs('/dashboard');
});
