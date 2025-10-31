<x-card id="view-type">
    <x-slot name="header">
        <h3 class="font-semibold text-gray-800">Appearance</h3>
    </x-slot>

    <div class="space-y-6">
        @fragment('choose')
            <section id="choose">
                <h3 class="mb-3 text-sm font-medium text-gray-700">View Style</h3>
                <div class="inline-flex w-full rounded-lg border border-gray-200 bg-gray-50 p-1">
                    <button
                        class="{{ preferences()->get('view_type') === 'list' ? 'bg-white text-gray-900 border-gray-100 shadow-sm' : 'border-transparent text-gray-600 hover:text-gray-900' }} flex w-full items-center gap-2 rounded-md border px-3 py-2 text-sm font-medium transition-all"
                        hx-put="{{ route('settings.viewType') }}"
                        hx-swap="outerHTML"
                        hx-target="#choose"
                        hx-vals='{"view_type": "list"}'
                        id="list-view-btn"
                    >
                        <x-icon
                            class="h-4 w-4"
                            name="list"
                        />
                        <span>List</span>
                    </button>

                    <button
                        class="{{ preferences()->get('view_type') === 'card' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }} flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition-all"
                        hx-put="{{ route('settings.viewType') }}"
                        hx-swap="outerHTML"
                        hx-target="#choose"
                        hx-vals='{"view_type": "card"}'
                        id="card-view-btn"
                    >
                        <x-icon
                            class="h-4 w-4"
                            name="card"
                        />
                        <span>Card</span>
                    </button>
                </div>
            </section>
        @endfragment
    </div>
</x-card>
