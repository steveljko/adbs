<x-card id="view-type">
    <x-slot name="header">
        <h3 class="font-semibold text-gray-800">Appearnce</h3>
    </x-slot>

    <div class="space-y-4">
        @fragment('choose')
            <section id="choose">
                <h3 class="mb-3 text-[15px] text-gray-600">View Style</h3>
                <div class="flex space-x-2 rounded border p-1">
                    <button
                        class="{{ preferences()->get('view_type') === 'list' ? 'bg-orange-50 text-orange-500 shadow-sm' : 'text-gray-500 hover:bg-gray-200' }} flex w-full items-center space-x-1 rounded-md p-1.5 transition-colors duration-200"
                        hx-put="{{ route('settings.viewType') }}"
                        hx-swap="outerHTML"
                        hx-target="#choose"
                        hx-vals='{"view_type": "list"}'
                        id="list-view-btn"
                    >
                        <x-icon
                            class="storke-1.5 h-4 w-4"
                            name="list"
                        />
                        <span class="text-sm font-medium">List</span>
                    </button>
                    <button
                        class="{{ preferences()->get('view_type') === 'card' ? 'bg-orange-50 text-orange-500 shadow-sm' : 'text-gray-500 hover:bg-gray-200' }} flex w-full items-center space-x-1 rounded-md p-1.5 transition-colors duration-200"
                        hx-put="{{ route('settings.viewType') }}"
                        hx-swap="outerHTML"
                        hx-target="#choose"
                        hx-vals='{"view_type": "card"}'
                        id="card-view-btn"
                    >
                        <x-icon
                            class="stroke-1.5 h-4 w-4"
                            name="card"
                        />
                        <span class="text-sm font-medium">Card</span>
                    </button>
                    <button
                        class="{{ preferences()->get('view_type') === 'low_performance' ? 'bg-orange-50 text-orange-500 shadow-sm' : 'text-gray-500 hover:bg-gray-200' }} flex w-full items-center space-x-1 rounded-md p-1.5 py-2 text-gray-500 transition-colors duration-200 hover:bg-gray-200"
                        hx-put="{{ route('settings.viewType') }}"
                        hx-swap="outerHTML"
                        hx-target="#choose"
                        hx-vals='{"view_type": "low_performance"}'
                        id="low-performance-view-btn"
                    >
                        <x-icon
                            class="stroke-1.5 h-4 w-4"
                            name="card"
                        />
                        <span class="text-sm font-medium">Low Performance</span>
                    </button>
                </div>
            </section>
        @endfragment

        <section id="disable">
            <h3 class="mb-3 text-[15px] text-gray-600">Screen Type Switch</h3>
            <label class="flex items-center space-x-3">
                <input
                    @checked(preferences()->get('disable_view_switch'))
                    autocomplete="off"
                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-orange-600 focus:ring-2 focus:ring-orange-500"
                    hx-put="{{ route('settings.disableViewSwitch') }}"
                    id="disableToggle"
                    type="checkbox"
                >
                <span class="text-sm text-gray-600">Disable bottom right switch</span>
            </label>
        </section>
    </div>
</x-card>
