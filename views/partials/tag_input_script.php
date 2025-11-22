
<script>
function tagInput(config) {
    return {
        items: config.initialTags || [], // Array of strings or objects
        inputValue: '',
        suggestions: [],
        isFocused: false,
        fieldName: config.fieldName || 'tags[]',
        noPrefix: config.noPrefix || false,

        init() {
            this.$watch('inputValue', (value) => {
                if (value.length >= 3) {
                    this.fetchSuggestions(value);
                } else {
                    this.suggestions = [];
                }
            });
        },

        addTag(item) {
            // If item is string (from enter/comma)
            let tagValue = typeof item === 'string' ? item.trim() : item.name;

            if (tagValue && !this.items.some(t => (typeof t === 'string' ? t : t.name) === tagValue)) {
                if (typeof item === 'object') {
                     // It's an existing tag object
                     this.items.push(item);
                } else {
                     // It's a new string tag
                     this.items.push(tagValue);
                }
            }
            this.inputValue = '';
            this.suggestions = [];
        },

        removeTag(index) {
            this.items.splice(index, 1);
        },

        handleKeydown(event) {
            if (['Enter', ','].includes(event.key)) {
                event.preventDefault();
                if (this.inputValue) {
                    this.addTag(this.inputValue);
                }
            } else if (event.key === 'Backspace') {
                if (!this.inputValue && this.items.length > 0) {
                    this.items.pop();
                }
            }
        },

        handlePaste(event) {
            event.preventDefault();
            let paste = (event.clipboardData || window.clipboardData).getData('text');
            if (paste) {
                let tags = paste.split(',');
                tags.forEach(tag => {
                    this.addTag(tag);
                });
            }
        },

        fetchSuggestions(query) {
            // Only fetch if it's the 'Tags' field which has an API. Meta keywords might just be local?
            if (!config.fetchUrl) return;

            fetch(`${config.fetchUrl}?q=${query}`)
                .then(res => res.json())
                .then(data => {
                    this.suggestions = data;
                })
                .catch(err => console.error(err));
        },

        // Helper to get value for hidden input
        getItemValue(item) {
             if (typeof item === 'object' && item.id) {
                 return item.id; // It's an ID
             }
             if (this.noPrefix) {
                 return item;
             }
             return 'new:' + item; // Flag as new
        }
    }
}
</script>
