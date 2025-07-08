<div class="w-full">
    <div class="flex gap-4">
        <div>
            <x-form.label name="favicon">Favicon</x-form.label>
            <img src="{{ asset($favicon) }}" alt="favicon">
            <input type="text" name="favicon" class="hidden" value="{{ $favicon }}">
        </div>
        <x-form.input type="text" name="title" label="Website Title" :value="$title" />
    </div>
    <div>
        <x-form.tags :tags="$tags" />
    </div>
</div>
