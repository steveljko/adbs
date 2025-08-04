<div
    class="fixed bottom-4 left-4 right-4 z-40"
>
    <div
        class="relative mx-auto flex max-w-lg gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-lg"
    >
        <div
            class="min-w-0 flex-1"
        >
            <div
                id="suggestions-container"
            ></div>

            <input
                @keydown.arrow-up="if (document.getElementById('suggestions-container')) {
                    $event.preventDefault();
                    const sc = Alpine.$data(document.getElementById('suggestions-container'));
                    sc.setFocusIndex(sc.suggestions.length - 1);
                    sc.updateFocus();
                }"
                autocomplete="off"
                class="h-full w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
                hx-include="#filters"
                hx-post="/dashboard/search"
                hx-trigger="focus, keyup changed delay:250ms"
                id="search"
                name="search"
                placeholder="Search bookmarks..."
                type="text"
                x-data=""
            >
            <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
            >
                <svg
                    class="h-4 w-4 text-gray-400"
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
            </div>
        </div>

        <button
            class="group flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg border border-gray-300 text-gray-600 transition-all duration-200 hover:border-orange-500 hover:bg-orange-50 hover:text-orange-500"
            hx-get="{{ route('bookmarks.create') }}"
            hx-target="#dialog"
            id="addBookmarkBtn"
            title="Add Bookmark"
        >
            <x-icon
                class="h-5 w-5 stroke-2 text-gray-500 group-hover:text-orange-600"
                name="plus"
            />
        </button>

        <div
            class="relative"
            x-data="{ dropdownOpen: false }"
        >
            <div
                @click.away="dropdownOpen = false"
                class="absolute bottom-full right-[-.35rem] z-50 mb-2 w-52 rounded-lg border border-gray-200 bg-white shadow-xl"
                x-cloak
                x-show="dropdownOpen"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter="transition ease-out duration-200"
                x-transition:leave-end="opacity-0 transform scale-95"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-150"
            >
                <div
                    class="p-3"
                >
                    <div
                        class="space-y-1"
                    >
                        <a
                            class="flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-sm transition-colors hover:bg-gray-50"
                            href="{{ route('dashboard') }}"
                        >
                            <x-icon
                                class="h-4 w-4 stroke-[1.75px] text-gray-600"
                                name="dashboard"
                            />
                            <span>Dashboard</span>
                        </a>
                        <a
                            class="flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-sm transition-colors hover:bg-gray-50"
                            href="{{ route('auth.settings') }}"
                        >
                            <x-icon
                                class="h-4 h-4 text-gray-600"
                                name="settings"
                            />
                            <span>Settings</span>
                        </a>
                        <div
                            class="my-1 border-t border-gray-100"
                        ></div>
                        <div
                            class="group flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-sm transition-colors hover:bg-red-50 hover:text-red-600"
                            hx-delete="{{ route('auth.logout') }}"
                        >
                            <x-icon
                                class="h-4 h-4 text-gray-600 group-hover:text-red-600"
                                name="logout"
                            />
                            <span>Logout</span>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute -bottom-2 right-4 h-4 w-4 rotate-45 transform border-b border-r border-gray-200 bg-white"
                >
                </div>
            </div>

            <button
                @click="dropdownOpen = !dropdownOpen"
                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <!-- TODO: implement here real avatar later -->
                VS
            </button>
        </div>
    </div>
    <x-view-switch />
</div>
