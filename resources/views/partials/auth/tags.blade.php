<div id="tags" class="shadow">
    <div class="flex justify-between p-4 bg-gray-100">
        <h4>Tags ({{ auth()->user()->tags()->count() }})</h4>
        <p>Manage tags etc...</p>
    </div>

    <div class="grid grid-cols-3 gap-2 p-4">
        @foreach (auth()->user()->tags()->orderBy('created_at', 'desc')->get() as $tag)
        <div class="inline-flex items-center p-2 cursor-pointer rounded hover:bg-gray-100"
            hx-get="{{ route('tags.edit', $tag) }}" hx-target="#dialog">
            <div style="background-color: {{ $tag->text_color }};" class="w-4 h-4 rounded-full mr-2"></div>
            {{ $tag->name }}
        </div>
        @endforeach
    </div>
</div>
