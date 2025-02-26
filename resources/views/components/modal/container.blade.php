<div id="modal-container" class="fixed z-[60] hidden hidden h-screen w-full overflow-y-auto" tabindex="-1" role="dialog"
    aria-labelledby="#modal-title" aria-hidden="true">
    <div id="dialog" class="relative mx-auto mt-4 w-full animate-fadeInDown rounded-lg bg-white md:w-2/3 lg:w-1/3"
        role="document" hx-target="this">
        {{ $slot }}
    </div>
</div>
