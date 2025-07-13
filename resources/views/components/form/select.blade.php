@props(['label', 'name', 'value', 'error', 'options' => [], 'placeholder' => null, 'disabled' => false])

<div class="mb-3 w-full">
    <x-form.label :name="$name">{{ $label }}</x-form.label>
    <select name="{{ $name }}" id="{{ $name }}"
        class="block text-md w-full rounded-md border border-gray-300 px-3 py-2 text-a focus:border-orange-500 focus:outline-none {{ $disabled ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}"
        {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        @if($placeholder)
        <option value="" {{ !isset($value) || $value==='' ? 'selected' : '' }} disabled>{{ $placeholder }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
        @php
        $isDisabled = false;
        $displayLabel = $optionLabel;

        if (is_array($optionLabel)) {
        $displayLabel = $optionLabel['label'] ?? $optionLabel['text'] ?? $optionValue;
        $isDisabled = $optionLabel['disabled'] ?? false;
        }
        @endphp
        <option value="{{ $optionValue }}" {{ isset($value) && $value==$optionValue ? 'selected' : '' }} {{ $isDisabled
            ? 'disabled' : '' }}>
            {{ $displayLabel }}
        </option>
        @endforeach
    </select>
    <span id="{{ $name }}-error" class="hidden text-red-500 block text-sm mt-2"></span>
</div>
