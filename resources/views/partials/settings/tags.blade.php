<x-card id="tags">
    <x-slot name="header">
        <h3 class="font-semibold text-gray-800">Tags ({{ $tags->count() }})</h3>
        <button class="px-2 text-sm py-1.5 bg-orange-500 text-white rounded hover:bg-orange-600">New Tag</button>
    </x-slot>

    <div class="grid grid-cols-3 gap-2">
        @foreach ($tags as $tag)
        <div class="inline-flex items-center p-2 cursor-pointer rounded hover:bg-gray-100"
            hx-get="{{ route('tags.edit', $tag) }}" hx-target="#dialog">
            <div style="background-color: {{ $tag->text_color }};" class="w-4 h-4 rounded-full mr-2"></div>
            {{ $tag->name }}
        </div>
        @endforeach
    </div>
</x-card>
