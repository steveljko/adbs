<form
    hx-delete="{{ route('client.destroy', $personalAccessToken) }}"
    hx-indicator="button #spinner"
    id="modal-content"
>
    <x-modal.header>Delete Client</x-modal.header>
    <x-modal.body>
        Are you sure you wan't to delete this client?
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
        >Delete Client</x-button>
    </x-modal.footer>
</form>
