<span
    class="group inline-flex items-center justify-between gap-2 rounded-full border border-orange-200 bg-orange-50 px-2 py-1 text-sm font-medium text-orange-600 transition-colors duration-150 hover:bg-orange-100"
>
    <span
        class="flex items-center gap-1.5"
    >
        site: {{ $site }}
    </span>
    <input
        class="hidden"
        name="sites[]"
        type="text"
        value="{{ $site }}"
    >
    <button
        class="flex h-5 w-5 items-center justify-center rounded-full text-orange-500 transition-colors duration-150 hover:bg-orange-200 hover:text-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300"
        onclick="this.parentNode.remove(); htmx.trigger('#bookmarks-container', 'loadBookmarks')"
        type="button"
    >
        <x-icon
            class="h-3.5 w-3.5 stroke-2 text-orange-500"
            name="x"
        />
    </button>
</span>
