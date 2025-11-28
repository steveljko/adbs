<div
    class="mx-auto hidden w-full max-w-xl"
    hx-swap="none"
    id="progress"
    sse-connect="/bookmarks/import/progress"
    sse-swap="message"
>
    <div class="space-y-3">
        <div class="relative h-8 overflow-hidden rounded-lg bg-gray-200">
            <div
                class="progress-fill flex h-full items-center justify-center rounded-lg bg-blue-600 text-sm font-semibold text-white transition-all duration-500 ease-out"
                id="progressFill"
                style="width: 0%"
            >
                <span
                    class="relative z-10"
                    id="progressPercent"
                >0%</span>
            </div>
        </div>

        <div
            class="message text-center text-sm text-gray-600"
            id="progressMessage"
        >
            Preparing...
        </div>
    </div>
</div>
