@props(['bookmark'])

<div class="rounded border border-gray-200 hover:shadow-sm">
    <div class="flex items-center border-b border-gray-100 p-4">
        <div class="mr-3 flex-shrink-0">
            <img
                alt="website favicon"
                class="h-6 w-6 select-none rounded transition-transform duration-200 group-hover:scale-110"
                id="favicon"
                loading="lazy"
                src="{{ asset($bookmark->favicon) }}"
            >
        </div>
        <div class="flex-grow truncate">
            <a
                class="block truncate font-medium text-gray-800 transition-colors duration-200 hover:text-orange-500 hover:underline"
                href="{{ $bookmark->url }}"
            >
                {{ $bookmark->title }}
            </a>
            <span class="block truncate text-xs text-gray-500">
                {{ parse_url($bookmark->url, PHP_URL_HOST) }}
            </span>
        </div>
    </div>

    @if ($bookmark->description)
        <div class="border-b border-gray-100 p-4 text-sm text-gray-600">
            <p class="transition-colors duration-200 group-hover:text-gray-700">{{ $bookmark->description }}
            </p>
        </div>
    @endif

    @if (request()->has('tags'))
        <div class="px-4 py-2">
            <div class="flex flex-wrap gap-1">
                @foreach ($bookmark->tags as $tag)
                    <a
                        class="inline-block cursor-pointer rounded px-2 py-1 text-xs transition-all duration-200 hover:scale-105 hover:shadow-sm"
                        href="dashboard?tags[]={{ $tag->name }}"
                        style="background-color: {{ $tag->background_color }}; color: {{ $tag->text_color }}"
                    >{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
    @endif

    <div
        class="flex items-center justify-between rounded-b bg-gray-50 p-3 text-xs text-gray-500 transition-colors duration-200 group-hover:bg-gray-100">
        <div class="flex items-center">
            @if ($bookmark->created_at)
                <span class="inline-flex items-center transition-colors duration-200">
                    <x-icon
                        class="mr-1 h-3.5 w-3.5"
                        name="clock"
                    />
                    {{ $bookmark->created_at->diffForHumans() }}
                </span>
            @endif
        </div>
        <div class="flex items-center space-x-1">
            <button
                class="rounded-md p-1 text-gray-500 transition-all duration-200 hover:bg-blue-100 hover:text-blue-600"
                hx-get="{{ route('bookmarks.edit', $bookmark->id) }}"
                hx-push-url="false"
                hx-swap="innerHTML"
                hx-target="#dialog"
                title="Edit bookmark"
                type="button"
            >
                <x-icon
                    class="h-4 w-4"
                    name="edit"
                />
            </button>
            <button
                class="rounded-md p-1 text-gray-500 transition-all duration-200 hover:bg-red-100 hover:text-red-600"
                hx-get="{{ route('bookmarks.delete', $bookmark->id) }}"
                hx-push-url="false"
                hx-swap="innerHTML"
                hx-target="#dialog"
                title="Delete bookmark"
                type="button"
            >
                <x-icon
                    class="h-4 w-4"
                    name="garbage"
                />
            </button>
        </div>
    </div>
</div>
