@props(['header' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md overflow-hidden']) }}>
    @if($header || isset($header))
    <div class="flex items-center justify-between p-4 bg-gray-100 border-b border-gray-200">
        {{ $header }}
    </div>
    @endif

    <div class="p-4">
        {{ $slot }}
    </div>

    @if($footer || isset($footer))
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
        {{ $footer }}
    </div>
    @endif
</div>
