@extends('layouts.home')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h3>Settings</h3>

        <div>
            <h4>Tags ({{ auth()->user()->tags()->count() }})</h4>
            <p>Manage tags etc...</p>

            <div class="grid grid-cols-3 gap-2">
                @foreach (auth()->user()->tags as $tag)
                    <div class="inline-flex items-center p-2 cursor-pointer rounded hover:bg-gray-100"
                        hx-get="{{ route('tags.edit', $tag) }}" hx-target="#dialog">
                        <div style="background-color: {{ $tag->text_color }};" class="w-4 h-4 rounded-full mr-2"></div>
                        {{ $tag->name }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
