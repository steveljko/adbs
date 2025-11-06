@props([
    'type' => 'card',
    'bookmarks',
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
            class="transition-opacity duration-300"
            x-bind:class="{ 'opacity-0': !show, 'opacity-100': show }"
            x-data="{ 'show': false }"
            x-init="setTimeout(() => show = true, 100)"
        >
            @if ($type == 'list')
                <div class="space-y-2">
                    @foreach ($bookmarks as $bookmark)
                        <div
                            x-data="{ 'loading': true }"
                            x-init="setTimeout(() => loading = false, 50)"
                        >
                            <template x-if="loading">
                                @include('partials.bookmark.list-loading', ['bookmark' => $bookmark])
                            </template>
                            <template x-if="!loading">
                                <div x-init="$nextTick(() => htmx.process($el))">
                                    @include('partials.bookmark.list', ['bookmark' => $bookmark])
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-4 xl:grid-cols-4">
                    @foreach ($bookmarks as $bookmark)
                        <div
                            class="animate-fade-in-up"
                            style="animation-delay: {{ $loop->index * 30 }}ms;"
                            x-data="{ 'loading': true }"
                            x-init="setTimeout(() => loading = false, 50)"
                        >
                            <template x-if="loading">
                                @include('partials.bookmark.card-loading', ['bookmark' => $bookmark])
                            </template>
                            <template x-if="!loading">
                                <div
                                    x-init="$nextTick(() => htmx.process($el))"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter="transition ease-out duration-300"
                                >
                                    @include('partials.bookmark.card', ['bookmark' => $bookmark])
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endfragment
</div>
