<div class="fixed bottom-4 left-4 right-4 z-40">
    <div class="max-w-md mx-auto flex bg-white border border-gray-200 rounded-xl shadow-lg px-4 py-3 relative">
        <div class="relative">
            <div id="suggestions-container"></div>

            <input
                type="text"
                placeholder="Search bookmarks..."
                name="search"
                id="search"
                hx-post="{{ route('dashboard.search') }}"
                hx-trigger="focus, keyup changed delay:250ms"
                hx-include="#filters"
                value="{{ request('title') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                autocomplete="off"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <x-icons.search />
            </div>
        </div>
    </div>
</div>
