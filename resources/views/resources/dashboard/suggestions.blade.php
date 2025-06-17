<div id="suggestions-container" class="absolute bottom-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mb-2 z-50">
    <div class="p-2">
        <div class="space-y-1">
            @if (count($tags))
                <div class="text-xs text-gray-500 font-medium mb-2">Tags</div>
                @foreach ($tags as $tag)
                    <div
                        class="px-3 py-2 hover:bg-gray-100 rounded cursor-pointer text-sm"
                        tabindex="-1" role="option" hx-get="{{ route('dashboard.search.tag', $tag) }}"
                        hx-trigger="click, keyup[key=='Enter']"
                        class="px-3 py-2 cursor-pointer hover:bg-orange-100 focus:bg-orange-100" hx-target="#filters"
                        hx-swap="afterbegin"
                        hx-on::after-request="
                            document.getElementById('suggestions-container').innerHTML = '';
                            document.getElementById('search').value = '';
                            htmx.trigger('#bookmarks', 'loadBookmarks');
                        "
                    >{{ $tag->name }}</div>
                @endforeach
            @endif

            @if (count($sites))
                <div class="text-xs text-gray-500 font-medium mb-2">Sites</div>
                @foreach ($sites as $site)
                    <div
                        class="px-3 py-2 hover:bg-gray-100 rounded cursor-pointer text-sm"
                        tabindex="-1" role="option" hx-get="{{ route('dashboard.search.site', $site) }}"
                        hx-trigger="click, keyup[key=='Enter']"
                        class="px-3 py-2 cursor-pointer hover:bg-orange-100 focus:bg-orange-100" hx-target="#filters"
                        hx-swap="afterbegin"
                        hx-on::after-request="
                            document.getElementById('suggestions-container').innerHTML = '';
                            document.getElementById('search').value = '';
                            htmx.trigger('#bookmarks', 'loadBookmarks');
                        "
                    >{{ $site }}</div>
                @endforeach
            @endif
        </div>
    </div>
    <!-- Arrow pointing down to search bar -->
    <div class="absolute -bottom-2 left-6 w-4 h-4 bg-white border-r border-b border-gray-200 transform rotate-45"></div>
</div>
