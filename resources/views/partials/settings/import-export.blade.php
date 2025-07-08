<div id="export" class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
    <div class="flex justify-between p-4 bg-gray-100">
        <h4>Export/Import Bookmarks</h4>
    </div>

    <div class="p-6 space-y-6">
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <h5 class="font-medium text-orange-800 mb-3 flex items-center">
                Export Bookmarks
            </h5>
            <a class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-md transition-colors duration-200 shadow-sm"
                hx-get="{{ route('bookmarks.export') }}" hx-target="#dialog">
                Export Bookmarks
            </a>
        </div>

        @if (auth()->user()->hasUndoableImport())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <h5 class="font-medium text-orange-800 mb-3 flex items-center">Undo latest import</h5>
            <p class="text-sm text-gray-600 text-red-700 mb-3">
                Your most recent bookmark import can be reversed if you imported incorrect data or duplicates.
                This will remove all bookmarks that were added during your last import session.
            </p>
            <x-button class="bg-red-500" hx-get="{{ route('bookmarks.import.undo') }}"
                hx-target="#dialog">Undo</x-button>
        </div>
        @endif

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h5 class="font-medium text-blue-800 mb-3 flex items-center">
                Import Bookmarks
            </h5>
            <p class="text-sm text-blue-700 mb-4">Upload JSON file to restore bookmarks.</p>

            @fragment('form')
            <form hx-post="{{ route('bookmarks.import') }}" hx-encoding="multipart/form-data" hx-swap="none"
                class="space-y-4">
                <div class="flex items-center space-x-3">
                    <label class="relative cursor-pointer">
                        <input type="file" name="file" accept=".json" class="sr-only" id="file-input">
                        <div
                            class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors duration-200">
                            <span class="text-sm text-gray-700 font-medium">Choose JSON File</span>
                        </div>
                    </label>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200 shadow-sm">
                        Import
                    </button>
                </div>
            </form>
            @endfragment

            @include('partials.bookmark.import-export.import-progress')
        </div>
    </div>
</div>
