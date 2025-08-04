@props(['type' => 'card', 'bookmarks', 'showSwitch' => false])

<div
    hx-get="{{ route('dashboard') }}"
    hx-include="#filters"
    hx-push-url="true"
    hx-target="#bookmarks-container"
    hx-trigger="loadBookmarks from:body"
    hx-vals='{"view_type": "{{ request('view_type', 'card') }}"}'
    x-bind:class="{ 'hidden': !show }"
    x-data="{ 'show': false }"
    x-init="setTimeout(() => show = true, 250)"
>
    @if ($type == 'list')
        <div
            class="w-full"
            id="bookmarks-container"
        >
            @foreach ($bookmarks as $bookmark)
                @include('partials.bookmark.list', ['bookmark' => $bookmark])
            @endforeach
        </div>
    @else
        <div
            class="w-full"
            id="bookmarks-container"
        >
            @foreach ($bookmarks as $bookmark)
                <div
                    class="bookmark-card mb-4 shadow"
                    style="width: calc(33% - 16px)"
                    x-data="{ 'loading': true }"
                    x-init="setTimeout(() => loading = false, 200)"
                >
                    <template
                        x-if="loading"
                    >
                        @include('partials.bookmark.card-loading', ['bookmark' => $bookmark])
                    </template>
                    <template
                        x-if="!loading"
                    >
                        <div
                            x-init="$nextTick(() => htmx.process($el))"
                        >
                            @include('partials.bookmark.card', ['bookmark' => $bookmark])
                        </div>
                    </template>
                </div>
            @endforeach
        </div>
    @endif
</div>
