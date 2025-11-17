@props(['tags', 'selectedTags'])

<div id="tags-field">
    <x-form.label name="tags">Tags</x-form.label>
    <div class="w-full">
        <div class="relative">
            <input
                {{ $attributes }}
                autocomplete="off"
                class="text-a block w-full rounded-md border border-gray-300 px-3 py-2 text-md focus:border-orange-500 focus:outline-none"
                hx-get="{{ route('tags') }}"
                hx-include="#name,#tags"
                hx-target="#suggestions-container"
                hx-trigger="keyup changed delay:500ms"
                id="name"
                name="name"
                placeholder="Add a tag"
                type="text"
            />
            <div id="suggestions-container"></div>
        </div>
        <div
            class="mt-2 flex flex-wrap space-x-2"
            id="tags"
        >
            @if (isset($selectedTags))
                @foreach ($selectedTags as $tag)
                    @include('partials.dashboard.filters.tag', ['tag' => $tag])
                @endforeach
            @endif
        </div>
    </div>
</div>
