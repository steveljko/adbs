@props(['tempFile' => null])

<form hx-post="{{ route('bookmarks.import.confirm') }}" id="modal-content">
    <x-modal.header>Confirm Import</x-modal.header>
    <x-modal.body>
        <input type="hidden" name="temp_file" value="{{ basename($tempFile) }}" />
        <p>Are you sure you wan't to import?</p>
    </x-modal.body>
    <x-modal.footer>
        <x-button.secondary type="button" @click="$store.modal.hide()">Cancel</x-button.secondary>
        <x-button variant="blue" type="submit">Confirm Import</x-button>
    </x-modal.footer>
</form>
