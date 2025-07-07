@props(['tempFile' => null])

<form hx-post="{{ route('bookmarks.decryptAndImport') }}" id="modal-content">
    <x-modal.header>Enter password to decrypt bookmarks</x-modal.header>
    <x-modal.body>
        <x-form.input name="password" label="Password" type="password" />
        <input type="hidden" name="temp_file" value="{{ basename($tempFile) }}" />
    </x-modal.body>
    <x-modal.footer>
        <x-button.secondary type="button" @click="$store.modal.hide()">Cancel</x-button.secondary>
        <x-button type="submit">Decrypt and Import</x-button>
    </x-modal.footer>
</form>
