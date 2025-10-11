@props(['header' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'bg-white border rounded-lg shadow-sm']) }}>
    @if ($header || isset($header))
        <div class="flex items-center justify-between border-gray-200 px-4 pt-3">
            {{ $header }}
        </div>
    @endif

    <div class="p-4">
        {{ $slot }}
    </div>

    @if ($footer || isset($footer))
        <div
            class="border-t border-gray-200 px-4 py-2"
            hx-swap-oob="true"
            id="card-footer"
        >
            {{ $footer }}
        </div>
    @endif
</div>
