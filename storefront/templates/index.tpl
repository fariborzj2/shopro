<?php include 'header.tpl'; ?>

<!-- App Container -->
<div
    x-data="store(<?php echo $store_data; ?>)"
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

    <section class="bg-white rounded-2xl shadow-sm p-8 my-10 text-center">
        <h2 class="text-2xl font-bold text-gray-900">خرید سریع و آسان</h2>
        <p class="mt-2 text-gray-500">بدون معطلی محصول مورد نظر خود را پیدا کنید.</p>
        <div class="mt-6">
            <a href="#products" class="bg-gray-900 text-white px-10 py-3 rounded-lg font-bold hover:bg-gray-800 transition-transform transform hover:scale-105">مشاهده همه محصولات</a>
        </div>
    </section>

    <section id="products" class="py-10">
        <div class="mb-8">
            <div class="sm:border-b sm:border-gray-200">
                <nav class="-mb-px flex space-x-4 space-x-reverse overflow-x-auto no-scrollbar" aria-label="Tabs">
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
                        <button class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity md:opacity-100 md:static">مشاهده</button>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <!-- Modal / Bottom Sheet -->
    <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="isModalOpen = false"></div>
    <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-end md:items-center justify-center">
        <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full md:translate-y-0 md:scale-95" x-transition:enter-end="opacity-100 translate-y-0 md:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 md:scale-100" x-transition:leave-end="opacity-0 translate-y-full md:translate-y-0 md:scale-95" @click.outside="isModalOpen = false" class="bg-white w-full max-w-lg rounded-t-2xl md:rounded-2xl shadow-xl transform">
             <div class="p-8 text-center" x-if="selectedProduct">
                <img :src="selectedProduct.imageUrl" :alt="selectedProduct.name" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="text-2xl font-bold" x-text="selectedProduct.name"></h3>
                <p class="text-xl text-gray-700 mt-2" x-text="selectedProduct.price + ' تومان'"></p>
                <div class="mt-6 space-y-3">
                    <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">پرداخت</button>
                    <button @click="isModalOpen = false" class="w-full bg-gray-200 text-gray-800 py-3 rounded-lg font-semibold">انصراف</button>
                </div>
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
        isModalOpen: false,
        init() {
            this.categories = data.categories || [];
            this.products = data.products || [];
        },
        get filteredProducts() {
            if (this.activeCategory === 'all') return this.products;
            return this.products.filter(p => p.category == this.activeCategory); // Use '==' for loose comparison as category IDs might be numbers or strings
        },
        setActiveCategory(categoryId) { this.activeCategory = categoryId; },
        selectProduct(product) {
            this.selectedProduct = product;
            this.isModalOpen = true;
        }
    }
}
</script>

<?php include 'footer.tpl'; ?>
