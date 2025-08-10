<form
    hx-delete="{{ route('bookmarks.destroy', $bookmark) }}"
    id="modal-content"
>
    <x-modal.header>Delete Bookmark</x-modal.header>
    <x-modal.body>
        Delete {{ $bookmark->title }}?
    </x-modal.body>
    <x-modal.footer>
        <x-button
            @click="$store.modal.hide()"
            type="button"
            variant="secondary"
        >Cancel</x-button>
        <x-button
            type="submit"
            variant="danger"
        >Delete Bookmark</x-button>
    </x-modal.footer>
</form>
