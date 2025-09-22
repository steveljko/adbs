<x-card
    class="z-5"
    id="clients"
>
    <x-slot name="header">
        <h3 class="font-semibold text-gray-800">Browser Connections</h3>
    </x-slot>

    <div
        class="space-y-6"
        x-data="{ openDropdown: null }"
    >
        @forelse($clients as $client)
            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-4">
                <div class="flex-1">
                    <h4 class="font-medium capitalize text-gray-900">{{ $client->info->browser }}</h4>
                    <p class="text-sm text-gray-500">
                        @if ($client->info->browser_version)
                            Version {{ $client->info->browser_version }}
                        @endif
                        @if ($client->info->addon_version)
                            • Extension v{{ $client->info->addon_version }}
                        @endif
                    </p>
                    @if ($client->last_used_at)
                        <p class="text-xs text-gray-400">
                            Last active: {{ $client->last_used_at->diffForHumans() }}
                        </p>
                    @endif
                </div>

                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2">
                        <span
                            class="@if ($client->info->status === App\Enums\TokenStatus::PENDING) bg-yellow-100 text-yellow-800
                            @elseif($client->info->status === App\Enums\TokenStatus::ACTIVE)
                                bg-green-100 text-green-800
                            @elseif($client->info->status === App\Enums\TokenStatus::REVOKED)
                                bg-gray-100 text-gray-800
                            @else
                                bg-red-100 text-red-800 @endif inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                        >
                            {{ $client->info->status->label() }}
                        </span>
                    </div>

                    @if ($client->info->isPending())
                        <button
                            class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            hx-patch="{{ route('client.activate', $client->id) }}"
                            hx-swap="none"
                        >
                            <svg
                                class="mr-1 h-3 w-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M5 13l4 4L19 7"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                >
                                </path>
                            </svg>
                            Activate
                        </button>
                    @endif

                    <div class="relative">
                        <button
                            @click="openDropdown = openDropdown === {{ $client->id }} ? null : {{ $client->id }}"
                            class="rounded-full p-1 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            type="button"
                        >
                            <x-icon
                                class="h-4 w-4 fill-current text-gray-500"
                                name="dots"
                            />
                        </button>

                        <div
                            @click.away="openDropdown = null"
                            class="absolute right-0 z-[999] mt-2 w-48 rounded-md border border-gray-200 bg-white shadow-lg"
                            x-cloak
                            x-show="openDropdown === {{ $client->id }}"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                        >
                            <div class="py-1">
                                <button
                                    @click="openDropdown = null"
                                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                                    hx-get="{{ route('client.show', $client) }}"
                                    hx-target="#dialog"
                                >
                                    Show
                                </button>
                                @if ($client->info->isActive() || $client->info->isPending())
                                    <button
                                        @click="openDropdown = null"
                                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                                        hx-patch="{{ route('client.deactivate', $client) }}"
                                        hx-swap="none"
                                    >
                                        Deactivate
                                    </button>
                                @endif
                                @if ($client->info->isInactive())
                                    <button
                                        @click="openDropdown = null"
                                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                                        hx-patch="{{ route('client.activate', $client) }}"
                                        hx-swap="none"
                                    >
                                        Activate
                                    </button>
                                @endif
                                <button
                                    @click="openDropdown = null"
                                    class="inline-flex w-full items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50"
                                    hx-get="{{ route('client.delete', $client) }}"
                                    hx-target="#dialog"
                                >
                                    <x-icon
                                        class="mr-2 h-3.5 w-3.5"
                                        name="garbage"
                                    />
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-gray-500">
                <p class="mt-2">No browser extensions connected yet</p>
                <p class="text-sm">Install the browser extension to get started</p>
            </div>
        @endforelse
    </div>
</x-card>
