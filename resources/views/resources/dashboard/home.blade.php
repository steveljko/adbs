@extends('layouts.home')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form id="filters" class="flex flex-wrap gap-2 mb-2">
        <input type="text" class="hidden" id="title" name="title" value="{{ request('title') }}">
        @if (!empty($queryTags))
        @foreach ($queryTags as $qtag)
        @include('resources.dashboard.filters.tag', ['tag' => $qtag])
        @endforeach
        @endif
        @if (!empty($querySites))
        @foreach ($querySites as $qsite)
        @include('resources.dashboard.filters.site', ['site' => $qsite])
        @endforeach
        @endif
    </form>

    <x-bookmark-list :type="$viewType" :bookmarks="$bookmarks" />

    @if($hasMore && $nextPage)
    <div class="h-1 w-full" hx-get="{{ route('dashboard') }}" hx-include="#filters" hx-vals='{"view_type": "{{ request('
        view_type', 'card' ) }}", "page" : {{ $nextPage }}, "load_more" : "1" }' hx-trigger="intersect once"
        hx-target="#bookmarks-container" hx-swap="beforeend" hx-indicator="#loading-state">
    </div>
    @endif
</div>
@endsection
