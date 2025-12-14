<?php include __DIR__ . '/header.tpl'; ?>

<main class="w-full">

    <!-- Hero Section -->
    <section class="relative bg-white overflow-hidden py-16 sm:py-24 lg:py-32">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8 animate-fade-in-up">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                        تجربه‌ای <span class="text-primary-600">متفاوت</span> از <br class="hidden lg:block"/> خرید آنلاین
                    </h1>
                    <p class="text-lg text-slate-500 max-w-xl leading-relaxed">
                        با محصولات باکیفیت و طراحی مدرن ما، سبک زندگی خود را ارتقا دهید. سادگی، زیبایی و کارایی در هر محصول.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/category/all" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-medium rounded-xl text-white bg-primary-600 hover:bg-primary-700 md:text-lg shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-1">
                            مشاهده محصولات
                        </a>
                        <a href="/about" class="inline-flex items-center justify-center px-8 py-4 border border-slate-200 text-base font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 md:text-lg transition-all hover:shadow-md">
                            درباره ما
                        </a>
                    </div>

                    <div class="pt-8 border-t border-slate-100 grid grid-cols-3 gap-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-2xl font-bold text-slate-900">+۱۰K</span>
                            <span class="text-sm text-slate-500">مشتری راضی</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-2xl font-bold text-slate-900">۲۴/۷</span>
                            <span class="text-sm text-slate-500">پشتیبانی</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-2xl font-bold text-slate-900">%۱۰۰</span>
                            <span class="text-sm text-slate-500">ضمانت بازگشت</span>
                        </div>
                    </div>
                </div>

                <div class="relative lg:h-full min-h-[400px]">
                    <div class="absolute inset-0 bg-primary-100 rounded-3xl transform rotate-3 scale-95 opacity-50"></div>
                    <div class="absolute inset-0 bg-slate-100 rounded-3xl transform -rotate-3 scale-95 opacity-50"></div>
                    <img src="https://placehold.co/800x600/f8fafc/e2e8f0?text=Hero+Image" alt="Hero" class="relative rounded-2xl shadow-2xl object-cover w-full h-full transform transition-transform hover:scale-[1.01] duration-500">

                    <!-- Floating Card -->
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-xl border border-slate-100 max-w-xs animate-bounce-slow hidden md:block">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">کیفیت تضمین شده</p>
                                <p class="text-xs text-slate-500">توسط ۱۰۰۰+ کاربر</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Purchase Section -->
    <section class="py-20 bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-12">
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">محصولات منتخب</h2>
                    <p class="text-slate-500">بهترین‌های هفته را از دست ندهید</p>
                </div>
                <a href="/category/all" class="text-primary-600 font-medium hover:text-primary-700 flex items-center gap-1 group">
                    مشاهده همه
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
            </div>

            <!-- Product Grid using Alpine data from $store_data -->
            <div x-data="productGrid()" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                 <template x-for="product in products" :key="product.id">
                    <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative">
                        <!-- Image -->
                        <div class="aspect-[4/3] overflow-hidden bg-slate-100 relative">
                            <img :src="product.image || 'https://placehold.co/400x300'" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            <!-- Quick Action Overlay -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[2px]">
                                <button @click="openModal(product)" class="bg-white text-slate-900 p-2.5 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors shadow-lg transform translate-y-4 group-hover:translate-y-0 duration-300">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <button class="bg-primary-600 text-white p-2.5 rounded-lg hover:bg-primary-700 transition-colors shadow-lg transform translate-y-4 group-hover:translate-y-0 duration-300 delay-75">
                                     <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-slate-900 line-clamp-1" x-text="product.name"></h3>
                                <div class="flex items-center gap-1 bg-yellow-50 px-1.5 py-0.5 rounded text-yellow-700 text-xs font-bold">
                                    <span>4.5</span>
                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            </div>
                            <p class="text-sm text-slate-500 mb-4 line-clamp-2" x-text="product.description || 'توضیحات محصول...'"></p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-primary-600" x-text="Number(product.price).toLocaleString() + ' تومان'"></span>
                                <span x-show="product.old_price" class="text-xs text-slate-400 line-through" x-text="Number(product.old_price).toLocaleString()"></span>
                            </div>
                        </div>
                    </div>
                 </template>
            </div>
        </div>
    </section>

    <!-- Why Us -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
             <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight mb-4">چرا ما را انتخاب کنید؟</h2>
                <p class="text-slate-500">ما متعهد به ارائه بهترین تجربه خرید برای شما هستیم.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary-200 hover:shadow-lg transition-all text-center group">
                    <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-6 text-primary-600 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">سرعت بالا</h3>
                    <p class="text-slate-500 leading-relaxed">تحویل سریع و مطمئن در کمترین زمان ممکن به سراسر کشور.</p>
                </div>

                 <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary-200 hover:shadow-lg transition-all text-center group">
                    <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-6 text-primary-600 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">امنیت پرداخت</h3>
                    <p class="text-slate-500 leading-relaxed">درگاه‌های پرداخت امن و رمزنگاری شده برای آرامش خاطر شما.</p>
                </div>

                 <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary-200 hover:shadow-lg transition-all text-center group">
                    <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-6 text-primary-600 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">پشتیبانی ۲۴/۷</h3>
                    <p class="text-slate-500 leading-relaxed">تیم پشتیبانی ما در تمام ساعات شبانه‌روز آماده پاسخگویی به شماست.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-20 bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-12">
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">آخرین مقالات</h2>
                    <p class="text-slate-500">دانش و اخبار روز دنیای تکنولوژی</p>
                </div>
                <a href="/blog" class="text-primary-600 font-medium hover:text-primary-700 flex items-center gap-1 group">
                    وبلاگ
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
            </div>

            <!-- Blog Grid (Static or fetched if available) -->
            <?php if (!empty($recent_posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($recent_posts as $post): ?>
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col h-full group">
                    <a href="/blog/<?= htmlspecialchars($post['slug'] ?? '') ?>" class="block aspect-video overflow-hidden">
                        <img src="<?= htmlspecialchars($post['image_url'] ?? 'https://placehold.co/600x400') ?>" alt="<?= htmlspecialchars($post['title'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 text-xs text-slate-400 mb-3">
                            <span><?= htmlspecialchars($post['category'] ?? 'عمومی') ?></span>
                            <span>•</span>
                            <span><?= \jdate('d F Y', strtotime($post['created_at'] ?? 'now')) ?></span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-primary-600 transition-colors">
                            <a href="/blog/<?= htmlspecialchars($post['slug'] ?? '') ?>">
                                <?= htmlspecialchars($post['title'] ?? '') ?>
                            </a>
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-3 mb-4 flex-grow">
                            <?= htmlspecialchars($post['excerpt'] ?? '') ?>
                        </p>
                        <a href="/blog/<?= htmlspecialchars($post['slug'] ?? '') ?>" class="text-primary-600 font-medium text-sm hover:underline mt-auto inline-flex items-center gap-1">
                            ادامه مطلب
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                 <div class="text-center text-slate-400 py-12">مقاله‌ای یافت نشد.</div>
            <?php endif; ?>
        </div>
    </section>

</main>

<script>
    function productGrid() {
        // Initialize with PHP data
        const storeData = <?php echo isset($store_data) ? json_encode($store_data) : '{}'; ?>;
        const rawProducts = storeData.products || [];

        return {
            products: rawProducts.slice(0, 4), // Limit to 4 for Quick Purchase
            openModal(product) {
                // Dispatch event for a modal or implement one here.
                // For now, redirect to category or detail page if modal logic isn't fully ported
                // But better: use a simple alert or reuse the logic if possible.
                // Ideally, we would have a Product Detail Modal.
                window.location.href = '/category/all';
            }
        }
    }
</script>

<?php include __DIR__ . '/footer.tpl'; ?>
