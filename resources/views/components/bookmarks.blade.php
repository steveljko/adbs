@props([
    'type' => 'card',
    'bookmarks',
    'showSwitch' => false,
])

<div
    hx-get="{{ route('dashboard') }}"
    hx-include="#filters"
    hx-push-url="true"
    hx-swap="outerHTML"
    hx-trigger="loadBookmarks from:body"
    id="bookmarks-container"
>
    @fragment('bookmarks')
        <div
            x-bind:class="{ 'hidden': !show }"
            x-data="{ 'show': false }"
            x-init="setTimeout(() => show = true, 250)"
        >
            @if ($type == 'list')
                <div
                    x-data="{ 'loading': true }"
                    x-init="setTimeout(() => loading = false, 500)"
                >
                    @foreach ($bookmarks as $bookmark)
                        <template x-if="loading">
                            @include('partials.bookmark.list-loading', ['bookmark' => $bookmark])
                        </template>
                        <template x-if="!loading">
                            <div x-init="$nextTick(() => htmx.process($el))">
                                @include('partials.bookmark.list', ['bookmark' => $bookmark])
                            </div>
                        </template>
                    @endforeach
                </div>
            @else
                @foreach ($bookmarks as $bookmark)
                    <div
                        class="bookmark-card mb-4 shadow"
                        style="width: calc(33% - 16px)"
                        x-data="{ 'loading': true }"
                        x-init="setTimeout(() => loading = false, 50)"
                    >
                        <template x-if="loading">
                            @include('partials.bookmark.card-loading', ['bookmark' => $bookmark])
                        </template>
                        <template x-if="!loading">
                            <div x-init="$nextTick(() => htmx.process($el))">
                                @include('partials.bookmark.card', ['bookmark' => $bookmark])
                            </div>
                        </template>
                    </div>
                @endforeach
            @endif
        </div>
    @endfragment
</div>
@if ($showSwitch)
    <x-view-switch />
@endif
