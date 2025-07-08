<div x-data="{
        focusedIndex: -1,
        suggestions: [],

        init() {
            this.suggestions = [...this.$el.querySelectorAll('[role=option]')];
        },

        handleKeydown(event) {
            switch(event.key) {
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
    }" @keydown="handleKeydown" id="suggestions-container" x-ref="suggestions"
    class="absolute bottom-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mb-2 z-50"
    role="listbox" aria-label="Search suggestions">
    <div class="p-2">
        <div class="space-y-1 mb-1.5">
            @if (count($tags))
            <div class="text-xs text-gray-500 font-medium mb-2">Tags</div>
            @foreach ($tags as $index => $tag)
            <div class="px-3 py-2 hover:bg-orange-100 focus:bg-orange-100 rounded cursor-pointer text-sm" tabindex="0"
                role="option" @mouseenter="setFocusIndex({{ $loop->index }})"
                hx-get="{{ route('dashboard.search.tag', $tag) }}" hx-trigger="click, keyup[key=='Enter']"
                hx-target="#filters" hx-swap="afterbegin" hx-on::after-request="
                            document.getElementById('suggestions-container').remove();
                            document.getElementById('search').value = '';
                            htmx.trigger('#bookmarks', 'loadBookmarks');
                        " :aria-selected="focusedIndex === {{ $loop->index }}">
                {{ $tag->name }}
            </div>
            @endforeach
            @endif
            @if (count($sites))
            <div class="text-xs text-gray-500 font-medium mb-2">Sites</div>
            @foreach ($sites as $site)
            <div class="px-3 py-2 hover:bg-orange-100 focus:bg-orange-100 rounded cursor-pointer text-sm" tabindex="0"
                role="option" @mouseenter="setFocusIndex({{ count($tags) + $loop->index }})"
                hx-get="{{ route('dashboard.search.site', $site) }}" hx-trigger="click, keyup[key=='Enter']"
                hx-target="#filters" hx-swap="afterbegin" hx-on::after-request="
                            document.getElementById('suggestions-container').innerHTML = '';
                            document.getElementById('search').value = '';
                            htmx.trigger('#bookmarks', 'loadBookmarks');
                        " :aria-selected="focusedIndex === {{ count($tags) + $loop->index }}">
                {{ $site }}
            </div>
            @endforeach
            @endif
        </div>
    </div>
    <!-- arrow pointing down -->
    <div class="absolute -bottom-2 left-6 w-4 h-4 bg-white border-r border-b border-gray-200 transform rotate-45"></div>
</div>
