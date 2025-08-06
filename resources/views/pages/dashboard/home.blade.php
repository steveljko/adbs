@extends('layouts.home')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @include('partials.dashboard.filters', [
            'queryTags' => $queryTags,
            'querySites' => $querySites,
        ])

        <x-bookmarks
            :bookmarks="$bookmarks"
            :type="$viewType"
        />

        <x-loadmore
            :hasMore="$hasMore"
            :nextPage="$nextPage"
            x-data='{ loading: false }'
            x-init="setTimeout(() => loading = true, 500)"
            x-show="loading"
        />
    </div>
@endsection
