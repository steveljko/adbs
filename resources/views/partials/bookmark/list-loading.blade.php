@props(['bookmark'])

<li class="group flex items-center justify-between rounded-lg p-3 transition-colors duration-150 hover:bg-gray-50">
    <div class="flex min-w-0 flex-grow items-center">
        <div class="flex-shrink-0">
            <div class="animate-shimmer h-5 w-5 rounded bg-gray-200"></div>
        </div>
        <div class="ml-3 max-w-sm">
            <div
                class="animate-shimmer h-4 rounded bg-gray-200"
                style="width: 180px"
            ></div>
        </div>
        @if ($bookmark->description)
            <div class="ml-2 hidden sm:inline">
                <div
                    class="animate-shimmer h-3 rounded bg-gray-200"
                    style="width: 120px"
                ></div>
            </div>
        @endif
    </div>
    <div class="ml-2 flex items-center opacity-0 transition-opacity duration-200 group-hover:opacity-100">
        <div class="animate-shimmer h-8 w-8 rounded bg-gray-200"></div>
        <div class="animate-shimmer ml-1 h-8 w-8 rounded bg-gray-200"></div>
    </div>
</li>
