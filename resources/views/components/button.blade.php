@props([
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'href' => null,
    'class' => '',
    'sid' => null,
    'id' => null,
])
@php
    $baseClasses = 'inline-flex items-center justify-center border font-medium rounded shadow-sm transition-colors focus:outline-none
focus:ring-1
disabled:opacity-50 disabled:cursor-not-allowed';
    $sizeClasses = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-2.5 py-1.5 text-sm',
        'md' => 'px-3 py-2 text-base',
        'lg' => 'px-4 py-2 text-lg',
        'xl' => 'px-5 py-2.5 text-xl',
    ];
    $variantClasses = [
        'primary' => 'bg-orange-500 hover:bg-orange-600 text-white border-transparent focus:ring-orange-500',
        'secondary' => 'bg-white hover:bg-gray-50 text-gray-500 border-gray-300 focus:ring-gray-500',
        'success' => 'bg-green-500 hover:bg-green-600 text-white border-transparent focus:ring-green-500',
        'danger' => 'bg-red-500 hover:bg-red-600 text-white border-transparent focus:ring-red-500',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white border-transparent focus:ring-yellow-500',
        'blue' => 'bg-blue-700 hover:bg-blue-600 text-white border-transparent focus:ring-blue-500',
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
    $excludedAttrs = ['class', 'variant', 'size', 'href'];
    $dynamicAttributes = $attributes->except($excludedAttrs);
@endphp
@if ($isLink)

    @if ($id)
        id="{{ $id }}"
    @endif
    {{ $dynamicAttributes }}
    @if ($variant != 'secondary')
        hx-indicator="this"
    @endif
    @if ($disabled)
        aria-disabled="true" tabindex="-1"
    @endif
    class="{{ $buttonClasses }}"
    href="{{ $href }}"
    >
@else
    <button
        {{ $dynamicAttributes }}
        @if ($disabled) disabled @endif
        @if ($variant == 'primary' || $sid != null) hx-indicator="this" @endif
        @if ($id) id="{{ $id }}" @endif
        class="{{ $buttonClasses }}"
    >
@endif
@if ($variant == 'primary' || $sid != null)
    <x-icon
        class="htmx-indicator {{ $spinnerClass }} mr-2 hidden animate-spin [&.htmx-request]:block [form.htmx-request_&]:block"
        name="spinner"
        viewBox="0 0 100 101"
    />
@endif
{{ $slot }}
@if ($isLink)
    </a>
@else
    </button>
@endif
