<form hx-post="{{ route('bookmarks.store') }}" hx-indicator="button #spinner" id="modal-content">
    <x-modal.header>Add Bookmark</x-modal.header>
    <x-modal.body>
        @fragment('form')
            <x-form.input name="url" label="URL Address" type="text" value="" :error="$errors->first('url')"
                hx-post="{{ route('bookmarks.preview') }}" hx-trigger="keyup delay:1s" hx-target="#content"
                hx-indicator="#content-spinner" />
            <div id="content" class="w-full flex justify-center">
                <x-icons.spinner class="htmx-indicator animate-spin w-6 h-6 hidden [&.htmx-request]:block"
                    id="content-spinner" />
            </div>
        @endfragment
    </x-modal.body>
    <x-modal.footer>
        <x-button.secondary data-hide-modal="true">Cancel</x-button.secondary>
        <x-button type="submit">Save Bookmark</x-button>
    </x-modal.footer>
</form>
