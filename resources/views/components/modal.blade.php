<div class="absolute z-[999]">
    <div x-data x-show="$store.modal.open" x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" style="display: none;">
        <div id="modal-backdrop" class="fixed inset-0 z-50 bg-black bg-opacity-50" @click="hide()"></div>

        <div x-ref="container" id="modal-container" class="fixed z-[60] h-screen w-full overflow-y-auto" tabindex="-1"
            role="dialog" aria-labelledby="#modal-title" :aria-hidden="!open" @keydown.escape="$store.modal.hide()">
            <div x-ref="dialog" id="dialog"
                class="relative mx-auto mt-4 w-full animate-fadeInDown rounded-lg bg-white md:w-2/3 lg:w-1/3"
                role="document" hx-target="this" @click.stop>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
