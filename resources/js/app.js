import './echo';
import htmx from 'htmx.org';
import Notify from './notify'
import Coloris from "@melloware/coloris";
import "@melloware/coloris/dist/coloris.css";
import Alpine from 'alpinejs'

window.htmx = htmx;
window.Alpine = Alpine
Coloris.init();
window.Coloris = Coloris;
const notify = new Notify;

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

    notify[type](text);
});

htmx.on('toast_after_redirect', (e) => {
    const { type, text, altText } = e.detail;
    sessionStorage.setItem('toast_after_redirect', JSON.stringify({
        status: type,
        title: text,
        text: altText,
    }));
});

document.addEventListener('DOMContentLoaded', () => {
    const storedToast = sessionStorage.getItem('toast_after_redirect');
    if (storedToast) {
        const toastData = JSON.parse(storedToast);

        notify[toastData.status](toastData.title);

        sessionStorage.removeItem('toast_after_redirect');
    }

    setTimeout(() => {
        document.querySelectorAll('#favicon').forEach(img => {
            if (img.complete) {
                checkFaviconBrightness(img);
            } else {
                img.addEventListener('load', () => checkFaviconBrightness(img));
            }
        });
    }, 500);
});

document.addEventListener('htmx:responseError', function (event) {
    const errors = JSON.parse(event.detail.xhr.response).errors;

    for (const [field, messages] of Object.entries(errors)) {
        const container = document.querySelector(`#${field}-error`);
        container.classList.remove('hidden');
        container.innerHTML = messages[0];
    }
});

// clears validation errors as users type on input
document.addEventListener('keydown', (event) => {
    if (event.target.matches('input[name]')) {
        const fieldName = event.target.getAttribute('name');
        const errorContainer = document.querySelector(`#${fieldName}-error`);
        if (errorContainer && !errorContainer.classList.contains('hidden')) {
            errorContainer.classList.add('hidden');
            errorContainer.innerHTML = '';
        }
    }
});

// used for showing dark background for white favicons
function checkFaviconBrightness(img) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = img.naturalWidth;
    canvas.height = img.naturalHeight;
    ctx.drawImage(img, 0, 0);

    const data = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
    let whitePixels = 0, visiblePixels = 0;

    for (let i = 0; i < data.length; i += 4) {
        if (data[i + 3] > 25) { // alpha > 25
            visiblePixels++;
            if (data[i] > 240 && data[i + 1] > 240 && data[i + 2] > 240) {
                whitePixels++;
            }
        }
    }

    if (visiblePixels > 0 && whitePixels / visiblePixels > 0.9) {
        img.classList.add('bg-gray-500', 'p-1');
    }
}
