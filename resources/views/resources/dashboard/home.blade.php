@extends('layouts.home')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session()->has('message'))
            <div class="border border-red-300 bg-red-50 p-3 rounded-md text-red-600 mb-6 flex items-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session()->get('message') }}</span>
            </div>
        @endif

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
