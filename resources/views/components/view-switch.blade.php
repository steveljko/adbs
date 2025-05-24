@props(['type'])

<div class="inline-flex items-center space-x-1 px-2 py-1.5 bg-gray-100 rounded-md">
    <button id="list-view-btn"
        class="flex items-center space-x-1 p-1.5 rounded-md transition-colors duration-200 {{ $type === 'list' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-500 hover:bg-gray-200' }}"
        hx-get="{{ route('dashboard') }}" hx-include="#filters" hx-push-url="true" hx-vals='{"view_type": "list"}'
        hx-target="#bookmarks" title="List view">
        <x-icons.list />
        <span class="text-sm font-medium">List</span>
    </button>
    <button id="card-view-btn"
        class="flex items-center space-x-1 p-1.5 rounded-md transition-colors duration-200 {{ $type === 'card' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-500 hover:bg-gray-200' }}"
        hx-get="{{ route('dashboard') }}" hx-include="#filters" hx-push-url="true" hx-vals='{"view_type": "card"}'
        hx-target="#bookmarks" title="Card view">
        <x-icons.card />
        <span class="text-sm font-medium">Card</span>
    </button>
</div>
