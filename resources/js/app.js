import htmx from 'htmx.org';
window.htmx = htmx;

class Modal {
    constructor() {
        this.modal = document.getElementById('modal-container');
        this.dialog = document.getElementById('dialog');
        this.backdrop = document.getElementById('modal-backdrop');
        this.modal.addEventListener('keydown', (event) => {
            if (event.key == 'Escape') this.hide();
        });
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

document.addEventListener('htmx:responseError', function (event) {
    const errors = JSON.parse(event.detail.xhr.response).errors;

    for (const [field, messages] of Object.entries(errors)) {
        const container = document.querySelector(`#${field}-error`);
        container.classList.remove('hidden');
        container.innerHTML = messages[0];
    }
});

function initializeTagInput() {
    // Elements
    const elements = {
        input: document.getElementById('add-tag-input'),
        tags: document.getElementById('tags'),
        suggestions: document.getElementById('suggestions'),
    }

    if (!elements.input && !elements.tags) {
        return;
    }

    // Data
    // availableTags: list of tags suggested for user
    // selecetedTags: list of tags that user has already selected
    const data = {
        availableTags: JSON.parse(elements.tags.getAttribute('data-tags').replace(/'/g, '"')),
        selectedTags: [],
    }

    elements.input.addEventListener('input', (el) => {
        elements.suggestions.classList.remove('hidden');
        elements.suggestions.innerHTML = '';

        // Close suggestions if input is empty
        if (!el.target.value) {
            elements.suggestions.classList.add('hidden');
            return;
        }

        const filteredTags = data.availableTags.filter(tag => tag.toLowerCase().includes(el.target.value.toLowerCase()));

        // If the entered value is not in available or selected tags, suggest creating a new tag
        if (!data.availableTags.includes(el.target.value) && !data.selectedTags.includes(el.target.value)) {
            elements.suggestions.appendChild(Object.assign(document.createElement('div'), {
                textContent: `Create new tag '${el.target.value}'`,
                className: 'py-2 px-4 hover:bg-blue-100 cursor-pointer',
                onclick: () => addTag(el.target.value)
            }));
        }

        if (filteredTags.length > 0) {
            filteredTags.forEach(tag => {
                // Add item in suggestion
                elements.suggestions.appendChild(Object.assign(document.createElement('div'), {
                    textContent: tag,
                    className: 'py-2 px-4 hover:bg-blue-100 cursor-pointer',
                    onclick: () => addTag(tag),
                }));
            });
        }

        // If no filtered tags are found and the current input value is already selected, hide suggestions
        if (filteredTags.length === 0 && data.selectedTags.includes(el.target.value)) elements.suggestions.classList.add('hidden');
    });

    function addTag(tag) {
        const tagElement = Object.assign(document.createElement('span'), {
            className: 'bg-orange-500 text-white text-sm rounded-full px-3 py-1 mr-2 mb-2 flex items-center justify-between',
            textContent: tag,
        });

        // Create a hidden input field to include a tag in the form submission
        tagElement.appendChild(Object.assign(document.createElement('input'), {
            type: 'hidden',
            name: 'tags[]',
            value: tag
        }));

        // Delete button for removing tag
        tagElement.appendChild(Object.assign(document.createElement('button'), {
            innerHTML: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>`,
            className: 'ml-2 text-white bg-red-500 rounded-full w-5 h-5 flex items-center justify-center cursor-pointer',
            type: 'button',
            onclick: () => elements.tags.removeChild(tagElement),
        }));

        elements.tags.appendChild(tagElement);

        if (data.availableTags.includes(tag)) {
            data.availableTags = data.availableTags.filter(t => t !== tag);
            data.selectedTags.push(tag);
        }

        elements.input.value = ''; // clear field on successful add
        elements.suggestions.classList.add('hidden'); // hide suggestion
    }
}

htmx.onLoad(() => {
    initializeTagInput();
});
