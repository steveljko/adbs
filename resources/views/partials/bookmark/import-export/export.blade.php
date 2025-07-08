<form hx-post="{{ route('bookmarks.export.confirm') }}" hx-on::after-request="window.modal.hide()" id="modal-content">
    <x-modal.header>Export bookmarks?</x-modal.header>
    <x-modal.body>
        <x-form.input type="password" name="password" label="Enter password" />
        <span class="text-gray-500 text-sm">Let password field blank if you wan't unencrypted file</span>
    </x-modal.body>
    <x-modal.footer>
        <x-button.secondary type="button" @click="$store.modal.hide()">Cancel</x-button.secondary>
        <x-button type="submit">Download</x-button>
    </x-modal.footer>
    <script>
    </script>
</form>
