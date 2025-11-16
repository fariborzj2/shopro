<?php include 'header.tpl'; ?>

<!-- App Container -->
<div
    x-data="store(<?php echo htmlspecialchars($store_data, ENT_QUOTES, 'UTF-8'); ?>)"
    x-init="init()"
    x-cloak
>
    <!-- Hero, Quick Purchase, Category Tabs & Product Grid sections -->
    <section class="text-center py-20">
        <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 leading-tight">تجربه خریدی بی‌نظیر</h1>
        <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600">محصولات با کیفیت و ارسال سریع را با ما تجربه کنید.</p>
        <div class="mt-8">
            <a href="#products" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors">شروع خرید</a>
        </div>
    </section>

    <section id="products" class="py-10">
        <div class="mb-8">
            <div class="sm:border-b sm:border-gray-200">
                <nav class="-mb-px flex space-x-4 space-x-reverse overflow-x-auto no-scrollbar" aria-label="Tabs">
                    <a href="#" @click.prevent="setActiveCategory('all')" :class="{ 'border-blue-500 text-blue-600': activeCategory === 'all', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeCategory !== 'all' }" class="whitespace-nowrap py-3 px-4 border-b-2 font-semibold text-sm transition-colors">همه</a>
                    <template x-for="category in categories" :key="category.id">
                        <a href="#" @click.prevent="setActiveCategory(category.id)" :class="{ 'border-blue-500 text-blue-600': activeCategory === category.id, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeCategory !== category.id }" class="whitespace-nowrap py-3 px-4 border-b-2 font-semibold text-sm transition-colors" x-text="category.name"></a>
                    </template>
                </nav>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <template x-for="product in filteredProducts" :key="product.id">
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
             <div class="p-8">
                <template x-if="selectedProduct">
                    <div>
                        <img :src="selectedProduct.imageUrl" :alt="selectedProduct.name" class="w-full h-48 object-cover rounded-lg mb-4">
                        <h3 class="text-2xl font-bold text-center" x-text="selectedProduct.name"></h3>
                        <form @submit.prevent="submitOrder" id="purchaseForm" class="mt-6">
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
                </template>
             </div>
        </div>
    </div>
</div>

<script>
function store(data) {
    return {
        categories: [],
        products: [],
        activeCategory: 'all',
        selectedProduct: null,
        customFields: [],
        isModalOpen: false,
        isUserLoggedIn: false,

        init() {
            this.categories = data.categories || [];
            this.products = data.products || [];
            this.isUserLoggedIn = data.isUserLoggedIn || false;
        },
        get filteredProducts() {
            if (this.activeCategory === 'all') return this.products;
            return this.products.filter(p => p.category == this.activeCategory);
        },
        setActiveCategory(categoryId) { this.activeCategory = categoryId; },
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
            const form = document.getElementById('purchaseForm');
            const formData = new FormData(form);
            const customFieldsData = {};
            for (const [key, value] of formData.entries()) {
                customFieldsData[key] = value;
            }

            fetch('/api/payment/start', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    product_id: this.selectedProduct.id,
                    custom_fields: customFieldsData
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 && body.payment_url) {
                    window.location.href = body.payment_url;
                } else {
                    alert('خطا: ' + (body.error || 'امکان اتصال به درگاه پرداخت وجود ندارد.'));
                }
            });
        }
    }
}
</script>

<?php include 'footer.tpl'; ?>
