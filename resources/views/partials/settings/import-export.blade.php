<x-card>
    <x-slot name="header">
        <h3 class="font-semibold text-gray-800">Import/Export Bookmarks</h3>
    </x-slot>

    <div class="space-y-6">
        <!-- Export Section -->
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="font-medium text-orange-800 mb-2">Export Bookmarks</h5>
                    <p class="text-sm text-orange-700">Download your bookmarks as a JSON file</p>
                </div>
                <x-button class="bg-orange-500 hover:bg-orange-600 text-white" hx-get="{{ route('bookmarks.export') }}"
                    hx-target="#dialog">
                    Export
                </x-button>
            </div>
        </div>

        <!-- Import Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h5 class="font-medium text-blue-800 mb-1">Import Bookmarks</h5>
                    <p class="text-sm text-blue-700">Upload a JSON file to restore bookmarks</p>
                </div>

                @fragment('form')
                <form hx-post="{{ route('bookmarks.import') }}" hx-encoding="multipart/form-data" hx-swap="none"
                    class="space-y-4" x-data="{
                      selectedFile: null,
                      handleFileSelect(event) {
                          event.preventDefault();
                          this.selectedFile = event.target.files[0];
                          if (this.selectedFile) {
                            htmx.trigger(this.$refs.form, 'submit');
                          }
                      }
                  }" x-ref="form">
                    <div class="flex items-center gap-3">
                        <input type="file" name="file" accept=".json" class="sr-only" id="file-input" x-ref="fileInput"
                            @change="handleFileSelect($event)">

                        <x-button type="button" variant="blue" @click="$refs.fileInput.click()">
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
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h5 class="font-medium text-red-800 mb-2">Undo Latest Import</h5>
                    <p class="w-10/12 leading-[1.5rem] text-sm text-red-700">
                        Your most recent bookmark import can be reversed if you imported incorrect data or duplicates.
                        This will remove all bookmarks that were added during your last import session.
                    </p>
                </div>
                <x-button class="bg-red-500 hover:bg-red-600 text-white ml-4 flex-shrink-0"
                    hx-get="{{ route('bookmarks.import.undo') }}" hx-target="#dialog">
                    Undo
                </x-button>
            </div>
        </div>
        @endif
    </div>
</x-card>
