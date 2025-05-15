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
        <!-- TODO: Code card based presentation of bookmarks -->
        <div class="grid grid-cols-3 gap-4">
            @foreach ($bookmarks as $bookmark)
                <div class="p-4 rounded border border-gray-200">
                    <a href="{{ $bookmark->url }}"
                        class="text-gray-600 hover:text-orange-500 hover:underline cursor-pointer text-sm">{{ $bookmark->title }}</a>
                </div>
            @endforeach
        </div>
    @endif
@endfragment
