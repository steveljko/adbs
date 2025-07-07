<div class="flex justify-between px-4 py-4">
    <h5 id="modal-title" class="font-medium">{{ $slot }}</h5>
    <button type="button" class="close" aria-label="Close" @click="$store.modal.hide()">
        <span class="px-2 py-2" aria-hidden="true">&times;</span>
    </button>
</div>
