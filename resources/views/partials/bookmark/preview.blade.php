<div class="w-full">
    <div class="flex gap-4">
        <div>
            <x-form.label name="favicon">Favicon</x-form.label>
            <img
                alt="favicon"
                src="{{ asset($favicon) }}"
            >
            <input
                class="hidden"
                name="favicon"
                type="text"
                value="{{ $favicon }}"
            >
        </div>
        <x-form.input
            :value="$title"
            label="Website Title"
            name="title"
            type="text"
        />
    </div>
    <div>
        <x-form.tags :tags="$tags" />
    </div>
</div>
<x-button
    hx-swap-oob="true"
    id="submit"
    type="submit"
>Save Bookmark</x-button>
