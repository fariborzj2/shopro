document.addEventListener('alpine:init', () => {
    Alpine.store('errorModal', {
        isOpen: false,
        title: 'خطا',
        message: '',
        openModal(title, message) {
            this.title = title || 'خطا';
            this.message = message || 'یک خطای غیرمنتظره رخ داده است.';
            this.isOpen = true;
        },
        closeModal() {
            this.isOpen = false;
        }
    });
});

function showErrorModal(message, title = 'خطا') {
    const store = Alpine.store('errorModal');
    if (store) {
        store.openModal(title, message);
    } else {
        // Fallback for timing issues
        window.addEventListener('alpine:initialized', () => {
            Alpine.store('errorModal').openModal(title, message);
        }, { once: true });
    }
}

window.alert = showErrorModal;
