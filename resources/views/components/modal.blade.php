<div class="absolute z-[999]">
    <div
        style="display: none;"
        x-data
        x-show="$store.modal.open"
        x-show="open"
        x-transition:enter-end="opacity-100"
        x-transition:enter-start="opacity-0"
        x-transition:enter="transition ease-out duration-300"
        x-transition:leave-end="opacity-0"
        x-transition:leave-start="opacity-100"
        x-transition:leave="transition ease-in duration-200"
    >
        <div
            @click="hide()"
            class="fixed inset-0 z-50 bg-black bg-opacity-40"
            id="modal-backdrop"
        ></div>

        <div
            :aria-hidden="!open"
            @keydown.escape="$store.modal.hide()"
            aria-labelledby="#modal-title"
            class="fixed z-[60] h-screen w-full overflow-y-auto"
            id="modal-container"
            role="dialog"
            tabindex="-1"
            x-ref="container"
        >
            <div
                @click.stop
                class="animate-fadeInDown absolute right-0 top-0 h-screen w-1/2 rounded-lg bg-white md:w-1/4"
                hx-target="this"
                id="dialog"
                role="document"
                x-ref="dialog"
            >
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
