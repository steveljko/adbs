<form hx-put="{{ route('tags.update', $tag) }}" hx-indicator="button #spinner" id="modal-content">
    <x-modal.header>Edit Bookmark</x-modal.header>
    <x-modal.body>
        <x-form.input name="name" label="Tag name" type="text" :value="$tag->name" />
        <x-form.input name="description" label="Tag description" type="text" :value="$tag->description" />
        <div class="flex items-center space-x-2">
            <label for="color" class="mb-3 block text-sm font-medium text-gray-500">Tag Color</label>
            <div id="color-res" style="background-color: {{ $tag->text_color }};" class="w-4 h-4 rounded-full mr-2">
            </div>
            <input type="text" name="text_color" value="{{ $tag->text_color }}" id="color">
        </div>
    </x-modal.body>
    <x-modal.footer class="flex justify-between border-t border-gray-200 p-4">
        <x-button class="bg-red-500" hx-delete="{{ route('tags.delete', $tag) }}" type="button">Delete Bookmark</x-button>
        <div class="flex space-x-2">
            <x-button.secondary data-hide-modal="true">Cancel</x-button.secondary>
            <x-button type="submit">Edit Bookmark</x-button>
        </div>
    </x-modal.footer>
</form>
