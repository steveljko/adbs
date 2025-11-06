@props(['class' => 'flex justify-end space-x-2 p-4 pt-0'])

<div {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</div>
