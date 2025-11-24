<?php include 'header.tpl'; ?>

<main class="flex-grow bg-gray-50 py-16" x-data="categoryApp()">
    <div class="container">
        <!-- Category Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">
                <?= htmlspecialchars($category->name_fa) ?>
            </h1>
            <p class="text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed">
                <?= htmlspecialchars($category->description) ?>
            </p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <template x-for="product in products" :key="product.id">
                <article class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col overflow-hidden">
                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-200">
                        <img
                            :src="product.imageUrl"
                            :alt="product.name"
                            class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h2 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors" x-text="product.name"></h2>
                        <div class="mt-auto flex items-end justify-between">
                            <span class="text-xl font-black text-primary-600" x-text="new Intl.NumberFormat('fa-IR').format(product.price) + ' تومان'"></span>
                        </div>
                        <button
                            @click="showReviews(product.id)"
                            class="mt-4 w-full py-2 px-4 rounded-xl bg-gray-50 text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium text-sm transition-colors border border-gray-200 hover:border-primary-100"
                        >
                            مشاهده و ثبت نظرات
                        </button>
                    </div>
                </article>
            </template>
        </div>

        <!-- Reviews Modal -->
        <div
            x-show="showModal"
            class="relative z-50"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
            x-cloak
        >
            <div
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"
            ></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        x-show="showModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        @click.outside="showModal = false"
                        class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200 max-h-[85vh] flex flex-col"
                    >
                        <!-- Header -->
                        <div class="bg-white px-4 py-4 sm:px-6 border-b border-gray-100 flex justify-between items-center sticky top-0 z-10">
                            <h3 class="text-lg font-bold text-gray-900">
                                نظرات کاربران: <span x-text="selectedProduct?.name" class="text-primary-600"></span>
                            </h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <!-- List -->
                            <div class="space-y-6 mb-8">
                                <template x-if="selectedProduct?.reviews?.length > 0">
                                    <template x-for="review in selectedProduct.reviews" :key="review.id">
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="font-bold text-gray-900" x-text="review.user_name"></span>
                                                <div class="flex items-center text-yellow-400 text-sm">
                                                    <span class="font-bold text-gray-900 ml-1" x-text="review.rating"></span>
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                </div>
                                            </div>
                                            <p class="text-gray-600 text-sm leading-relaxed" x-text="review.comment"></p>

                                            <template x-if="review.admin_reply">
                                                <div class="mt-3 pr-3 border-r-2 border-primary-500 bg-primary-50 p-3 rounded-lg">
                                                    <p class="text-xs font-bold text-primary-800 mb-1">پاسخ مدیر:</p>
                                                    <p class="text-sm text-gray-700" x-text="review.admin_reply"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="!selectedProduct?.reviews || selectedProduct.reviews.length === 0">
                                    <p class="text-center text-gray-500 py-4">هنوز نظری برای این محصول ثبت نشده است.</p>
                                </template>
                            </div>

                            <!-- Form -->
                            <div class="border-t border-gray-100 pt-6">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <h4 class="font-bold text-gray-900 mb-4">ثبت دیدگاه جدید</h4>
                                    <form action="/reviews/store" method="POST">
                                        <input type="hidden" name="product_id" :value="selectedProduct?.id">

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 mb-1">نام</label>
                                                <input type="text" value="<?= $_SESSION['user_name'] ?? '' ?>" readonly class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 mb-1">موبایل</label>
                                                <input type="text" value="<?= $_SESSION['user_mobile'] ?? '' ?>" readonly class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">امتیاز شما</label>
                                            <div class="flex items-center gap-1 flex-row-reverse justify-end">
                                                <template x-for="i in 5">
                                                    <button type="button" @click="rating = i" class="text-2xl transition-colors focus:outline-none transform hover:scale-110" :class="i <= rating ? 'text-yellow-400' : 'text-gray-200'">★</button>
                                                </template>
                                            </div>
                                            <input type="hidden" name="rating" x-model="rating" required>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">دیدگاه</label>
                                            <textarea name="comment" rows="3" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3" placeholder="نظر خود را بنویسید..."></textarea>
                                        </div>

                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl bg-primary-600 px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-primary-700 transition-colors">
                                            ثبت نظر
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="bg-gray-50 rounded-xl p-6 text-center">
                                        <p class="text-gray-600 text-sm mb-3">برای ثبت نظر باید وارد حساب کاربری خود شوید.</p>
                                        <button @click.prevent="$dispatch('open-auth-modal'); showModal = false" class="text-primary-600 font-bold hover:underline">
                                            ورود به حساب کاربری
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function categoryApp() {
    return {
        products: <?= json_encode(json_decode($store_data)->products) ?>,
        showModal: false,
        selectedProduct: null,
        rating: 5,

        showReviews(productId) {
            this.selectedProduct = this.products.find(p => p.id === productId);
            this.rating = 5;
            this.showModal = true;
        }
    }
}
</script>

<!-- JSON-LD Schema for CollectionPage -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "<?= htmlspecialchars($category->name_fa) ?>",
    "description": "<?= htmlspecialchars($category->description) ?>",
    "mainEntity": {
        "@type": "ItemList",
        "itemListElement": [
            <?php foreach (json_decode($store_data)->products as $index => $product) : ?>
            {
                "@type": "Product",
                "name": "<?= $product->name ?>",
                "image": "<?= $product->imageUrl ?>",
                "offers": {
                    "@type": "Offer",
                    "price": "<?= $product->price ?>",
                    "priceCurrency": "IRR"
                }
            }<?= $index < count(json_decode($store_data)->products) - 1 ? ',' : '' ?>
            <?php endforeach; ?>
        ]
    }
}
</script>

<?php include 'footer.tpl'; ?>
