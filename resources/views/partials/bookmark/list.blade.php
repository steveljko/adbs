@props(['bookmark'])

<li
    class="group flex items-center justify-between rounded-lg p-3 transition-colors duration-150 hover:bg-orange-50"
>
    <div
        class="flex min-w-0 flex-grow items-center"
    >
        <div
            class="flex-shrink-0"
        >
            @if ($bookmark->favicon)
                <img
                    alt="Website favicon"
                    class="h-5 w-5 select-none rounded"
                    src="{{ asset($bookmark->favicon) }}"
                >
            @else
                <div
                    class="flex h-5 w-5 items-center justify-center rounded bg-gray-200 text-xs text-gray-500"
                >
                    {{ substr($bookmark->title, 0, 1) }}
                </div>
            @endif
        </div>
        <a
            class="ml-3 max-w-sm truncate font-medium text-gray-700 hover:text-orange-600"
            href="{{ $bookmark->url }}"
            title="{{ $bookmark->title }}"
        >
            {{ $bookmark->title }}
        </a>
        @if ($bookmark->description)
            <span
                class="ml-2 hidden truncate text-xs text-gray-400 sm:inline"
            >
                {{ $bookmark->description }}
            </span>
        @endif
    </div>
    <div
        class="ml-2 flex items-center opacity-0 transition-opacity duration-200 group-hover:opacity-100"
    >
        <button
            class="rounded p-1.5 text-gray-500 transition-colors hover:bg-orange-100 hover:text-orange-500"
            hx-get="{{ route('bookmarks.edit', $bookmark->id) }}"
            hx-push-url="false"
            hx-swap="innerHTML"
            hx-target="#dialog"
            title="Edit bookmark"
            type="button"
        >
            <x-icons.edit />
        </button>
        <button
            class="ml-1 rounded p-1.5 text-gray-500 transition-colors hover:bg-red-100 hover:text-red-500"
            hx-get="{{ route('bookmarks.delete', $bookmark->id) }}"
            hx-push-url="false"
            hx-swap="innerHTML"
            hx-target="#dialog"
            title="Delete bookmark"
            type="button"
        >
            <x-icons.garbage />
        </button>
    </div>
</li>
