@props(['searchedTags' => null, 'search' => ''])

<x-card id="tags">
    <x-slot name="header">
        <div class="flex-1">
            <h3 class="text-base font-semibold text-gray-900">Tags</h3>
            <p class="mt-0.5 text-xs text-gray-500">
                @if ($searchedTags)
                    {{ $searchedTags->total() }} total
                @else
                    {{ $tags->total() }} total
                @endif
            </p>
        </div>
        <x-button size="sm">New Tag</x-button>
    </x-slot>

    <div class="mb-4">
        <div class="relative">
            <x-icon
                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                name="search"
            />
            <input
                class="w-full rounded-lg border border-gray-200 py-2 pl-10 pr-4 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-gray-300"
                hx-get="{{ route('tags.search') }}"
                hx-include="this"
                hx-trigger="keyup changed delay:300ms"
                name="search"
                placeholder="Search tags..."
                type="text"
                value="{{ $search }}"
            >
        </div>
    </div>

    <div id="content">
        @fragment('content')
            <div id="tags-list">
                @php
                    $displayTags = $searchedTags ?? $tags;
                @endphp

                @if ($displayTags->isEmpty())
                    <div class="py-12 text-center">
                        <x-icon
                            class="mx-auto h-8 w-8 text-gray-400"
                            name="tag"
                        />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            @if ($search)
                                No tags found
                            @else
                                No tags yet
                            @endif
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if ($search)
                                Try adjusting your search criteria
                            @else
                                Get started by creating a new tag
                            @endif
                        </p>
                        @if (!$search)
                            <div class="mt-6">
                                <x-button size="sm">Create Tag</x-button>
                            </div>
                        @endif
                    </div>
                @else
                    @foreach ($displayTags as $tag)
                        <button
                            class="group flex w-full items-center gap-3 px-1 py-2.5 text-left transition-colors hover:bg-gray-50 focus:outline-none"
                            hx-get="{{ route('tags.edit', $tag) }}"
                            hx-target="#dialog"
                            type="button"
                        >
                            <span
                                class="h-2.5 w-2.5 flex-shrink-0 rounded-full"
                                style="background-color: {{ $tag->text_color }};"
                            ></span>
                            <span class="flex-1 truncate text-sm font-medium text-gray-700">{{ $tag->name }}</span>
                            <x-icon
                                class="h-4 w-4 text-gray-500"
                                name="chevron-right"
                            />
                        </button>
                    @endforeach
                @endif
            </div>
        @endfragment
    </div>

    <x-slot name="footer">
        @fragment('load-more')
            @php
                $paginatedTags = $searchedTags ?? $tags;
            @endphp

            @if ($paginatedTags->hasMorePages() || $paginatedTags->currentPage() > 1)
                <div class="flex items-center justify-between">
                    @if ($paginatedTags->currentPage() > 1)
                        <x-button
                            hx-get="{{ route('tags.search') }}"
                            hx-include="[name='search']"
                            hx-vals="{{ json_encode(['page' => $paginatedTags->currentPage() - 1]) }}"
                            size="sm"
                            variant="secondary"
                        >Previous</x-button>
                    @else
                        <x-button
                            disabled
                            size="sm"
                            variant="secondary"
                        >Previous</x-button>
                    @endif

                    @if ($paginatedTags->hasMorePages())
                        <x-button
                            hx-get="{{ route('tags.search') }}"
                            hx-include="[name='search']"
                            hx-vals="{{ json_encode(['page' => $paginatedTags->currentPage() + 1]) }}"
                            size="sm"
                        >Next</x-button>
                    @else
                        <x-button
                            disabled
                            size="sm"
                        >Next</x-button>
                    @endif
                </div>
            @endif
        @endfragment
    </x-slot>
</x-card>
