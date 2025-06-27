@props(['class' => 'flex justify-end space-x-2 border-t border-gray-200 p-4'])

<div {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</div>
