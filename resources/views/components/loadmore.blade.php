@props(['hasMore', 'nextPage'])

@if ($hasMore && $nextPage)
    <div
        class="h-1 w-full"
        hx-get="{{ route('dashboard') }}"
        hx-include="#filters"
        hx-oob-swap="true"
        hx-swap="beforeend"
        hx-target="#bookmarks-container"
        hx-trigger="intersect once"
        hx-vals='{"view_type": "{{ request('view_type', 'card') }}", "page": {{ $nextPage }}, "load_more": "1"}'
        id="load_more"
        x-bind:class="{ 'hidden': loading }"
        x-data="{ loading: true }"
        x-init="setTimeout(() => {
            loading = false;
            $nextTick(() => htmx.process($el));
        }, 200)"
    >
    </div>
@endif
