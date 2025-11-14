@if ($name != '')
    <ul
        class="absolute z-20 mt-2 max-h-64 w-full divide-y divide-gray-100 overflow-y-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        id="suggestions"
        tabindex="-1"
    >
        <li
            class="flex cursor-pointer items-center px-4 py-3 text-sm text-gray-700 transition duration-150 ease-in-out hover:bg-orange-50 focus:bg-orange-50 focus:outline-none"
            hx-get="{{ route('tags.get', $name) }}"
            hx-on::after-request="
            document.getElementById('search').value = '';
            document.getElementById('name').value = '';
            document.getElementById('suggestions-container').innerHTML = '';
        "
            hx-swap="afterbegin"
            hx-target="#tags"
            hx-trigger="click, keyup[key=='Enter']"
            role="option"
        >
            <span class="flex items-center">
                <svg
                    class="mr-2 h-4 w-4 text-orange-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                    />
                </svg>
                Create tag '<span class="font-medium">{{ $name }}</span>'
            </span>
        </li>
        @if (count($tags) >= 1)
            @foreach ($tags as $tag)
                <li
                    class="flex cursor-pointer items-center px-4 py-3 text-sm text-gray-700 transition duration-150 ease-in-out hover:bg-orange-50 focus:bg-orange-50 focus:outline-none"
                    hx-get="{{ route('tags.get', $tag->name) }}"
                    hx-on::after-request="
                    document.getElementById('search').value = '';
                    document.getElementById('name').value = '';
                    document.getElementById('suggestions-container').innerHTML = '';
                "
                    hx-swap="afterbegin"
                    hx-target="#tags"
                    hx-trigger="click, keyup[key=='Enter']"
                    role="option"
                    tabindex="-1"
                >
                    <span class="flex items-center">
                        <svg
                            class="mr-2 h-4 w-4 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                        </svg>
                        {{ $tag->name }}
                    </span>
                </li>
            @endforeach
        @endif
    </ul>
@endif
