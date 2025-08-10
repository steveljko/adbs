<form
    hx-post="{{ route('bookmarks.store') }}"
    id="modal-content"
>
    <x-modal.header>Add Bookmark</x-modal.header>
    <x-modal.body>
        @fragment('form')
            <x-form.input
                :error="$errors->first('url')"
                hx-indicator="#content-spinner"
                hx-post="{{ route('bookmarks.preview') }}"
                hx-target="#content"
                hx-trigger="keyup delay:1s"
                label="URL Address"
                name="url"
                type="text"
                value=""
            />
            <div
                class="flex w-full justify-center"
                id="content"
            >
                <x-icons.spinner
                    class="htmx-indicator hidden h-6 w-6 animate-spin [&.htmx-request]:block"
                    id="content-spinner"
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
        <x-button type="submit">Save Bookmark</x-button>
    </x-modal.footer>
</form>
