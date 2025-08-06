<form
    hx-put="{{ route('tags.update', $tag) }}"
    id="modal-content"
>
    <x-modal.header>Edit Bookmark</x-modal.header>
    <x-modal.body>
        <x-form.input
            :value="$tag->name"
            label="Tag name"
            name="name"
            type="text"
        />
        <x-form.input
            :value="$tag->description"
            label="Tag description"
            name="description"
            type="text"
        />
        <div>
            <label
                class="mb-3 block text-sm font-medium text-gray-500"
                for="color"
            >Tag Color</label>
            <div class="flex items-center">
                <input
                    id="color"
                    name="text_color"
                    type="text"
                    value="{{ $tag->text_color }}"
                    x-init="Coloris({
                        el: $el,
                        theme: 'pill',
                    });"
                >
            </div>
        </div>
    </x-modal.body>
    <x-modal.footer class="flex justify-between border-t border-gray-200 p-4">
        <x-button
            class="bg-red-500"
            hx-delete="{{ route('tags.delete', $tag) }}"
            type="button"
        >Delete
            Bookmark</x-button>
        <div class="flex space-x-2">
            <x-button.secondary
                @click="$store.modal.hide()"
                type="button"
            >Cancel</x-button.secondary>
            <x-button type="submit">Edit Bookmark</x-button>
        </div>
    </x-modal.footer>
</form>
