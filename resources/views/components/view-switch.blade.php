<div id="switch" hx-swap-oob="outerHTML"
    class="absolute bottom-0 right-0 inline-flex items-center space-x-1 px-2 py-1.5 bg-white border border-gray-300 rounded-md">
    <button id="list-view-btn"
        class="flex items-center space-x-1 p-1.5 rounded-md transition-colors duration-200 {{ request()->query('view_type') === 'list' ? 'bg-orange-50 text-orange-500 shadow-sm' : 'text-gray-500 hover:bg-gray-200' }}"
        hx-get="{{ route('dashboard') }}" hx-include="#filters" hx-push-url="true" hx-vals='{"view_type": "list"}'
        hx-target="#bookmarks" title="List view">
        <x-icon name="list" class="w-4 h-4 storke-1.5" />
        <span class="text-sm font-medium">List</span>
    </button>
    <button id="card-view-btn"
        class="flex items-center space-x-1 p-1.5 rounded-md transition-colors duration-200 {{ request()->query('view_type') === 'card' || !request()->query('view_type') ? 'bg-orange-50 text-orange-500 shadow-sm' : 'text-gray-500 hover:bg-gray-200' }}"
        hx-get="{{ route('dashboard') }}" hx-include="#filters" hx-push-url="true" hx-vals='{"view_type": "card"}'
        hx-target="#bookmarks" title="Card view">
        <x-icon name="card" class="w-4 h-4 stroke-1.5" />
        <span class="text-sm font-medium">Card</span>
    </button>
</div>
