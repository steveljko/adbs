<x-card>
    <x-slot name="header">
        <h3 class="font-semibold text-gray-800">Browser Extensions</h3>
    </x-slot>

    <div class="space-y-6">
        Additional Clients
        {{ $clients[0]->browser }}
    </div>
</x-card>
