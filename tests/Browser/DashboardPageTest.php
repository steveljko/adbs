<?php

declare(strict_types=1);

use App\Actions\Bookmark\CreateBookmarkAction;
use App\Models\Tag;
use App\Models\User;

it('displays bookmarks on dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $action = app(CreateBookmarkAction::class);
    $bookmark = $action->execute([
        'url' => 'https://www.youtube.com',
        'title' => 'Test',
        'tags' => ['youtube'],
    ], $user->id);

    visit(route('dashboard'))
        ->assertSee($bookmark->title)
        ->assertSee(parse_url($bookmark->url, PHP_URL_HOST));
});

it('displays multiple bookmark cards', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $action = app(CreateBookmarkAction::class);

    for ($i = 0; $i < 5; $i++) {
        $action->execute([
            'url' => 'https://www.youtube.com',
            'title' => 'Test',
            'tags' => ['youtube'],
        ], $user->id);
    }

    visit(route('dashboard'))
        ->assertCount('#card', 5);
});

it('updates bookmark title through edit dialog', function () {
    pest()->browser()->timeout(10000);

    $user = User::factory()->create();
    $this->actingAs($user);

    $action = app(CreateBookmarkAction::class);

    $action->execute([
        'url' => 'https://www.youtube.com',
        'title' => 'Test',
        'tags' => ['youtube'],
    ], $user->id);

    visit(route('dashboard'))
        ->assertCount('#card', 1)
        ->click('#card button[title="Edit bookmark"]')
        ->type('#dialog #title', 'New Title')
        ->click('#dialog button[type="submit"]')
        ->assertSee('New Title');
});

it('adds new tags to bookmark through edit dialog', function () {
    pest()->browser()->timeout(10000);

    $user = User::factory()->create();
    $this->actingAs($user);

    $action = app(CreateBookmarkAction::class);

    $bookmark = $action->execute([
        'url' => 'https://www.youtube.com',
        'title' => 'Test',
        'tags' => ['youtube'],
    ], $user->id);

    visit(route('dashboard'))
        ->assertCount('#card', 1)
        ->click('#card button[title="Edit bookmark"]')
        ->typeSlowly('#tags-field input[name="name"]', '')
        ->typeSlowly('#tags-field input[name="name"]', 't')
        ->click('ul#suggestions li[role="option"]')
        ->click('#dialog button[type="submit"]');

    expect(Tag::where('name', 't')->exists())->toBeTrue();
    expect($bookmark->fresh()->tags()->count())->toBe(2);
});

it('removes bookmark card after deletion', function () {
    pest()->browser()->timeout(10000);

    $user = User::factory()->create();
    $this->actingAs($user);

    $action = app(CreateBookmarkAction::class);

    $action->execute([
        'url' => 'https://www.youtube.com',
        'title' => 'Test',
        'tags' => ['youtube'],
    ], $user->id);

    visit(route('dashboard'))
        ->assertCount('#card', 1)
        ->click('#card button[title="Delete bookmark"]')
        ->click('#dialog button[type="submit"]')
        ->assertCount('#card', 0);
});
