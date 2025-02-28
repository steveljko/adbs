<div class="w-full">
    <div>
        <x-form.input type="text" name="title" label="Website Title" value="{{ $title }}" error="" />
    </div>
    <div>
        <x-form.label name="favicon">Favicon</x-form.label>
        <img src="{{ asset($favicon) }}" alt="favicon">
        <input type="text" name="favicon" class="hidden" value="{{ $favicon }}">
    </div>
</div>
