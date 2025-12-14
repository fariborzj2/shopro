<!-- Purchase Modal Partial -->
<div x-data="purchaseModal()"
     @open-purchase-modal.window="open($event.detail)"
     class="relative z-[70]"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true"
     x-show="isOpen"
     x-cloak>

    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="isOpen"
                 @click.away="close()"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start gap-6">
                         <!-- Product Image -->
                         <div class="w-full sm:w-1/3 mb-4 sm:mb-0">
                            <img :src="product.image || 'https://placehold.co/400x400'" class="w-full rounded-xl border border-slate-200 object-cover aspect-square">
                        </div>

                        <div class="w-full sm:w-2/3 text-right">
                             <div class="flex justify-between items-start">
                                <h3 class="text-xl font-bold leading-6 text-slate-900 mb-2" id="modal-title" x-text="product.name"></h3>
                                <button @click="close()" class="text-slate-400 hover:text-slate-500">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <p class="text-sm text-slate-500 mb-4 leading-relaxed" x-text="product.description"></p>

                            <div class="flex items-center gap-2 mb-6">
                                <span class="text-2xl font-bold text-slate-900" x-text="Number(product.price).toLocaleString() + ' تومان'"></span>
                                <span x-show="product.old_price" class="text-sm text-slate-400 line-through" x-text="Number(product.old_price).toLocaleString()"></span>
                            </div>

                            <!-- Custom Fields Placeholder (If API supports) -->
                            <!-- Simplifying for design: Quantity -->
                             <div class="mb-6">
                                <label class="block text-sm font-medium text-slate-700 mb-2">تعداد</label>
                                <div class="flex items-center border border-slate-200 rounded-lg w-fit">
                                    <button @click="quantity > 1 ? quantity-- : null" class="p-2 text-slate-500 hover:text-primary-600"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg></button>
                                    <input type="text" x-model="quantity" readonly class="w-12 text-center border-none focus:ring-0 text-slate-900 font-bold p-0">
                                    <button @click="quantity++" class="p-2 text-slate-500 hover:text-primary-600"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg></button>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <?php if(isset($_SESSION['user_id'])): ?>
                                <button @click="submitOrder" :disabled="loading" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-primary-500/20 flex items-center justify-center gap-2">
                                     <span x-show="loading" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                                     <span x-text="loading ? 'در حال پردازش...' : 'تایید و پرداخت'"></span>
                                </button>
                                <?php else: ?>
                                <button @click="close(); $dispatch('open-auth-modal')" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-slate-900/20">
                                    برای خرید وارد شوید
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function purchaseModal() {
        return {
            isOpen: false,
            product: {},
            quantity: 1,
            loading: false,

            open(product) {
                this.product = product;
                this.quantity = 1;
                this.isOpen = true;
            },

            close() {
                this.isOpen = false;
            },

            async submitOrder() {
                this.loading = true;
                try {
                    // Placeholder for payment logic matching PaymentController
                    const formData = new FormData();
                    formData.append('product_id', this.product.id);
                    // formData.append('custom_fields', ...); // Logic needed for custom fields

                    // Since custom fields are complex and require API fetch,
                    // this demo assumes simple redirect or API call.
                    // For now, let's simulate a call or redirect to payment start.

                    const payload = {
                        product_id: this.product.id,
                        custom_fields: {} // Gather fields if any
                    };

                    const response = await fetch('/api/payment/start', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (result.success && result.redirect_url) {
                        window.location.href = result.redirect_url;
                    } else {
                        alert(result.error || 'خطا در ثبت سفارش');
                    }

                } catch (e) {
                    alert('خطای ارتباط با سرور');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
