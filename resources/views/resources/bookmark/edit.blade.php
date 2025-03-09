<form hx-put="{{ route('bookmarks.update', $bookmark) }}" hx-indicator="button #spinner" id="modal-content">
    <x-modal.header>Edit Bookmark</x-modal.header>
    <x-modal.body>
        <x-form.input name="url" label="Website URL" type="text" value="{{ $bookmark->url }}" />
        <x-form.input name="title" label="Website Title" type="text" value="{{ $bookmark->title }}" />
        <div class="mb-2">
            <x-form.label name="favicon">Favicon</x-form.label>
            <img src="{{ asset($bookmark->favicon) }}" alt="favicon">
            <input type="hidden" name="favicon" value="{{ $bookmark->favicon }}">
        </div>
        <x-form.tags :tags="[]" :selectedTags="$bookmark->tags" />
    </x-modal.body>
    <x-modal.footer>
        <x-button type="submit">Edit Bookmark</x-button>
    </x-modal.footer>
</form>
