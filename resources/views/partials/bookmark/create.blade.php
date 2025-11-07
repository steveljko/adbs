<form
    hx-post="{{ route('bookmarks.store') }}"
    id="modal-content"
>
    <x-modal.header>Add Bookmark</x-modal.header>
    <x-modal.body>
        @fragment('form')
            <div class="mb-3 flex items-end gap-4">
                <x-form.input
                    :error="$errors->first('url')"
                    hx-indicator="#content-spinner"
                    hx-post="{{ route('bookmarks.preview') }}"
                    hx-target="#content"
                    hx-trigger="keyup delay:1s"
                    label="URL Address"
                    name="url"
                    nomb
                    type="text"
                    value=""
                />
                <x-button
                    id="paste"
                    type="button"
                >Paste</x-button>
            </div>
            <div
                class="flex w-full justify-center"
                id="content"
            >
                <x-icon
                    class="htmx-indicator hidden h-6 w-6 animate-spin [&.htmx-request]:block"
                    id="content-spinner"
                    name="spinner"
                    viewBox="0 0 100 101"
                />
            </div>
        @endfragment
    </x-modal.body>
    <x-modal.footer>
        <x-button
            @click="$store.modal.hide()"
            type="button"
            variant="secondary"
        >Cancel</x-button>
        <x-button
            disabled
            id="submit"
            type="submit"
        >Save Bookmark</x-button>
    </x-modal.footer>
    <script>
        document.getElementById('paste').addEventListener('click', async () => {
            const pasteButton = document.getElementById('paste');
            const urlInput = document.querySelector('input[name="url"]');

            try {
                const permission = await navigator.permissions.query({
                    name: 'clipboard-read'
                });

                if (permission.state === 'denied') {
                    pasteButton.disabled = true;
                    return;
                }

                const clipboard = await navigator.clipboard.readText();
                urlInput.value = clipboard;
                htmx.trigger(urlInput, 'keyup');
            } catch (err) {
                pasteButton.disabled = true;
            }
        });
    </script>
</form>
