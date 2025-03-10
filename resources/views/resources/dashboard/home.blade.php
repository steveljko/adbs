@extends('layouts.default')

@section('content')
    <div class="container mt-8 mx-auto">
        <div class="flex justify-end">
            <button class="flex items-center text-md" hx-get="{{ route('bookmarks.create') }}" hx-target="#dialog">
                <x-icons.plus class="size-5 mr-2" />
                Add Bookmark
            </button>
        </div>
        @if (session()->has('message'))
            <div class="border border-red-300 bg-red-50 p-2 rounded text-red-500">
                <span>{{ session()->get('message') }}</span>
            </div>
        @endif
        <div class="my-3">
            <div class="relative">
                <div class="border border-gray-300 flex items-center justify-between px-3 py-2 rounded">
                    <input type="text" name="search" hx-post="{{ route('dashboard.search') }}"
                        class="w-full focus:outline-none" hx-trigger="keyup changed delay:500ms" id="search"
                        hx-swap="innerHTML" hx-include="#filters" hx-target="#suggestions-container" tabindex="-1"
                        hx-indicator="#input_spinner" placeholder="Search by tag...">
                    <x-icons.spinner class="animate-spin w-5 h-5 [&.htmx-request]:block hidden" id="input_spinner" />
                </div>
                <div id="suggestions-container"></div>
            </div>
            <form id="filters" class="flex mt-3 space-x-2">
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
        </div>
        <div class="space-y-2" id="bookmarks" hx-get="{{ route('dashboard') }}" hx-push-url="true" hx-include="#filters"
            hx-trigger="loadBookmarks from:body" hx-swap="innerHTML">
            @fragment('bookmark-list')
                @foreach ($bookmarks as $bookmark)
                    <li class="flex items-center space-x-2">
                        <img src="{{ asset($bookmark->favicon) }}" class="w-5 h-5 rounded select-none" alt="website favicon">
                        <a href="{{ $bookmark->url }}"
                            class="text-gray-600 hover:text-orange-500 hover:underline cursor-pointer text-sm">{{ $bookmark->title }}</a>
                        <button type="button" hx-get="{{ route('bookmarks.edit', $bookmark->id) }}" hx-push-url="false"
                            hx-target="#dialog">Edit</button>
                    </li>
                @endforeach
            @endfragment
        </div>
    </div>
@endsection
