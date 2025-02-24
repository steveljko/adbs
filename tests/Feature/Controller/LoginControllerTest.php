<?php

declare(strict_types=1);

namespace Tests\Feature\Controller;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders login page correctly', function () {
    $this->get(route('auth.login'))
        ->assertOk()
        ->assertSee('form')
        ->assertElementExists('input[name="email"]')
        ->assertElementExists('input[name="password"]')
        ->assertSee('button');
});

it('fails when email or password are not filled in', function () {
    $response = $this->post(route('auth.login'));

    $response
        ->assertSessionHasErrors('email')
        ->assertSessionHasErrors('password');
});

it('fails when email is not correct format', function () {
    $response = $this->post(route('auth.login'), [
        'email' => 'exampletestcom',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
});

it('fails when email or password are not correct', function () {
    $response = $this->post(route('auth.login'), [
        'email' => 'test@test.com',
        'password' => 'password',
    ]);

    $response->assertElementExists('span#email-error');
});

it('authenticates user and redirect to dashboard if credentials are correct', function () {
    $user = User::factory()->create();

    $response = $this->post(route('auth.login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);

    $response->assertHeader('HX-Redirect', route('dashboard'));
});
