<x-card id="tags">
    <x-slot name="header">
        <h3 class="font-semibold text-gray-800">Tags ({{ $tags->count() }})</h3>
        <x-button size="sm">
            New Tag
        </x-button>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
        @forelse($tags as $tag)
        <div class="flex items-center p-2 cursor-pointer rounded hover:bg-gray-100 transition-colors"
            hx-get="{{ route('tags.edit', $tag) }}" hx-target="#dialog">
            <div class="w-4 h-4 rounded-full mr-2 flex-shrink-0" style="background-color: {{ $tag->text_color }};">
            </div>
            <span class="text-sm text-gray-700 truncate">{{ $tag->name }}</span>
        </div>
        @empty
        <div class="col-span-full text-center py-8 text-gray-500">
            <p class="text-base font-medium">No tags</p>
            <p class="text-sm mt-1">Add tags to get started</p>
        </div>
        @endforelse
    </div>
</x-card>
