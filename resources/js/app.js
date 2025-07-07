import htmx from 'htmx.org';
window.htmx = htmx;

import './echo';

import Notify from 'simple-notify'
import 'simple-notify/dist/simple-notify.css'

import Alpine from 'alpinejs'

window.Alpine = Alpine

if (window.userId) {
    window.Echo.private(`import-progress.${window.userId}`)
        .listen('.progress.updated', (data) => {
            document.getElementById('progress-section').classList.remove('hidden');
            document.getElementById('progress-container').innerHTML = `
                <div id="progress-container">
                    <div class="progress-info mb-3">
                        <div class="flex justify-between items-center">
                            <span id="progress-message" class="text-gray-700">Initializing...</span>
                            <span id="progress-percentage" class="text-gray-700 font-medium">${data.percentage}%</span>
                        </div>
                        <div class="progress-stats mt-2">
                            <small class="text-gray-500 text-sm">
                                Processed: <span id="progress-processed" class="font-medium">${data.processed}</span>/<span
                                    id="progress-total" class="font-medium">${data.total}</span> |
                                Successful: <span id="progress-successful" class="font-medium text-green-600">${data.successful}</span> |
                                Failed: <span id="progress-failed" class="font-medium text-red-600">${data.failed}</span>
                            </small>
                        </div>
                    </div>
                    <div class="progress mb-3 bg-gray-200 rounded-full h-5 overflow-hidden">
                        <div class="progress-bar bg-blue-500 h-full transition-all duration-300 ease-out"
                            role="progressbar" id="progress-bar" style="width: ${data.percentage}%" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                    <div id="progress-status" class="text-center">
                        <div class="inline-flex items-center">
                            <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"
                                role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <small class="text-gray-500 ml-2 text-sm">${data.message}</small>
                        </div>
                    </div>
                </div>
            `;

            if (data.percentage === 100 && data.message == "Import completed!") {
                new Notify({
                    status: 'success',
                    title: 'Import successfully finished.',
                });
            }
        })
}

// fix focus only
document.addEventListener('alpine:init', () => {
    Alpine.store('modal', {
        open: false,
        show() {
            this.open = true;
        },
        hide() {
            this.open = false;
        }
    });

    window.modal = Alpine.store('modal');
});

Alpine.start()

htmx.on('htmx:afterSwap', (e) => {
    if (e.detail.target.id == 'dialog') {
        window.modal.show();
    }
});

htmx.on('htmx:beforeSwap', (e) => {
    if (e.detail.target.id == 'dialog' && !e.detail.xhr.response) {
        window.modal.hide();
        e.detail.shouldSwap = false;
    }
});

htmx.on('hideModal', () => window.modal.hide());

htmx.on('toast', (e) => {
    const { type, text, altText } = e.detail;

    new Notify({
        status: type,
        title: text,
        text: altText,
    });
});

document.addEventListener('htmx:responseError', function (event) {
    console.log(event.detail.xhr);
    const errors = JSON.parse(event.detail.xhr.response).errors;

    for (const [field, messages] of Object.entries(errors)) {
        const container = document.querySelector(`#${field}-error`);
        container.classList.remove('hidden');
        container.innerHTML = messages[0];
    }
});

document.addEventListener('DOMContentLoaded', () => {
    Coloris({
        el: '#color',
        theme: 'pill',
        onChange: (color, input) => {
            document.getElementById('color-res').style.backgroundColor = color;
        }
   });
});
