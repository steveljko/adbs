@props(['tempFile' => null])

<form
    hx-post="{{ route('bookmarks.import.decrypt') }}"
    id="modal-content"
>
    <x-modal.header>Enter password to decrypt bookmarks</x-modal.header>
    <x-modal.body>
        <x-form.input
            label="Password"
            name="password"
            type="password"
        />
        <input
            name="temp_file"
            type="hidden"
            value="{{ basename($tempFile) }}"
        />
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
        >Decrypt and Import</x-button>
    </x-modal.footer>
</form>
