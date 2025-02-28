@extends('layouts.default')

@section('content')
    <div class="container mt-8 mx-auto">
        <div class="flex justify-end">
            <button hx-get="{{ route('bookmarks.create') }}" hx-target="#dialog">Add Bookmark</button>
        </div>
        <div class="space-y-2">
            @foreach ($bookmarks as $bookmark)
                <li class="flex items-center space-x-2">
                    <img src="{{ asset($bookmark->favicon) }}" class="w-5 h-5 rounded select-none" alt="website favicon">
                    <a href="{{ $bookmark->url }}"
                        class="text-gray-600 hover:text-orange-500 hover:underline cursor-pointer text-sm">{{ $bookmark->title }}</a>
                </li>
            @endforeach
        </div>
    </div>
@endsection
