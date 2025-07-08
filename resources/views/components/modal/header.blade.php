<div class="flex justify-between px-4 py-4">
    <h5 id="modal-title" class="font-medium">{{ $slot }}</h5>
    <button type="button" class="close" aria-label="Close" @click="$store.modal.hide()">
        <x-icon name="x" class="w-4 h-4 stroke-2" />
    </button>
</div>
