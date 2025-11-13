<?php include 'header.tpl'; ?>

<!-- App Container -->
<div
    x-data="categoryStore(<?php echo htmlspecialchars($store_data, ENT_QUOTES, 'UTF-8'); ?>)"
    x-init="init()"
    x-cloak
>
    <!-- Category Header -->
    <section class="text-center py-12 bg-gray-50">
        <h1 class="text-4xl font-extrabold text-gray-900" x-text="category.name"></h1>
        <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600" x-text="category.description"></p>
    </section>

    <!-- Product Grid -->
    <section id="products" class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <template x-for="product in products" :key="product.id">
                <div @click="selectProduct(product)" class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow cursor-pointer group">
                    <img :src="product.imageUrl" :alt="product.name" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800" x-text="product.name"></h3>
                        <p class="mt-2 text-gray-600" x-text="product.price + ' تومان'"></p>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <!-- Purchase Modal -->
    <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="isModalOpen = false"></div>
    <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-end md:items-center justify-center">
        <div
            x-show="isModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full md:translate-y-0 md:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
            x-transition:leave-end="opacity-0 translate-y-full md:translate-y-0 md:scale-95"
            @click.outside="isModalOpen = false"
            class="bg-white w-full max-w-lg rounded-t-2xl md:rounded-2xl shadow-xl transform"
        >
             <div class="p-8" x-if="selectedProduct">
                <img :src="selectedProduct.imageUrl" :alt="selectedProduct.name" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="text-2xl font-bold text-center" x-text="selectedProduct.name"></h3>
                <form @submit.prevent="submitOrder" class="mt-6">
                    <div class="space-y-4 text-right">
                        <template x-for="field in customFields" :key="field.id">
                            <div>
                                <label :for="'field_' + field.id" class="block text-sm font-medium text-gray-700 mb-1" x-text="field.label"></label>

                                <input x-if="['text', 'number', 'date', 'color'].includes(field.type)" :type="field.type" :name="field.name" :id="'field_' + field.id" :placeholder="field.placeholder" :required="field.is_required" class="w-full border-gray-300 rounded-md">

                                <textarea x-if="field.type === 'textarea'" :name="field.name" :id="'field_' + field.id" :placeholder="field.placeholder" :required="field.is_required" class="w-full border-gray-300 rounded-md"></textarea>

                                <select x-if="field.type === 'select'" :name="field.name" :id="'field_' + field.id" :required="field.is_required" class="w-full border-gray-300 rounded-md">
                                    <template x-for="option in field.options" :key="option.value">
                                        <option :value="option.value" x-text="option.label"></option>
                                    </template>
                                </select>

                                <!-- Add more field types like radio/checkbox if needed -->

                            </div>
                        </template>
                    </div>
                    <div class="mt-8 space-y-3">
                        <button type="submit" class="w-full bg-blue-600 text-white py-3">پرداخت</button>
                        <button @click="isModalOpen = false" type="button" class="w-full bg-gray-200 py-3">انصراف</button>
                    </div>
                </form>
             </div>
        </div>
    </div>
</div>

<script>
function categoryStore(data) {
    return {
        category: {},
        products: [],
        selectedProduct: null,
        customFields: [],
        isModalOpen: false,
        isUserLoggedIn: false,

        init() {
            this.category = data.category || {};
            this.products = data.products || [];
            this.isUserLoggedIn = data.isUserLoggedIn || false;
        },
        selectProduct(product) {
            if (!this.isUserLoggedIn) {
                window.dispatchEvent(new CustomEvent('open-auth-modal'));
                return;
            }

            this.selectedProduct = product;
            this.isModalOpen = true;

            fetch(`/api/product-details/${product.id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        this.isModalOpen = false;
                    } else {
                        this.customFields = data.custom_fields;
                    }
                });
        },
        submitOrder() {
            alert('سفارش شما ثبت شد!');
            this.isModalOpen = false;
        }
    }
}
</script>

<?php include 'footer.tpl'; ?>
