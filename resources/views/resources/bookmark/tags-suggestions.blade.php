<ul id="suggestions"
    class="absolute z-20 w-full max-h-64 overflow-y-auto bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
    tabindex="-1">
    <li role="option"
        class="flex items-center px-4 py-3 text-sm text-gray-700 cursor-pointer transition duration-150 ease-in-out hover:bg-orange-50 focus:bg-orange-50 focus:outline-none"
        hx-get="{{ route('tags.get', $name) }}" hx-trigger="click, keyup[key=='Enter']" hx-target="#tags"
        hx-swap="afterbegin"
        hx-on::after-request="
            document.getElementById('search').value = '';
            document.getElementById('suggestions-container').innerHTML = '';
        ">
        <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create tag '<span class="font-medium">{{ $name }}</span>'
        </span>
    </li>
    @if (count($tags) >= 1)
        @foreach ($tags as $tag)
            <li tabindex="-1" role="option" hx-get="{{ route('tags.get', $tag->name) }}"
                hx-trigger="click, keyup[key=='Enter']"
                class="flex items-center px-4 py-3 text-sm text-gray-700 cursor-pointer transition duration-150 ease-in-out hover:bg-orange-50 focus:bg-orange-50 focus:outline-none"
                hx-target="#tags" hx-swap="afterbegin"
                hx-on::after-request="
                    document.getElementById('search').value = '';
                    document.getElementById('suggestions-container').innerHTML = '';
                ">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    {{ $tag->name }}
                </span>
            </li>
        @endforeach
    @endif
</ul>
