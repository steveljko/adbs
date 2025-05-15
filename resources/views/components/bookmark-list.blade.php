@props(['type' => 'list', 'bookmarks'])

@fragment('bookmark-list')
    @if ($type == 'list')
        @foreach ($bookmarks as $bookmark)
            <li class="flex items-center space-x-2">
                <img src="{{ asset($bookmark->favicon) }}" class="w-5 h-5 rounded select-none" alt="website favicon">
                <a href="{{ $bookmark->url }}"
                    class="text-gray-600 hover:text-orange-500 hover:underline cursor-pointer text-sm">{{ $bookmark->title }}</a>
                <button type="button" hx-get="{{ route('bookmarks.edit', $bookmark->id) }}" hx-push-url="false"
                    hx-target="#dialog">Edit</button>
                <button type="button" hx-get="{{ route('bookmarks.delete', $bookmark->id) }}" hx-push-url="false"
                    hx-target="#dialog">Delete</button>
            </li>
        @endforeach
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($bookmarks as $bookmark)
                <div
                    class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-100">
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
                            <span
                                class="text-xs text-gray-500 truncate block">{{ parse_url($bookmark->url, PHP_URL_HOST) }}</span>
                        </div>
                    </div>

                    @if ($bookmark->description)
                        <div class="p-4 text-sm text-gray-600 border-b border-gray-100">
                            <p class="line-clamp-2">{{ $bookmark->description }}</p>
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

                            @if ($bookmark->tags->isNotEmpty())
                                <span class="inline-flex items-center ml-3">
                                    <x-icons.tag />
                                    {{ $bookmark->tags->pluck('name')->implode(', ') }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-1">
                            <button type="button" hx-get="{{ route('bookmarks.edit', $bookmark->id) }}"
                                hx-push-url="false" hx-target="#dialog" class="p-1 rounded hover:bg-gray-200">
                                <x-icons.edit />
                            </button>
                            <button type="button" hx-get="{{ route('bookmarks.delete', $bookmark->id) }}"
                                hx-push-url="false" hx-target="#dialog" class="p-1 rounded hover:bg-gray-200">
                                <x-icons.garbage />
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endfragment
