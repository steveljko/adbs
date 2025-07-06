<span
    class="inline-flex items-center justify-between gap-2 px-2 py-1 text-sm font-medium border rounded-full group hover:bg-orange-100 transition-colors duration-150"
    style="background-color: {{ $tag->background_color }}; color: {{ $tag->text_color }}">
    <span class="flex items-center gap-1.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" style="color: {{ $tag->text_color }}" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
        {{ $tag->name }}
    </span>
    <input type="text" class="hidden" name="tags[]" value="{{ $tag->name }}">
    <button type="button"
        class="flex items-center justify-center w-5 h-5 text-orange-500 rounded-full focus:outline-none focus:ring-2 transition-colors duration-150"
        onclick="this.parentNode.remove(); htmx.trigger('#bookmarks', 'loadBookmarks')">
        <x-icons.x class="w-4 h-4" style="color: {{ $tag->text_color }}" />
    </button>
</span>
