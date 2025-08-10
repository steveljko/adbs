<form
    hx-put="{{ route('bookmarks.update', $bookmark) }}"
    id="modal-content"
>
    <x-modal.header>Edit Bookmark</x-modal.header>
    <x-modal.body>
        <div class="flex gap-6">
            <div class="mb-2">
                <x-form.label name="favicon">Favicon</x-form.label>
                <img
                    alt="website favicon"
                    class="h-10 w-10 select-none rounded transition-transform duration-200 group-hover:scale-110"
                    id="favicon"
                    loading="lazy"
                    src="{{ asset($bookmark->favicon) }}"
                >
                <input
                    name="favicon"
                    type="hidden"
                    value="{{ $bookmark->favicon }}"
                >
            </div>
            <x-form.input
                label="Website Title"
                name="title"
                type="text"
                value="{{ $bookmark->title }}"
            />
        </div>
        <x-form.tags :selectedTags="$bookmark->tags" />
    </x-modal.body>
    <x-modal.footer>
        <x-button
            @click="$store.modal.hide()"
            type="button"
            variant="secondary"
        >Cancel</x-button>
        <x-button type="submit">Edit Bookmark</x-button>
    </x-modal.footer>
</form>
