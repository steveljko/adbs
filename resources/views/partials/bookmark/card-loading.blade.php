@props(['bookmark'])

<div x-show="loading" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-white z-10">
    <div class="flex items-center p-4 border-b border-gray-100">
        <div class="flex-shrink-0 mr-3">
            <div class="w-6 h-6 bg-gray-200 rounded animate-shimmer"></div>
        </div>
        <div class="flex-grow">
            <div class="h-4 bg-gray-200 rounded animate-shimmer mb-2" style="width: 75%"></div>
            <div class="h-3 bg-gray-200 rounded animate-shimmer" style="width: 55%"></div>
        </div>
    </div>

    @if ($bookmark->description)
    <div class="p-4 border-b border-gray-100">
        <div class="space-y-2">
            <div class="h-3 bg-gray-200 rounded animate-shimmer" style="width: 90%"></div>
            <div class="h-3 bg-gray-200 rounded animate-shimmer" style="width: 70%"></div>
            <div class="h-3 bg-gray-200 rounded animate-shimmer" style="width: 50%"></div>
        </div>
    </div>
    @endif

    @if ($bookmark->tags->isnotempty())
    <div class="px-4 py-2">
        <div class="flex flex-wrap gap-1">
            @foreach ($bookmark->tags as $tag)
            <div class="h-6 bg-gray-200 rounded animate-shimmer" style="width: {{ strlen($tag->name) * 8 + 16 }}px">
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="p-3 bg-gray-50 flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-4 h-4 bg-gray-200 rounded animate-shimmer mr-2"></div>
            <div class="h-3 bg-gray-200 rounded animate-shimmer" style="width: 80px"></div>
        </div>
        <div class="flex items-center space-x-1">
            <div class="w-6 h-6 bg-gray-200 rounded animate-shimmer"></div>
            <div class="w-6 h-6 bg-gray-200 rounded animate-shimmer"></div>
        </div>
    </div>
</div>
