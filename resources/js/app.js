import './echo';
import htmx from 'htmx.org';
import Notify from 'simple-notify'
import 'simple-notify/dist/simple-notify.css'
import Coloris from "@melloware/coloris";
import "@melloware/coloris/dist/coloris.css";
import Alpine from 'alpinejs'
import Masonry from "masonry-layout";

window.htmx = htmx;
window.Alpine = Alpine
Coloris.init();
window.Coloris = Coloris;

document.addEventListener('alpine:init', () => {
    Alpine.store('modal', {
        open: false,
        show() {
            this.open = true

            Alpine.nextTick(() => {
                const modalContainer = document.getElementById('modal-container');
                if (modalContainer) {
                    modalContainer.focus();
                }
            });
        },
        hide() { this.open = false }
    });

    window.modal = Alpine.store('modal');
});

htmx.on('htmx:afterSwap', (e) => { if (e.detail.target.id == 'dialog') window.modal.show() });

htmx.on('htmx:beforeSwap', (e) => {
    if (e.detail.target.id == 'dialog' && !e.detail.xhr.response) {
        window.modal.hide();
        e.detail.shouldSwap = false;
    }
});

htmx.on('hideModal', () => window.modal.hide());

Alpine.start();

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

let masonry;
document.addEventListener('htmx:afterSettle', (e) => {
    if (e.target.id === 'bookmarks-container') {
        const viewType = e.detail.requestConfig.parameters?.view_type ||
                         new URLSearchParams(window.location.search).get('view_type') || 'card';
        if (viewType === 'card') {
            setTimeout(() => {
                initMasonry();
            }, 250);
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('view_type') === 'card' || !urlParams.get('view_type')) {
        setTimeout(() => {
            initMasonry();
        }, 250);
    }
});


function initMasonry() {
    const container = document.getElementById('bookmarks-container');
    if (container) {
        masonry = new Masonry(container, {
            itemSelector: '.bookmark-card',
            gutter: 16,
            horizontalOrder: true
        });
    }
}
