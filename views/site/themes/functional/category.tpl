<?php include __DIR__ . '/header.tpl'; ?>

<main class="w-full bg-slate-50 min-h-screen py-8">
    <div class="container mx-auto px-4" x-data="categoryStore(<?php echo htmlspecialchars(json_encode($store_data ?? [])); ?>)">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">فروشگاه</h1>
                <p class="text-slate-500">مشاهده و خرید محصولات با بهترین قیمت</p>
            </div>

             <!-- Sorting -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">مرتب‌سازی:</span>
                <select x-model="sortBy" class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2.5 outline-none">
                    <option value="newest">جدیدترین</option>
                    <option value="price_asc">ارزان‌ترین</option>
                    <option value="price_desc">گران‌ترین</option>
                    <option value="popular">پرفروش‌ترین</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <aside class="hidden lg:block space-y-6">
                <!-- Categories -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">دسته‌بندی‌ها</h3>
                    <div class="space-y-2">
                        <template x-for="cat in categories" :key="cat.id">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" :value="cat.id" x-model="selectedCategories" class="w-5 h-5 rounded border-slate-300 text-primary-600 focus:ring-primary-500 transition-colors">
                                <span class="text-slate-600 group-hover:text-primary-600 transition-colors text-sm" x-text="cat.name"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                     <h3 class="font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">محدوده قیمت</h3>
                     <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                         <span x-text="Number(minPrice).toLocaleString() + ' تومان'"></span>
                         <span x-text="Number(maxPrice).toLocaleString() + ' تومان'"></span>
                     </div>
                     <input type="range" min="0" max="10000000" step="100000" x-model="maxPrice" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-primary-600">
                </div>

                 <!-- Availability -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="onlyAvailable" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </div>
                        <span class="text-slate-700 font-medium text-sm">فقط کالاهای موجود</span>
                    </label>
                </div>
            </aside>

            <!-- Product Grid -->
            <div class="col-span-1 lg:col-span-3">
                 <!-- Active Filters Badges -->
                <div class="flex flex-wrap gap-2 mb-6" x-show="selectedCategories.length > 0 || onlyAvailable">
                    <template x-for="catId in selectedCategories" :key="catId">
                         <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-primary-50 text-primary-700 border border-primary-100">
                            <span x-text="getCategoryName(catId)"></span>
                            <button @click="toggleCategory(catId)" class="hover:text-primary-900"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                        </span>
                    </template>
                     <span x-show="onlyAvailable" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                        فقط موجود
                        <button @click="onlyAvailable = false" class="hover:text-green-900"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </span>
                    <button @click="resetFilters" class="text-xs text-red-500 hover:text-red-700 underline px-2">حذف همه</button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="product in filteredProducts" :key="product.id">
                         <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative group flex flex-col">
                             <!-- Image -->
                            <div class="aspect-[4/3] overflow-hidden bg-slate-100 relative">
                                <img :src="product.image || 'https://placehold.co/400x300'" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3 z-10" x-show="product.status === 'unavailable'">
                                    <span class="bg-red-500/90 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">ناموجود</span>
                                </div>
                                <div class="absolute top-3 right-3 z-10" x-show="product.status !== 'unavailable' && product.old_price">
                                    <span class="bg-red-500/90 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">تخفیف ویژه</span>
                                </div>

                                <!-- Hover Actions -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-[2px]">
                                     <a :href="'/product/' + product.id" class="bg-white text-slate-900 p-2.5 rounded-xl hover:bg-primary-50 hover:text-primary-600 transition-colors shadow-lg transform translate-y-4 group-hover:translate-y-0 duration-300" title="مشاهده جزئیات">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    <button @click="openPurchaseModal(product)" x-show="product.status !== 'unavailable'" class="bg-primary-600 text-white p-2.5 rounded-xl hover:bg-primary-700 transition-colors shadow-lg transform translate-y-4 group-hover:translate-y-0 duration-300 delay-75" title="افزودن به سبد خرید">
                                         <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="p-5 flex flex-col flex-grow">
                                 <div class="flex justify-between items-start mb-2">
                                     <h3 class="font-bold text-slate-900 line-clamp-1 hover:text-primary-600 transition-colors" x-text="product.name"></h3>
                                 </div>
                                 <p class="text-sm text-slate-500 mb-4 line-clamp-2" x-text="product.description || 'توضیحات محصول...'"></p>

                                 <div class="mt-auto flex items-end justify-between border-t border-slate-50 pt-4">
                                     <div>
                                         <div x-show="product.old_price" class="text-xs text-slate-400 line-through mb-1" x-text="Number(product.old_price).toLocaleString()"></div>
                                         <span class="text-lg font-bold text-slate-900" x-text="Number(product.price).toLocaleString() + ' تومان'"></span>
                                     </div>
                                     <button @click="openPurchaseModal(product)" :disabled="product.status === 'unavailable'" class="text-sm font-medium px-4 py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-primary-600 hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                         <span x-text="product.status === 'unavailable' ? 'ناموجود' : 'خرید'"></span>
                                     </button>
                                 </div>
                            </div>
                         </div>
                    </template>
                </div>

                <!-- Empty State -->
                <div x-show="filteredProducts.length === 0" class="text-center py-16 bg-white rounded-2xl border border-slate-200 mt-8" x-cloak>
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                         <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">محصولی یافت نشد</h3>
                    <p class="text-slate-500">لطفا فیلترها را تغییر دهید.</p>
                </div>

            </div>
        </div>
    </div>
</main>

<!-- Reuse template-1 purchase modal or implement new one -->
<?php include __DIR__ . '/partials/_purchase_modal.tpl'; ?>
<!-- If modal doesn't exist in theme yet, I need to copy it. -->
<!-- Since I can't rely on template-1 partial being in path, I will create one or link. -->
<!-- However, the plan said "Implement Store Pages". I'll assume I need to create the modal logic or embed it. -->
<!-- But wait, if I don't create it, the button will fail. -->
<!-- I'll check if I can use existing one by relative path? -->
<!-- No, better to duplicate into this theme for consistency and "Functional UI" styling. -->

<script>
    function categoryStore(data) {
        return {
            products: data.products || [],
            categories: data.categories || [],
            selectedCategories: [],
            sortBy: 'newest',
            minPrice: 0,
            maxPrice: 10000000,
            onlyAvailable: false,

            get filteredProducts() {
                let result = this.products;

                // Filter by Category
                if (this.selectedCategories.length > 0) {
                    result = result.filter(p => this.selectedCategories.includes(p.category_id));
                }

                // Filter by Price
                result = result.filter(p => p.price <= this.maxPrice);

                // Filter by Availability
                if (this.onlyAvailable) {
                    result = result.filter(p => p.status !== 'unavailable');
                }

                // Sort
                if (this.sortBy === 'price_asc') {
                    result = result.sort((a, b) => a.price - b.price);
                } else if (this.sortBy === 'price_desc') {
                    result = result.sort((a, b) => b.price - a.price);
                } else if (this.sortBy === 'popular') {
                    // Assuming sales_count exists, otherwise fallback
                    result = result.sort((a, b) => (b.sales_count || 0) - (a.sales_count || 0));
                }
                 // 'newest' is default (assuming initial order is by ID/date)

                return result;
            },

            getCategoryName(id) {
                const cat = this.categories.find(c => c.id == id);
                return cat ? cat.name : '';
            },

            toggleCategory(id) {
                if (this.selectedCategories.includes(id)) {
                    this.selectedCategories = this.selectedCategories.filter(c => c !== id);
                } else {
                    this.selectedCategories.push(id);
                }
            },

            resetFilters() {
                this.selectedCategories = [];
                this.onlyAvailable = false;
                this.maxPrice = 10000000;
            },

            openPurchaseModal(product) {
                // Dispatch event to open modal
                 window.dispatchEvent(new CustomEvent('open-purchase-modal', { detail: product }));
            }
        }
    }
</script>

<?php include __DIR__ . '/footer.tpl'; ?>
