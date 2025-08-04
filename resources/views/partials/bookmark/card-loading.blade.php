@props(['bookmark'])

<div
    class="flex items-center border-b border-gray-100 p-4"
>
    <div
        class="mr-3 flex-shrink-0"
    >
        <div
            class="animate-shimmer h-6 w-6 rounded bg-gray-200"
        ></div>
    </div>
    <div
        class="flex-grow"
    >
        <div
            class="animate-shimmer mb-2 h-4 rounded bg-gray-200"
            style="width: 75%"
        ></div>
        <div
            class="animate-shimmer h-3 rounded bg-gray-200"
            style="width: 55%"
        ></div>
    </div>
</div>

@if ($bookmark->description)
    <div
        class="border-b border-gray-100 p-4"
    >
        <div
            class="space-y-2"
        >
            <div
                class="animate-shimmer h-3 rounded bg-gray-200"
                style="width: 90%"
            ></div>
            <div
                class="animate-shimmer h-3 rounded bg-gray-200"
                style="width: 70%"
            ></div>
            <div
                class="animate-shimmer h-3 rounded bg-gray-200"
                style="width: 50%"
            ></div>
        </div>
    </div>
@endif

@if ($bookmark->tags->isnotempty())
    <div
        class="px-4 py-2"
    >
        <div
            class="flex flex-wrap gap-1"
        >
            @foreach ($bookmark->tags as $tag)
                <div
                    class="animate-shimmer h-6 rounded bg-gray-200"
                    style="width: {{ strlen($tag->name) * 8 + 16 }}px"
                >
                </div>
            @endforeach
        </div>
    </div>
@endif

<div
    class="flex items-center justify-between bg-gray-50 p-3"
>
    <div
        class="flex items-center"
    >
        <div
            class="animate-shimmer mr-2 h-4 w-4 rounded bg-gray-200"
        ></div>
        <div
            class="animate-shimmer h-3 rounded bg-gray-200"
            style="width: 80px"
        ></div>
    </div>
    <div
        class="flex items-center space-x-1"
    >
        <div
            class="animate-shimmer h-6 w-6 rounded bg-gray-200"
        ></div>
        <div
            class="animate-shimmer h-6 w-6 rounded bg-gray-200"
        ></div>
    </div>
</div>
