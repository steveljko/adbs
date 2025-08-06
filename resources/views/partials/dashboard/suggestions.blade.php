<div
    @keydown="handleKeydown"
    aria-label="Search suggestions"
    class="absolute bottom-full left-0 right-0 z-50 mb-2 rounded-lg border border-gray-200 bg-white shadow-lg"
    id="suggestions-container"
    role="listbox"
    x-data="{
        focusedIndex: -1,
        suggestions: [],

        init() {
            this.suggestions = [...this.$el.querySelectorAll('[role=option]')];
        },

        handleKeydown(event) {
            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    if (this.focusedIndex < this.suggestions.length - 1) {
                        this.focusedIndex++;
                        this.updateFocus();
                    } else {
                        this.focusedIndex = -1;
                        this.updateFocus();
                        document.getElementById('search').focus();
                    }
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    if (this.focusedIndex > 0) {
                        this.focusedIndex--;
                        this.updateFocus();
                    } else if (this.focusedIndex === 0) {
                        this.focusedIndex = -1;
                        this.updateFocus();
                        document.getElementById('search').focus();
                    } else {
                        this.focusedIndex = this.suggestions.length - 1;
                        this.updateFocus();
                    }
                    break;
                case 'Enter':
                    if (this.focusedIndex >= 0) {
                        event.preventDefault();
                        this.suggestions[this.focusedIndex].click();
                    }
                    break;
                case 'Escape':
                    this.focusedIndex = -1;
                    this.updateFocus();
                    document.getElementById('search').focus();
                    break;
            }
        },

        updateFocus() {
            this.suggestions.forEach((el, index) => {
                if (index === this.focusedIndex) {
                    el.classList.add('focus:bg-orange-100');
                    el.focus();
                } else {
                    el.classList.remove('focus:bg-orange-100');
                }
            });
        },

        setFocusIndex(index) {
            this.focusedIndex = index;
            this.updateFocus();
        }
    }"
    x-ref="suggestions"
>
    <div class="p-2">
        <div class="mb-1.5 space-y-1">
            @if (count($tags))
                <div class="mb-2 text-xs font-medium text-gray-500">Tags</div>
                @foreach ($tags as $index => $tag)
                    <div
                        :aria-selected="focusedIndex === {{ $loop->index }}"
                        @mouseenter="setFocusIndex({{ $loop->index }})"
                        class="cursor-pointer rounded px-3 py-2 text-sm hover:bg-orange-100 focus:bg-orange-100"
                        hx-get="{{ route('dashboard.search.tag', $tag) }}"
                        hx-on::after-request="
                            document.getElementById('suggestions-container').remove();
                            document.getElementById('search').value = '';
                            htmx.trigger('#bookmarks-container', 'loadBookmarks');
                        "
                        hx-swap="afterbegin"
                        hx-target="#filters"
                        hx-trigger="click, keyup[key=='Enter']"
                        role="option"
                        tabindex="0"
                    >
                        {{ $tag->name }}
                    </div>
                @endforeach
            @endif
            @if (count($sites))
                <div class="mb-2 text-xs font-medium text-gray-500">Sites</div>
                @foreach ($sites as $site)
                    <div
                        :aria-selected="focusedIndex === {{ count($tags) + $loop->index }}"
                        @mouseenter="setFocusIndex({{ count($tags) + $loop->index }})"
                        class="cursor-pointer rounded px-3 py-2 text-sm hover:bg-orange-100 focus:bg-orange-100"
                        hx-get="{{ route('dashboard.search.site', $site) }}"
                        hx-on::after-request="
                            document.getElementById('suggestions-container').innerHTML = '';
                            document.getElementById('search').value = '';
                            htmx.trigger('#bookmarks-container', 'loadBookmarks');
                        "
                        hx-swap="afterbegin"
                        hx-target="#filters"
                        hx-trigger="click, keyup[key=='Enter']"
                        role="option"
                        tabindex="0"
                    >
                        {{ $site }}
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <!-- arrow pointing down -->
    <div class="absolute -bottom-2 left-6 h-4 w-4 rotate-45 transform border-b border-r border-gray-200 bg-white"></div>
</div>
