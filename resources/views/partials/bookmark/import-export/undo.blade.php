<div id="modal-content">
    <x-modal.header>Confirm Import Undo</x-modal.header>
    <x-modal.body>
        <h3 class="mb-3 text-lg font-semibold text-gray-900">Are you sure you want to undo the import?</h3>
        <p class="mb-4 text-sm/6 text-gray-600">
            This action will permanently delete all bookmarks that were added during your most recent import.
            Any bookmarks you've modified or organized since the import will remain unchanged, but the imported
            bookmarks themselves cannot be recovered once removed.
        </p>
    </x-modal.body>
    <x-modal.footer>
        <x-button
            @click="$store.modal.hide()"
            type="button"
            variant="secondary"
        >Cancel</x-button>
        <x-button
            hx-delete="{{ route('bookmarks.import.undo.confirm') }}"
            type="button"
            variant="danger"
        >Confirm</x-button>
    </x-modal.footer>
</div>
