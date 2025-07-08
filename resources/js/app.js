import './echo';
import htmx from 'htmx.org';
import Notify from 'simple-notify'
import 'simple-notify/dist/simple-notify.css'
import Alpine from 'alpinejs'

window.htmx = htmx;
window.Alpine = Alpine

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

document.addEventListener('DOMContentLoaded', () => {
    Coloris({
        el: '#color',
        theme: 'pill',
        onChange: (color, input) => {
            document.getElementById('color-res').style.backgroundColor = color;
        }
   });
});
