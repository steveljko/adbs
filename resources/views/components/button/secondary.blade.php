<button class="px-3 py-1.5 flex items-center text-md rounded border border-gray-300 bg-white text-black shadow-md"
    {{ $attributes }}>
    <x-icons.spinner id="spinner" class="htmx-indicator [&.htmx-request]:block hidden animate-spin w-4 h-4 mr-2" />
    {{ $slot }}
</button>
