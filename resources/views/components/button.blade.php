@props([
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'href' => null,
    'class' => '',
])
@php
    $baseClasses =
        'inline-flex items-center justify-center font-medium rounded shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    $sizeClasses = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-2.5 py-1.5 text-sm',
        'md' => 'px-3 py-1.5 text-base',
        'lg' => 'px-4 py-2 text-lg',
        'xl' => 'px-5 py-2.5 text-xl',
    ];
    $variantClasses = [
        'primary' => 'bg-orange-500 hover:bg-orange-600 text-white focus:ring-orange-500',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
        'success' => 'bg-green-500 hover:bg-green-600 text-white focus:ring-green-500',
        'danger' => 'bg-red-500 hover:bg-red-600 text-white focus:ring-red-500',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-500',
        'blue' => 'bg-blue-700 hover:bg-blue-600 text-white focus:ring-blue-500',
    ];
    $spinnerSizes = [
        'xs' => 'w-3 h-3',
        'sm' => 'w-3 h-3',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
        'xl' => 'w-6 h-6',
    ];
    $buttonClasses = collect([
        $baseClasses,
        $sizeClasses[$size] ?? $sizeClasses['md'],
        $variantClasses[$variant] ?? $variantClasses['primary'],
        $class,
    ])
        ->filter()
        ->implode(' ');
    $spinnerClass = $spinnerSizes[$size] ?? $spinnerSizes['md'];
    $isLink = !empty($href);
    $excludedAttrs = ['class', 'variant', 'size', 'disabled', 'href'];
    $dynamicAttributes = $attributes->except($excludedAttrs);
@endphp
@if ($isLink)
    <a
        {{ $dynamicAttributes }}
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif
        class="{{ $buttonClasses }}"
        href="{{ $href }}"
        hx-indicator="this"
    >
    @else
        <button
            {{ $dynamicAttributes }}
            @if ($disabled) disabled @endif
            class="{{ $buttonClasses }}"
            hx-indicator="this"
        >
@endif
<x-icon
    class="htmx-indicator {{ $spinnerClass }} mr-2 hidden animate-spin [&.htmx-request]:block [form.htmx-request_&]:block"
    name="spinner"
    viewBox="0 0 100 101"
/>
{{ $slot }}
@if ($isLink)
    </a>
@else
    </button>
@endif
