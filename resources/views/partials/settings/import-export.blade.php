<x-card>
    <x-slot name="header">
        <h3 class="font-semibold text-gray-800">Import/Export Bookmarks</h3>
    </x-slot>

    <div class="space-y-6">
        <!-- Export Section -->
        <div class="rounded-lg border border-orange-200 bg-orange-50 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="mb-2 font-medium text-orange-800">Export Bookmarks</h5>
                    <p class="text-sm text-orange-700">Download your bookmarks as a JSON file</p>
                </div>
                <x-button
                    class="bg-orange-500 text-white hover:bg-orange-600"
                    hx-get="{{ route('bookmarks.export') }}"
                    hx-target="#dialog"
                    size="sm"
                >
                    Export
                </x-button>
            </div>
        </div>

        <!-- Import Section -->
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h5 class="mb-1 font-medium text-blue-800">Import Bookmarks</h5>
                    <p class="text-sm text-blue-700">Upload a JSON file to restore bookmarks</p>
                </div>

                @fragment('form')
                    <form
                        class="space-y-4"
                        hx-encoding="multipart/form-data"
                        hx-post="{{ route('bookmarks.import') }}"
                        hx-swap="none"
                        x-data="{
                            selectedFile: null,
                            handleFileSelect(event) {
                                event.preventDefault();
                                this.selectedFile = event.target.files[0];
                                if (this.selectedFile) {
                                    htmx.trigger(this.$refs.form, 'submit');
                                }
                            }
                        }"
                        x-ref="form"
                    >
                        <div class="flex items-center gap-3">
                            <input
                                @change="handleFileSelect($event)"
                                accept=".json"
                                class="sr-only"
                                id="file-input"
                                name="file"
                                type="file"
                                x-ref="fileInput"
                            >

                            <x-button
                                @click="$refs.fileInput.click()"
                                size="sm"
                                type="button"
                                variant="blue"
                            >
                                Choose File
                            </x-button>
                        </div>
                    </form>
                @endfragment

            </div>

            @include('partials.bookmark.import-export.import-progress')
        </div>

        <!-- Undo Import Section -->
        @if (auth()->user()->hasUndoableImport())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h5 class="mb-2 font-medium text-red-800">Undo Latest Import</h5>
                        <p class="w-10/12 text-sm leading-[1.5rem] text-red-700">
                            Your most recent bookmark import can be reversed if you imported incorrect data or
                            duplicates.
                            This will remove all bookmarks that were added during your last import session.
                        </p>
                    </div>
                    <x-button
                        class="ml-4 flex-shrink-0 bg-red-500 text-white hover:bg-red-600"
                        hx-get="{{ route('bookmarks.import.undo') }}"
                        hx-target="#dialog"
                    >
                        Undo
                    </x-button>
                </div>
            </div>
        @endif
    </div>
</x-card>
