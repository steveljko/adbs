@props(['type' => 'card', 'bookmarks'])

<div id="bookmarks" hx-get="{{ route('dashboard') }}" hx-include="#filters"
    hx-vals='{"view_type": "{{ request('view_type', 'card') }}"}' hx-trigger="loadBookmarks from:body" hx-swap="outerHTML"
    hx-push-url="true">
    @if ($type == 'list')
        @foreach ($bookmarks as $bookmark)
            <li
                class="flex items-center justify-between p-3 group hover:bg-orange-50 transition-colors duration-150 rounded-lg">
                <div class="flex items-center flex-grow min-w-0">
                    <div class="flex-shrink-0">
                        @if ($bookmark->favicon)
                            <img src="{{ asset($bookmark->favicon) }}" class="w-5 h-5 rounded select-none"
                                alt="Website favicon">
                        @else
                            <div
                                class="w-5 h-5 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">
                                {{ substr($bookmark->title, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <a href="{{ $bookmark->url }}"
                        class="ml-3 text-gray-700 hover:text-orange-600 font-medium truncate max-w-sm"
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
        <div class="columns-1 md:columns-2 lg:columns-3 gap-4 space-y-4">
    @foreach ($bookmarks as $bookmark)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-100 break-inside-avoid mb-4">
            <div class="flex items-center p-4 border-b border-gray-100">
                <div class="flex-shrink-0 mr-3">
                    <img src="{{ asset($bookmark->favicon) }}" class="w-6 h-6 rounded select-none"
                        alt="website favicon">
                </div>
                <div class="truncate flex-grow">
                    <a href="{{ $bookmark->url }}"
                        class="font-medium text-gray-800 hover:text-orange-500 hover:underline truncate block">
                        {{ $bookmark->title }}
                    </a>
                    <span class="text-xs text-gray-500 truncate block">{{ parse_url($bookmark->url, PHP_URL_HOST) }}</span>
                </div>
            </div>
            @if ($bookmark->description)
                <div class="p-4 text-sm text-gray-600 border-b border-gray-100">
                    <p>{{ $bookmark->description }}</p>
                </div>
            @endif
            @if ($bookmark->tags->isNotEmpty())
                <div class="px-4 py-2">
                    <div class="flex flex-wrap gap-1">
                        @foreach ($bookmark->tags as $tag)
                            <a
                                style="background-color: {{ $tag->background_color }}; color: {{ $tag->text_color }}" class="inline-block text-blue-800 text-xs px-2 py-1 rounded cursor-pointer"
                                href="dashboard?tags[]={{ $tag->name }}"
                            >{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="p-3 bg-gray-50 flex items-center justify-between text-xs text-gray-500">
                <div class="flex items-center">
                    @if ($bookmark->created_at)
                        <span class="inline-flex items-center">
                            <x-icons.clock />
                            {{ $bookmark->created_at->diffForHumans() }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center space-x-1">
                    <button type="button" hx-get="{{ route('bookmarks.edit', $bookmark->id) }}"
                        hx-push-url="false" hx-swap="innerHTML" hx-target="#dialog"
                        class="p-1 rounded hover:bg-gray-200">
                        <x-icons.edit />
                    </button>
                    <button type="button" hx-get="{{ route('bookmarks.delete', $bookmark->id) }}"
                        hx-push-url="false" hx-swap="innerHTML" hx-target="#dialog"
                        class="p-1 rounded hover:bg-gray-200">
                        <x-icons.garbage />
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
    @endif
</div>
