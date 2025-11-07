@props(['label', 'type', 'name', 'value', 'error', 'nomb' => false])

<div class="@if (!$nomb) mb-3 @endif w-full">
    <x-form.label :name="$name">{{ $label }}</x-form.label>
    <input
        {{ $attributes }}
        autocomplete="off"
        class="text-a block h-[37px] w-full rounded-md border border-gray-300 px-3 py-2 text-md focus:border-orange-500 focus:outline-none"
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ isset($value) ? html_entity_decode($value) : '' }}"
    >
    <span
        class="mt-2 block hidden text-sm text-red-500"
        id="{{ $name }}-error"
    ></span>
</div>
