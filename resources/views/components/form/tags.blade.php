@props(['tags'])

<div>
    <x-form.label name="tags">Tags</x-form.label>
    <div class="w-full">
        <div class="relative">
            <input
                class="block text-md w-full rounded-md border border-gray-300 px-3 py-2 text-a focus:border-orange-500 focus:outline-none"
                type="text" id="add-tag-input" id="tag-input" placeholder="Add a tag" autocomplete="off" />
            <div id="suggestions" class="absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden">
            </div>
        </div>
        <div id="tags" class="mt-2 flex flex-wrap" data-tags="{{ json_encode($tags) }}"></div>
    </div>
</div>
