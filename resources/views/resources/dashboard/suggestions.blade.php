<ul id="suggestions"
    class="absolute overflow-y-auto max-h-[320px] border border-gray-200 z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full focus:border-red-500"
    tabindex="-1">
    @if (count($tags) >= 1)
        @foreach ($tags as $tag)
            <li tabindex="-1" role="option" hx-get="{{ route('dashboard.search.tag', $tag) }}"
                hx-trigger="click, keyup[key=='Enter']"
                class="px-3 py-2 cursor-pointer hover:bg-orange-100 focus:bg-orange-100" hx-target="#filters"
                hx-swap="afterbegin"
                hx-on::after-request="
                    document.getElementById('suggestions-container').innerHTML = '';
                    document.getElementById('search').value = '';
                    htmx.trigger('#bookmarks', 'loadBookmarks');
                ">
                {{ $tag->name }}
            </li>
        @endforeach
    @else
        <p>No tags found!</p>
    @endif
</ul>
