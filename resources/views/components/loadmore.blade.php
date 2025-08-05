@props(['hasMore', 'nextPage'])

@if ($hasMore && $nextPage)
    <div
        class="h-1 w-full"
        hx-get="{{ route('dashboard') }}"
        hx-include="#filters"
        hx-swap-oob="true"
        hx-swap="beforeend"
        hx-target="#bookmarks-container"
        hx-trigger="intersect once"
        hx-vals='{
            "view_type": "{{ request('view_type', 'card') }}",
            "page": {{ $nextPage }},
            "load_more": "1"
        }'
        id="load_more"
        style="display: none"
        x-data='{
            loading: false,
            isSwappedIn: {{ request()->has('load_more') ? 'true' : 'false' }}
        }'
        x-init="if (isSwappedIn) {
            setTimeout(() => loading = true, 1500);
        } else {
            setTimeout(() => loading = true, 500);
        }"
        x-show="loading"
    >
    </div>
@endif
