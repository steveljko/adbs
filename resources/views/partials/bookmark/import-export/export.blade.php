<form
    hx-on::after-request="window.modal.hide()"
    hx-post="{{ route('bookmarks.export.confirm') }}"
    id="modal-content"
>
    <x-modal.header>Export bookmarks?</x-modal.header>
    <x-modal.body>
        <x-form.input
            label="Enter password"
            name="password"
            type="password"
        />
        <span class="text-sm text-gray-500">Let password field blank if you wan't unencrypted file</span>
    </x-modal.body>
    <x-modal.footer>
        <x-button
            @click="$store.modal.hide()"
            type="button"
            variant="secondary"
        >Cancel</x-button>
        <x-button type="submit">Download</x-button>
    </x-modal.footer>
    <script></script>
</form>
