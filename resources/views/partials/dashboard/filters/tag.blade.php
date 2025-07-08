<span
    class="inline-flex items-center justify-between gap-2 px-2 py-1 text-sm font-medium border rounded-full group hover:bg-orange-100 transition-colors duration-150"
    style="background-color: {{ $tag->background_color }}; color: {{ $tag->text_color }}">
    <span class="flex items-center gap-1.5">
        <x-icon name="tag" class="w-3.5 h-3.5" />
        {{ $tag->name }}
    </span>
    <input type="text" class="hidden" name="tags[]" value="{{ $tag->name }}">
    <button type="button"
        class="flex items-center justify-center w-5 h-5 text-orange-500 rounded-full focus:outline-none focus:ring-2 transition-colors duration-150"
        onclick="this.parentNode.remove(); htmx.trigger('#bookmarks', 'loadBookmarks')">
        <x-icon name="x" class="w-3.5 h-3.5 stroke-2" style="color: {{ $tag->text_color }}" />
    </button>
</span>
