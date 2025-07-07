<div id="modal-content">
    <x-modal.header>Confirm Import Undo</x-modal.header>
    <x-modal.body>
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Are you sure you want to undo the import?</h3>
        <p class="text-sm/6 text-gray-600 mb-4">
            This action will permanently delete all bookmarks that were added during your most recent import.
            Any bookmarks you've modified or organized since the import will remain unchanged, but the imported
            bookmarks themselves cannot be recovered once removed.
        </p>
    </x-modal.body>
    <x-modal.footer>
        <x-button.secondary type="button" @click="$store.modal.hide()">Cancel</x-button.secondary>
        <x-button type="button" hx-delete="{{ route('bookmarks.import.undo.confirm') }}"
            class="bg-red-500">Confirm</x-button>
    </x-modal.footer>
</div>
