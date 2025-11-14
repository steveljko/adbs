<span
    class="group inline-flex items-center justify-between gap-2 rounded-full border px-2 py-1 text-sm font-medium transition-colors duration-150"
    style="background-color: {{ $tag->background_color }}; color: {{ $tag->text_color }}; border-color: {{ $tag->text_color }};"
>
    <span class="flex items-center gap-1.5">
        <x-icon
            class="h-3.5 w-3.5"
            name="tag"
        />
        {{ $tag->name }}
    </span>
    <input
        class="hidden"
        name="tags[]"
        type="text"
        value="{{ $tag->name }}"
    >
    <button
        class="flex h-5 w-5 items-center justify-center rounded-full text-orange-500 transition-colors duration-150 focus:outline-none focus:ring-2"
        onclick="this.parentNode.remove();"
        type="button"
    >
        <x-icon
            class="h-3.5 w-3.5 stroke-2"
            name="x"
            style="color: {{ $tag->text_color }}"
        />
    </button>
</span>
