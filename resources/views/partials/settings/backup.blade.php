<x-card>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <h3 class="font-semibold text-gray-800">Backup & Restore</h3>
        </div>
    </x-slot>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-[repeat(auto-fit,minmax(280px,1fr))]">
        <div
            class="group relative overflow-hidden rounded-xl border border-orange-200 bg-gradient-to-br from-orange-50 to-orange-100/50 p-5 transition-all hover:shadow-md">
            <div
                class="absolute right-0 top-0 h-32 w-32 -translate-y-8 translate-x-8 rounded-full bg-orange-200/30 blur-2xl">
            </div>

            <div class="relative flex h-full flex-col justify-between">
                <div>
                    <div class="mb-4 inline-flex rounded-lg bg-orange-500/10 p-3">
                        <x-icon
                            class="h-6 w-6 stroke-current text-orange-600"
                            name="cloud-export"
                        />
                    </div>

                    <div>
                        <h5 class="mb-2 text-lg font-semibold text-orange-900">Export Bookmarks</h5>
                        <p class="mb-4 text-sm leading-relaxed text-orange-700">
                            Download all your bookmarks as a JSON file for safekeeping or transfer
                        </p>
                    </div>
                </div>

                <x-button
                    class="w-full"
                    hx-get="{{ route('bookmarks.export') }}"
                    hx-target="#dialog"
                    size="sm"
                    variant="primary"
                >
                    <span class="flex items-center justify-center gap-2">
                        <x-icon
                            class="h-4 w-4 stroke-current"
                            name="download"
                        />
                        Export Now
                    </span>
                </x-button>
            </div>
        </div>

        <div
            class="group relative overflow-hidden rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100/50 p-5 hover:shadow-md">
            <div
                class="absolute right-0 top-0 h-32 w-32 -translate-y-8 translate-x-8 rounded-full bg-blue-200/30 blur-2xl">
            </div>

            <div class="relative flex h-full flex-col justify-between">
                <div>
                    <div class="mb-4 inline-flex rounded-lg bg-blue-500/10 p-3">
                        <x-icon
                            class="h-6 w-6 text-blue-600"
                            name="cloud-import"
                        />
                    </div>

                    <h5 class="mb-2 text-lg font-semibold text-blue-900">Import Bookmarks</h5>
                    <p class="mb-4 text-sm leading-relaxed text-blue-700">
                        Upload a JSON file to restore or add bookmarks to your collection
                    </p>
                </div>

                <div id="progressContainer">
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
                                class="w-full"
                                id="importBtn"
                                size="sm"
                                type="button"
                                variant="blue"
                            >
                                <span class="flex items-center justify-center gap-2">
                                    <x-icon
                                        class="h-4 w-4"
                                        name="document"
                                    />
                                    Choose File
                                </span>
                            </x-button>
                        </form>
                    @endfragment
                </div>

            </div>
        </div>

        @if (auth()->user()->hasUndoableImport())
            <div
                class="group relative overflow-hidden rounded-xl border border-red-200 bg-gradient-to-br from-red-50 to-red-100/50 p-5 hover:shadow-sm">
                <div
                    class="absolute right-0 top-0 h-32 w-32 -translate-y-8 translate-x-8 rounded-full bg-red-200/30 blur-2xl">
                </div>

                <div class="relative flex h-full flex-col justify-between">
                    <div>
                        <div class="mb-4 inline-flex rounded-lg bg-red-500/10 p-3">
                            <x-icon
                                class="h-6 w-6 text-red-600"
                                name="play-pause"
                            />
                        </div>

                        <div>
                            <h5 class="mb-2 text-lg font-semibold text-red-900">Undo Latest Import</h5>
                            <p class="mb-4 text-sm leading-relaxed text-red-700">
                                This will remove all bookmarks added during your last import session.
                            </p>
                        </div>
                    </div>

                    <x-button
                        class="w-full"
                        hx-get="{{ route('bookmarks.import.undo') }}"
                        hx-target="#dialog"
                        size="sm"
                        variant="danger"
                    >
                        <span class="flex items-center justify-center gap-2">
                            <x-icon
                                class="h-4 w-4"
                                name="undo"
                            />
                            Undo Import
                        </span>
                    </x-button>
                </div>
            </div>
        @endif
    </div>
</x-card>
