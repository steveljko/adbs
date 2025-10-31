<div class="fixed bottom-4 left-0 right-0 z-40 flex justify-center">
    <div
        class="relative flex max-w-2xl gap-2 rounded-full border border-gray-200 bg-white px-3 py-2 shadow"
        x-data="{ searchExpanded: false }"
    >

        @if (Route::is('dashboard'))
            <div class="flex flex-1 items-center gap-2">
                <button
                    @click="searchExpanded = !searchExpanded"
                    class="group flex h-9 items-center gap-2 rounded-full border border-gray-300 px-3 text-sm text-gray-600 transition-all duration-200 hover:border-orange-500 hover:bg-orange-50 hover:text-orange-600"
                    title="Search bookmarks"
                    x-show="!searchExpanded"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                        ></path>
                    </svg>
                    <span class="text-sm">Search</span>
                </button>

                <div
                    class="relative flex-1"
                    x-cloak
                    x-show="searchExpanded"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter="transition ease-out duration-200"
                >
                    <input
                        @keydown.arrow-up="if (document.getElementById('suggestions-container')) {
                           $event.preventDefault();
                           const sc = Alpine.$data(document.getElementById('suggestions-container'));
                           sc.setFocusIndex(sc.suggestions.length - 1);
                           sc.updateFocus();
                       }"
                        @keydown.escape="searchExpanded = false"
                        autocomplete="off"
                        class="h-9 w-full rounded-full border border border-gray-300 border-orange-500 bg-orange-50 px-3 pr-20 text-sm text-orange-500 placeholder:text-orange-500 focus:outline-none"
                        hx-include="#filters"
                        hx-post="/dashboard/search"
                        hx-trigger="keyup changed delay:500ms"
                        id="search"
                        name="search"
                        placeholder="Search bookmarks..."
                        type="text"
                        value="{{ request('title', null) }}"
                        x-init="$watch('searchExpanded', value => { if (value) $nextTick(() => $el.focus()) })"
                    >
                    <div class="absolute inset-y-0 right-0 flex items-center gap-1.5 pr-3">
                        <button
                            @click="searchExpanded = false"
                            class="rounded p-1 text-orange-500"
                        >
                            <x-icon
                                class="h-4 w-4 stroke-2"
                                name="x"
                            />
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if (Route::is('settings'))
            <a
                class="focus:outline-none"
                href="{{ route('dashboard') }}"
            >
                <button
                    class="group flex h-9 items-center gap-2 rounded-full border border-gray-300 px-4 text-sm text-gray-600 transition-all duration-200 hover:border-orange-500 hover:bg-orange-50 hover:text-orange-600"
                    title="Dashboard"
                >
                    <x-icon
                        class="h-4 w-4 stroke-[1.5px] text-current"
                        name="dashboard"
                    />
                    <span class="text-sm">Dashboard</span>
                </button>
            </a>
        @endif

        <button
            class="group flex h-9 w-9 items-center justify-center rounded-full border border-gray-300 text-gray-600 transition-all duration-200 hover:border-orange-500 hover:bg-orange-50 hover:text-orange-500"
            hx-get="{{ route('bookmarks.create') }}"
            hx-target="#dialog"
            id="addBookmarkBtn"
            title="Add Bookmark"
        >
            <x-icon
                class="h-4 w-4 stroke-2"
                name="plus"
            />
        </button>

        <div
            class="relative"
            x-data="{ dropdownOpen: false }"
        >
            <div
                @click.away="dropdownOpen = false"
                class="absolute bottom-full right-0 z-50 mb-2 w-48 rounded-lg border border-gray-200 bg-white shadow-xl"
                x-cloak
                x-show="dropdownOpen"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter="transition ease-out duration-200"
                x-transition:leave-end="opacity-0 transform scale-95"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-150"
            >
                <div class="p-2">
                    <div class="space-y-0.5">
                        <a
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-3 py-2 text-sm transition-colors hover:bg-gray-50"
                            href="{{ route('settings') }}"
                        >
                            <x-icon
                                class="h-4 w-4 text-gray-600"
                                name="settings"
                            />
                            <span>Settings</span>
                        </a>
                        <div class="my-1 border-t border-gray-100"></div>
                        <div
                            class="group flex cursor-pointer items-center gap-2.5 rounded-md px-3 py-2 text-sm transition-colors hover:bg-red-50 hover:text-red-600"
                            hx-delete="{{ route('auth.logout') }}"
                        >
                            <x-icon
                                class="h-4 w-4 text-gray-600 group-hover:text-red-600"
                                name="logout"
                            />
                            <span>Logout</span>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute -bottom-2 right-3 h-3 w-3 rotate-45 transform border-b border-r border-gray-200 bg-white">
                </div>
            </div>

            <button
                :class="dropdownOpen ? 'border-orange-500 bg-orange-50 text-orange-500' :
                    'border-gray-300 text-gray-600 hover:border-orange-500 hover:bg-orange-50 hover:text-orange-500'"
                @click="dropdownOpen = !dropdownOpen"
                class="group flex h-9 w-9 items-center justify-center rounded-full border border-gray-300 text-gray-600 transition-all duration-200"
            >
                <x-icon
                    class="h-4 w-4 stroke-2"
                    name="list"
                />
            </button>
        </div>

        <!-- hidden suggestions container -->
        <div
            class="hidden"
            id="suggestions-container"
        ></div>
    </div>
</div>
