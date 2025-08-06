<div
    class="absolute bottom-0 right-0 inline-flex items-center space-x-1 rounded-md border border-gray-300 bg-white px-2 py-1.5"
    hx-swap-oob="outerHTML"
    id="switch"
>
    <button
        class="{{ request()->query('view_type') === 'list' ? 'bg-orange-50 text-orange-500 shadow-sm' : 'text-gray-500 hover:bg-gray-200' }} flex items-center space-x-1 rounded-md p-1.5 transition-colors duration-200"
        hx-get="{{ route('dashboard') }}"
        hx-include="#filters"
        hx-push-url="true"
        hx-swap="outerHTML"
        hx-target="#bookmarks-container"
        hx-vals='{"view_type": "list"}'
        id="list-view-btn"
    >
        <x-icon
            class="storke-1.5 h-4 w-4"
            name="list"
        />
        <span class="text-sm font-medium">List</span>
    </button>
    <button
        class="{{ request()->query('view_type') === 'card' || !request()->query('view_type') ? 'bg-orange-50 text-orange-500 shadow-sm' : 'text-gray-500 hover:bg-gray-200' }} flex items-center space-x-1 rounded-md p-1.5 transition-colors duration-200"
        hx-get="{{ route('dashboard') }}"
        hx-include="#filters"
        hx-push-url="true"
        hx-swap="outerHTML"
        hx-target="#bookmarks-container"
        hx-vals='{"view_type": "card"}'
        id="card-view-btn"
    >
        <x-icon
            class="stroke-1.5 h-4 w-4"
            name="card"
        />
        <span class="text-sm font-medium">Card</span>
    </button>
</div>
