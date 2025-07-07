@props(['bookmark'])

<div x-show="!loading" x-transition:enter="transition ease-out duration-400 delay-100"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0">
    <div class="flex items-center p-4 border-b border-gray-100">
        <div class="flex-shrink-0 mr-3">
            <img src="{{ asset($bookmark->favicon) }}"
                class="w-6 h-6 rounded select-none transition-transform duration-200 group-hover:scale-110"
                alt="website favicon" loading="lazy">
        </div>
        <div class="truncate flex-grow">
            <a href="{{ $bookmark->url }}"
                class="font-medium text-gray-800 hover:text-orange-500 hover:underline truncate block transition-colors duration-200">
                {{ $bookmark->title }}
            </a>
            <span class="text-xs text-gray-500 truncate block">
                {{ parse_url($bookmark->url, PHP_URL_HOST) }}
            </span>
        </div>
    </div>

    @if ($bookmark->description)
    <div class="p-4 text-sm text-gray-600 border-b border-gray-100">
        <p class="transition-colors duration-200 group-hover:text-gray-700">{{ $bookmark->description }}
        </p>
    </div>
    @endif

    @if ($bookmark->tags->isNotEmpty())
    <div class="px-4 py-2">
        <div class="flex flex-wrap gap-1">
            @foreach ($bookmark->tags as $tag)
            <a style="background-color: {{ $tag->background_color }}; color: {{ $tag->text_color }}"
                class="inline-block text-xs px-2 py-1 rounded cursor-pointer transition-all duration-200 hover:scale-105 hover:shadow-sm"
                href="dashboard?tags[]={{ $tag->name }}">{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>
    @endif

    <div
        class="p-3 bg-gray-50 flex items-center justify-between text-xs text-gray-500 transition-colors duration-200 group-hover:bg-gray-100">
        <div class="flex items-center">
            @if ($bookmark->created_at)
            <span class="inline-flex items-center transition-colors duration-200">
                <x-icons.clock />
                {{ $bookmark->created_at->diffForHumans() }}
            </span>
            @endif
        </div>
        <div class="flex items-center space-x-1">
            <button type="button" hx-get="{{ route('bookmarks.edit', $bookmark->id) }}" hx-push-url="false"
                hx-swap="innerHTML" hx-target="#dialog"
                class="p-1 rounded hover:bg-gray-200 transition-all duration-200 hover:scale-110">
                <x-icons.edit />
            </button>
            <button type="button" hx-get="{{ route('bookmarks.delete', $bookmark->id) }}" hx-push-url="false"
                hx-swap="innerHTML" hx-target="#dialog"
                class="p-1 rounded hover:bg-gray-200 transition-all duration-200 hover:scale-110">
                <x-icons.garbage />
            </button>
        </div>
    </div>
</div>
