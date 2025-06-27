<form hx-delete="{{ route('bookmarks.destroy', $bookmark) }}" hx-indicator="button #spinner" id="modal-content">
    <x-modal.header>Delete Bookmark</x-modal.header>
    <x-modal.body>
        Asd {{ $bookmark->title }}
    </x-modal.body>
    <x-modal.footer>
        <x-button.secondary data-hide-modal="true">Cancel</x-button.secondary>
        <x-button type="submit" color="bg-red-500">Delete Bookmark</x-button>
    </x-modal.footer>
</form>
