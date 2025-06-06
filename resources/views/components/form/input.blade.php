@props(['label', 'type', 'name', 'value', 'error'])

<div class="mb-3 w-full">
    <x-form.label :name="$name">{{ $label }}</x-form.label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        value="{{ isset($value) ? html_entity_decode($value) : '' }}"
        class="block text-md w-full rounded-md border border-gray-300 px-3 py-2 text-a focus:border-orange-500 focus:outline-none"
        autocomplete="off" {{ $attributes }}>
    <span id="{{ $name }}-error" class="hidden text-red-500 block text-sm mt-2"></span>
</div>
