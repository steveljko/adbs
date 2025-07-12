<x-card id="clients" class="z-5">
    <x-slot name="header">
        <h3 class="font-semibold text-gray-800">Browser Extensions</h3>
    </x-slot>

    <div class="space-y-6" x-data="{ openDropdown: null }">
        @forelse($clients as $client)
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex-1">
                <h4 class="font-medium text-gray-900 capitalize">{{ $client->browser }}</h4>
                <p class="text-sm text-gray-500">
                    @if($client->browser_version)
                    Version {{ $client->browser_version }}
                    @endif
                    @if($client->addon_version)
                    • Extension v{{ $client->addon_version }}
                    @endif
                </p>
                @if($client->last_activity_at)
                <p class="text-xs text-gray-400">
                    Last active: {{ $client->last_activity_at->diffForHumans() }}
                </p>
                @endif
            </div>

            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($client->status === App\Enums\AddonClientStatus::PENDING)
                                bg-yellow-100 text-yellow-800
                            @elseif($client->status === App\Enums\AddonClientStatus::ACTIVE)
                                bg-green-100 text-green-800
                            @elseif($client->status === App\Enums\AddonClientStatus::REVOKED)
                                bg-gray-100 text-gray-800
                            @else
                                bg-red-100 text-red-800
                            @endif
                        ">
                        {{ $client->status->label() }}
                    </span>
                </div>

                @if($client->isPending())
                <button hx-patch="{{ route('token.activate', $client->id) }}" hx-swap="none" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium
                        rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2
                        focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    Activate
                </button>
                @endif

                <div class="relative">
                    <button type="button"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full p-1"
                        @click="openDropdown = openDropdown === {{ $client->id }} ? null : {{ $client->id }}">
                        <x-icon name="dots" class="w-4 h-4 fill-current text-gray-500" />
                    </button>

                    <div x-show="openDropdown === {{ $client->id }}"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95" @click.away="openDropdown = null"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-[999] border border-gray-200"
                        x-cloak>
                        <div class="py-1">
                            @if($client->isActive() || $client->isPending())
                            <button hx-patch="{{ route('token.deactivate', $client) }}" hx-swap="none"
                                @click="openDropdown = null"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Deactivate
                            </button>
                            @endif
                            @if($client->isInactive())
                            <button hx-patch="{{ route('token.activate', $client) }}" hx-swap="none"
                                @click="openDropdown = null"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Activate
                            </button>
                            @endif
                            <button
                                class="inline-flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50"
                                @click="openDropdown = null">
                                <x-icon name="garbage" class="w-3.5 h-3.5 mr-2" />
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">
            <p class="mt-2">No browser extensions connected yet</p>
            <p class="text-sm">Install the browser extension to get started</p>
        </div>
        @endforelse
    </div>
</x-card>
