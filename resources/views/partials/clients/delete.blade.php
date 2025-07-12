<form hx-delete="{{ route('client.destroy', $addonClient) }}" hx-indicator="button #spinner" id="modal-content">
    <x-modal.header>Delete Client</x-modal.header>
    <x-modal.body>
        Are you sure you wan't to delete this client?
    </x-modal.body>
    <x-modal.footer>
        <x-button.secondary type="button" @click="$store.modal.hide()">Cancel</x-button.secondary>
        <x-button type="submit" color="bg-red-500">Delete Client</x-button>
    </x-modal.footer>
</form>
