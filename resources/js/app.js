import htmx from 'htmx.org';
window.htmx = htmx;

import Notify from 'simple-notify'
import 'simple-notify/dist/simple-notify.css'

class Modal {
    constructor() {
        this.modal = document.getElementById('modal-container');
        this.dialog = document.getElementById('dialog');
        this.backdrop = document.getElementById('modal-backdrop');
        if (this.modal) {
            this.modal.addEventListener('keydown', (event) => {
                if (event.key == 'Escape') this.hide();
            });
        }
    }

    toggleModal() {
        this.modal.classList.toggle('hidden');
        this.modal.setAttribute('aria-hidden', 'false');
        this.toggleBackdrop();
        this.modal.focus();
    }

    toggleBackdrop() {
        this.backdrop.classList.toggle('hidden');
    }

    clearDialog () {
        this.dialog.innerHTML = '';
        this.modal.setAttribute('aria-hidden', 'true');
        this.modal.blur();
    }

    setupCloseButtons() {
        if (this.modal) {
            this.modal.querySelectorAll('[data-hide-modal="true"]')
                    .forEach(e => e.addEventListener('click', _ => this.hide()));
        }
    }

    show() {
        this.toggleModal();
        this.setupCloseButtons();
    }

    hide() {
        this.toggleModal();
        this.clearDialog();
    }
}

window.modal = new Modal;

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
        type: type,
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

document.addEventListener('htmx:afterSwap', function(event) {
  const suggestionsContainer = document.getElementById('suggestions-container');

    if (suggestionsContainer) {
        if (event.target === suggestionsContainer || suggestionsContainer.contains(event.target) || event.target.contains(suggestionsContainer)) {
            addSearchbarKeyboardAccessibility();
        }
    }
});

function addSearchbarKeyboardAccessibility() {
    const elements = {
        input: document.querySelector('input[name="search"]'),
        container: document.getElementById('suggestions-container'),
        items: document.getElementById('suggestions-container').querySelectorAll('ul > li'),
    }

    if (!elements.container) {
        console.warn('Suggestions container not found');
        return;
    }

    elements.input.addEventListener('keydown', event => {
        if (event.key == "ArrowDown") {
            event.preventDefault();
            elements.items[0].focus();
        }
    });

    elements.items.forEach((item, index) => {
        item.addEventListener('keydown', event => {
            switch(event.key) {
                case 'Enter':
                case ' ':
                    event.preventDefault();
                    item.click();
                    break;

                case 'ArrowDown':
                    event.preventDefault();
                    const nextElement = getNextFocusableElement(item);
                    if (nextElement) nextElement.focus();
                    break;

                case 'ArrowUp':
                    event.preventDefault();
                    const prevElement = getPreviousFocusableElement(item);
                    if (prevElement) prevElement.focus();
                    break;

                case 'Escape':
                    event.preventDefault();
                    hideSuggestions();
                    break;
            }
        });
    });

    function getNextFocusableElement(currItem) {
        const elementsArray = Array.from(elements.items);
        const currIndex = elementsArray.indexOf(currItem);

        if (currIndex < elementsArray.length - 1) {
            return elementsArray[currIndex + 1];
        }

        return elementsArray[0];
    }

    function getPreviousFocusableElement(currItem) {
        const elementsArray = Array.from(elements.items);
        const currIndex = elementsArray.indexOf(currItem);

        if (currIndex > 0) {
            return elementsArray[currIndex - 1];
        }

        return elementsArray[elementsArray.length - 1];
    }
    function hideSuggestions() {
        if (elements.container) {
            if (elements.input) {
                elements.input.focus();
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const userMenuButton = document.getElementById('userMenuBtn');
    if (userMenuButton) {
        const dropdown = document.getElementById('userDropdown');
        userMenuButton.addEventListener('click', () => dropdown.classList.toggle('hidden'));

        document.addEventListener('click', (event) => {
            if (!dropdown.classList.contains('hidden') && !dropdown.contains(event.target) && event.target !== userMenuButton) {
                dropdown.classList.add('hidden');
            }
        });
    }

    Coloris({
        el: '#color',
        theme: 'pill',
        onChange: (color, input) => {
            document.getElementById('color-res').style.backgroundColor = color;
        }
    });
});
