@extends('layouts.home')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Active filters -->
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
</div>
@endsection
