<span
    class="inline-flex items-center justify-between gap-2 px-2 py-1 text-sm font-medium text-orange-600 bg-orange-50 border border-orange-200 rounded-full group hover:bg-orange-100 transition-colors duration-150">
    <span class="flex items-center gap-1.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-orange-500" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
        {{ $tag }}
    </span>
    <input type="text" class="hidden" name="tags[]" value="{{ $tag }}">
    <button type="button"
        class="flex items-center justify-center w-5 h-5 text-orange-500 rounded-full hover:bg-orange-200 hover:text-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-colors duration-150"
        onclick="this.parentNode.remove(); htmx.trigger('#bookmarks', 'loadBookmarks')">
        <x-icons.x class="w-4 h-4 text-orange-500" />
    </button>
</span>
