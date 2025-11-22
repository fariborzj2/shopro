
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
            } else if (event.key === 'Backspace' && !this.inputValue && this.items.length > 0) {
                this.items.pop();
            }
        },

        fetchSuggestions(query) {
            // Only fetch if it's the 'Tags' field which has an API. Meta keywords might just be local?
            // Prompt says "When reached 3 chars, suggest PREVIOUS tags".
            // So both could potentially use the API if we want suggestions.
            // Let's assume the 'config' tells us if we should fetch.
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
             // If it's an object (existing tag), we might want to send ID or Name.
             // If the controller expects IDs for existing tags and Strings for new ones, we send accordingly.
             // However, for simplicity and handling "new tags" seamlessly, often sending names for everything
             // and letting controller resolve/create is easier.
             // OR send ID if available, Name if not.
             // Let's send the Name if it's an object, or the string itself.
             // Actually, standardizing on Names for submission is usually safer if the backend syncs by name.
             // But `syncTags` typically uses IDs.
             // Let's see: `syncTags` in `BlogPost` uses `tag_id`.
             // So for existing tags, we MUST send IDs.
             // For new tags, we send Name.
             // So the controller needs to distinguish.
             // Let's send:
             // If object (has ID): return ID (as string/int)
             // If string: return "new:TAGNAME" or just "TAGNAME" and let controller figure it out (if is_numeric check?)
             // Using a mixed array is tricky in PHP `syncTags` if it expects IDs.
             // I will modify the controller to handle this.

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
