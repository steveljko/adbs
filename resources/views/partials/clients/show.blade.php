<div id="modal-content">
    <x-modal.header>View Client</x-modal.header>
    <x-modal.body>
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($addonClient->isPending())
                                bg-yellow-100 text-yellow-800
                            @elseif($addonClient->isActive())
                                bg-green-100 text-green-800
                            @elseif($addonClient->status === App\Enums\AddonClientStatus::REVOKED)
                                bg-gray-100 text-gray-800
                            @else
                                bg-red-100 text-red-800
                            @endif
                        ">
                        {{ $addonClient->status->label() }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Browser</label>
                    <p class="text-sm text-gray-900">{{ $addonClient->browser }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Browser Version</label>
                    <p class="text-sm text-gray-900">{{ $addonClient->browser_version }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Addon Version</label>
                    <p class="text-sm text-gray-900">{{ $addonClient->addon_version }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                    <p class="text-sm text-gray-900 font-mono">{{ $addonClient->ip_address }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Activity</label>
                    <p class="text-sm text-gray-900">
                        @if($addonClient->last_activity_at)
                        {{ $addonClient->last_activity_at->format('M j, Y g:i A') }}
                        <span class="text-gray-500">({{ $addonClient->last_activity_at->diffForHumans() }})</span>
                        @else
                        Never
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                    <p class="text-sm text-gray-900">
                        {{ $addonClient->created_at->format('M j, Y g:i A') }}
                        <span class="text-gray-500">({{ $addonClient->created_at->diffForHumans() }})</span>
                    </p>
                </div>
            </div>

            @if($addonClient->user_agent)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">User Agent</label>
                <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded font-mono break-all">{{ $addonClient->user_agent
                    }}</p>
            </div>
            @endif

            @if($addonClient->notes)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded whitespace-pre-wrap">{{ $addonClient->notes }}
                </p>
            </div>
            @endif
        </div>
    </x-modal.body>
    <x-modal.footer>
        <x-button variant="secondary" @click="$store.modal.hide()">Cancel</x-button.secondary>
    </x-modal.footer>
</div>
