@props(['type' => 'card', 'bookmarks', 'hasMore' => false, 'nextPage' => null, 'currentPage' => 1])

@if ($type == 'list')
@foreach ($bookmarks as $bookmark)
<li class="flex items-center justify-between p-3 group hover:bg-orange-50 transition-colors duration-150 rounded-lg">
    <div class="flex items-center flex-grow min-w-0">
        <div class="flex-shrink-0">
            @if ($bookmark->favicon)
            <img src="{{ asset($bookmark->favicon) }}" class="w-5 h-5 rounded select-none" alt="Website favicon">
            @else
            <div class="w-5 h-5 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">
                {{ substr($bookmark->title, 0, 1) }}
            </div>
            @endif
        </div>

        <a href="{{ $bookmark->url }}" class="ml-3 text-gray-700 hover:text-orange-600 font-medium truncate max-w-sm"
            title="{{ $bookmark->title }}">
            {{ $bookmark->title }}
        </a>

        @if ($bookmark->description)
        <span class="ml-2 text-xs text-gray-400 truncate hidden sm:inline">
            {{ $bookmark->description }}
        </span>
        @endif
    </div>

    <div class="flex items-center ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        <button type="button" hx-get="{{ route('bookmarks.edit', $bookmark->id) }}" hx-push-url="false"
            hx-target="#dialog" hx-swap="innerHTML"
            class="p-1.5 text-gray-500 hover:text-orange-500 hover:bg-orange-100 rounded transition-colors"
            title="Edit bookmark">
            <x-icons.edit />
        </button>

        <button type="button" hx-get="{{ route('bookmarks.delete', $bookmark->id) }}" hx-push-url="false"
            hx-target="#dialog" hx-swap="innerHTML"
            class="p-1.5 text-gray-500 hover:text-red-500 hover:bg-red-100 rounded transition-colors ml-1"
            title="Delete bookmark">
            <x-icons.garbage />
        </button>
    </div>
</li>
@endforeach
@else
@foreach ($bookmarks as $bookmark)
<div
    class="bookmark-card relative bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 break-inside-avoid mb-4 group">
    @include('partials.bookmark.card-loading', ['bookmark' => $bookmark])
    @include('partials.bookmark.card', ['bookmark' => $bookmark])
</div>
@endforeach
@endif
