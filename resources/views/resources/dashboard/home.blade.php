@extends('layouts.default')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">My Bookmarks</h1>

            <div class="flex items-center space-x-4">
                <x-button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center text-sm font-medium transition-colors duration-200"
                    hx-get="{{ route('bookmarks.create') }}" hx-target="#dialog">
                    <x-icons.plus class="size-4 mr-2" />
                    Add Bookmark
                </x-button>

                <div class="relative">
                    <button id="userMenuButton" class="flex cursor-pointer border border-gray-300 bg-white shadow-sm px-4 py-2 h-[36px] rounded items-center space-x-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->email }}</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transition-transform duration-200" id="dropdownArrow">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

        <div id="userDropdown" class="absolute hidden right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="py-1">
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings
                            </a>
                            <hr class="border-gray-200">
                            <a hx-delete="{{ route('auth.logout') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors cursor-pointer">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash message -->
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

        <!-- Search and filter section -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 mb-3">
            <div class="relative">
                <div
                    class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all duration-200">
                    <div class="pl-3 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" hx-post="{{ route('dashboard.search') }}"
                        class="w-full py-2.5 px-3 focus:outline-none text-sm" hx-trigger="focus, keyup changed delay:250ms"
                        id="search" hx-include="#filters" value="{{ request('title') }}"
                        placeholder="Search bookmarks by tag or title..." hx-indicator="#input_spinner" autocomplete="off">
                    <x-icons.spinner class="animate-spin w-5 h-5 mr-3 [&.htmx-request]:block hidden" id="input_spinner" />
                </div>
                <div id="suggestions-container" class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg"></div>
            </div>
        </div>

        <!-- Active filters -->
        <form id="filters" class="flex flex-wrap gap-2 mb-3">
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
