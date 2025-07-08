<span
    class="inline-flex items-center justify-between gap-2 px-2 py-1 text-sm font-medium text-orange-600 bg-orange-50 border border-orange-200 rounded-full group hover:bg-orange-100 transition-colors duration-150">
    <span class="flex items-center gap-1.5">
        site: {{ $site }}
    </span>
    <input type="text" class="hidden" name="sites[]" value="{{ $site }}">
    <button type="button"
        class="flex items-center justify-center w-5 h-5 text-orange-500 rounded-full hover:bg-orange-200 hover:text-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-colors duration-150"
        onclick="this.parentNode.remove(); htmx.trigger('#bookmarks', 'loadBookmarks')">
        <x-icon name="x" class="w-3.5 h-3.5 stroke-2 text-orange-500" />
    </button>
</span>
