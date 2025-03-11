@props(['tags', 'selectedTags'])

<div>
    <x-form.label name="tags">Tags</x-form.label>
    <div class="w-full">
        <div class="relative">
            <input
                class="block text-md w-full rounded-md border border-gray-300 px-3 py-2 text-a focus:border-orange-500 focus:outline-none"
                type="text" name="name" id="name" placeholder="Add a tag" hx-trigger="keyup changed delay:500ms"
                hx-get="{{ route('tags') }}" hx-include="#name,#tags" hx-target="#suggestions-container"
                autocomplete="off" {{ $attributes }} />
            <div id="suggestions-container"></div>
        </div>
        <div id="tags" class="mt-2 flex space-x-2 flex-wrap">
            @if (isset($selectedTags))
                @foreach ($selectedTags as $tag)
                    @include('resources.dashboard.filters.tag', ['tag' => $tag->name])
                @endforeach
            @endif
        </div>
        <span id="tags-error" class="hidden text-red-500 block text-sm mt-2"></span>
    </div>
</div>
