<div id="content">
    <h1 class="text-2xl text-center">Requirements</h1>
    {{ $phpVersion }}

    <div class="bg-gray-50 p-6 rounded-lg">
<h3 class="text-lg font-semibold mb-4">PHP Extensions</h3>
        <div class="grid md:grid-cols-2 gap-3">
            @foreach($extensions as $extension => $details)
            <div class="flex items-center justify-between p-3 bg-white rounded border">
                <span class="font-medium">{{ $extension }}</span>
                <div class="flex items-center">
                    <span class="text-sm text-gray-600 mr-3">{{ $details['current'] }}</span>
                    @if($details['satisfied'])
                    <i class="fas fa-check-circle text-green-600"></i>
                    @else
                    <i class="fas fa-times-circle text-red-600"></i>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex">
        <button hx-get="{{ route('installer.database') }}" hx-target="#content" hx-swap="outerHTML" class="py-3 w-full bg-orange-500 text-white rounded">Next</button>
    </div>
</div>
