<div class="fixed bottom-4 left-4 right-4 z-40">
    <div class="max-w-lg mx-auto flex bg-white border border-gray-200 rounded-xl shadow-lg px-4 py-3 relative gap-3">
        <div class="flex-1 min-w-0">
            <div id="suggestions-container"></div>

            <input
                type="text"
                placeholder="Search bookmarks..."
                name="search"
                id="search"
                hx-post="/dashboard/search"
                hx-trigger="focus, keyup changed delay:250ms"
                hx-include="#filters"
                value=""
                class="w-full h-full px-3 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                autocomplete="off"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <button
            id="addBookmarkBtn"
            class="flex-shrink-0 w-10 h-10 flex justify-center items-center border border-gray-200 hover:border-orange-500 text-gray-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all duration-200"
            hx-get="{{ route('bookmarks.create') }}"
            hx-target="#dialog"
            title="Add Bookmark"
        >
            <x-icons.plus class="w-5 h-5" />
        </button>

        <div class="relative">
            <div id="userDropdown" class="absolute bottom-full right-[-.35rem] bg-white border border-gray-200 rounded-lg shadow-xl mb-2 hidden z-50 w-52">
                <div class="p-3">
                    <div class="space-y-1">
                        <div class="px-3 py-2 hover:bg-gray-50 rounded-md cursor-pointer text-sm flex items-center gap-3 transition-colors">
                            <x-icons.settings />
                            <span>Settings</span>
                        </div>
                        <div class="border-t border-gray-100 my-1"></div>
                        <div class="px-3 py-2 hover:bg-red-50 hover:text-red-600 rounded-md cursor-pointer text-sm flex items-center gap-3 transition-colors" hx-delete="{{ route('auth.logout') }}">
                            <x-icons.logout />
                            <span>Logout</span>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-2 right-4 w-4 h-4 bg-white border-r border-b border-gray-200 transform rotate-45"></div>
            </div>

            <button
                id="userMenuBtn"
                class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-md"
            >
                <!-- TODO: implement here real avatar later -->
                VS
            </button>
        </div>
    </div>
</div>
</div>
