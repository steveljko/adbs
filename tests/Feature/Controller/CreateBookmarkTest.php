<?php

declare(strict_types=1);

namespace Tests\Feature\Controller;

use App\Models\User;
use Illuminate\Http\Response;

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
            'tags' => ['youtube', 'entertainment'],
        ]);

    $response->assertStatus(Response::HTTP_OK);

    $this->assertDatabaseCount('bookmarks', 1);
    $this->assertDatabaseCount('tags', 2);
});

it('fails when invalid url is provided', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('bookmarks.preview'), [
            'url' => 'ww.gooc',
        ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('fails when website is unreachable', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('bookmarks.preview'), [
            'url' => 'https://totallyunreachblewebsite.c',
        ]);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonFragment([
            'url' => [__('validation.website_unreachable')],
        ]);
});

it('fails when bookmark tags are not unique', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('bookmarks.create'), [
            'url' => 'https://www.youtube.com',
            'title' => 'YouTube',
            'favicon' => 'youtube',
            'tags' => ['asd', 'asd'],
        ]);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonFragment([
            'tags' => ['All tags should be unique.'],
        ]);
});
