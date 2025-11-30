@props([
    'progress' => 0,
    'message' => 'Preparing...',
])

<div
    class="mx-auto w-full max-w-xl"
    hx-get="{{ route('bookmarks.import.progress') }}"
    hx-swap="innerHTML"
    hx-trigger="every 500ms [!htmx.closest(this, '#progress').classList.contains('complete')]"
    id="progress"
>
    @fragment('content')
        <div class="space-y-3">
            <div class="relative h-8 overflow-hidden rounded-lg bg-gray-200">
                <div
                    class="{{ $progress != 100 ? 'bg-blue-600' : 'bg-green-500' }} flex h-full items-center justify-center rounded-lg text-sm font-semibold text-white transition-all duration-500 ease-out"
                    style="width: {{ $progress }}%"
                >
                    <span class="relative z-10">{{ $progress }}%</span>
                </div>
            </div>
            <div class="{{ $progress != 100 ? 'text-gray-600' : 'text-green-500' }} text-center text-sm">
                {{ $message }}
            </div>
        </div>
    @endfragment
</div>
