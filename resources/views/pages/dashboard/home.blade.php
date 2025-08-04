@extends('layouts.home')

@section('content')
    <div
        class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8"
    >
        @include('partials.dashboard.filters', [
            'queryTags' => $queryTags,
            'querySites' => $querySites,
        ])

        <x-bookmarks
            :bookmarks="$bookmarks"
            :type="$viewType"
        />
    </div>
@endsection
