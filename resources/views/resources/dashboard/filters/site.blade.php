<span
    class="bg-white border border-orange-500 text-orange-500 text-sm rounded-full px-3 py-1 mb-2 flex items-center justify-between">
    site: {{ $site }}
    <input type="text" class="hidden" name="sites[]" value="{{ $site }}">
    <button type="button" class="ml-2 rounded-full p-0.5 bg-red-500"
        onclick="this.parentNode.remove(); htmx.trigger('#bookmarks', 'loadBookmarks')">
        <x-icons.x class="w-4 h-4 text-white" />
    </button>
</span>
