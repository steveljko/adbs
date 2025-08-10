@props(['tempFile' => null])

<form
    hx-post="{{ route('bookmarks.import.confirm') }}"
    id="modal-content"
>
    <x-modal.header>Confirm Import</x-modal.header>
    <x-modal.body>
        <input
            name="temp_file"
            type="hidden"
            value="{{ basename($tempFile) }}"
        />
        <p>Are you sure you wan't to import?</p>
    </x-modal.body>
    <x-modal.footer>
        <x-button
            @click="$store.modal.hide()"
            type="button"
            variant="secondary"
        >Cancel</x-button>
        <x-button
            type="submit"
            variant="blue"
        >Confirm Import</x-button>
    </x-modal.footer>
</form>
