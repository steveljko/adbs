<?php

declare(strict_types=1);

namespace Tests\Feature\Controller;

use App\Models\User;

it('renders create bookmark modal successfully', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('bookmarks.create'))
        ->assertOk()
        ->assertSee('form')
        ->assertElementExists('input[name="url"]')
        ->assertElementExists('#content')
        ->assertSee('button');
});

it('previews correctly website details', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('bookmarks.preview'), [
            'url' => 'https://www.google.com',
        ])
        ->assertOk()
        ->assertElementExists('input[name="title"]')
        ->assertElementExists('input[name="favicon"]');
});

it('creates bookmark when correct input is provided', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('bookmarks.create'), [
            'url' => 'https://www.youtube.com',
            'title' => 'YouTube',
            'favicon' => 'youtube',
        ]);

    $response->assertSessionHasNoErrors();
});

it('fails when invalid url is provided', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('bookmarks.preview'), [
            'url' => 'ww.gooc',
        ]);

    $response->assertSessionHasErrors('url');
});

it('fails when website is unreachable', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('bookmarks.preview'), [
            'url' => 'https://totallyunreachblewebsite.c',
        ]);

    $response->assertSessionHasErrors('url');

    $errors = session('errors')->get('url');
    expect($errors[0])->toBe(__('validation.website_unreachable'));
});
