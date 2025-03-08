<span
    class="bg-white border border-orange-500 text-orange-500 text-white text-sm rounded-full px-3 py-1 mb-2 flex items-center justify-between">
    tag: {{ $tag }}
    <input type="text" class="hidden" name="tags[]" value="{{ $tag }}">
    <button type="button" class="ml-2 rounded-full p-0.5 bg-red-500"
        onclick="this.parentNode.remove(); htmx.trigger('#bookmarks', 'loadBookmarks')">
        <x-icons.x class="w-4 h-4 text-white" />
    </button>
</span>
