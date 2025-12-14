<?php include __DIR__ . '/header.tpl'; ?>

<main class="w-full bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4">

        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-slate-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
            <a href="/" class="hover:text-primary-600 transition-colors">خانه</a>
            <svg class="w-4 h-4 mx-2 rtl:rotate-180 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <a href="/category/all" class="hover:text-primary-600 transition-colors">فروشگاه</a>
            <svg class="w-4 h-4 mx-2 rtl:rotate-180 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-900 font-medium truncate"><?= htmlspecialchars($product['name'] ?? 'محصول') ?></span>
        </nav>

        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Gallery Section -->
                <div class="space-y-4" x-data="{ activeImage: '<?= htmlspecialchars($product['image_url'] ?? 'https://placehold.co/600x600') ?>' }">
                    <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                        <img :src="activeImage" class="w-full h-full object-cover" alt="<?= htmlspecialchars($product['name'] ?? '') ?>">
                    </div>
                    <!-- Thumbnails (Static Demo) -->
                    <div class="flex gap-4 overflow-x-auto pb-2">
                         <button @click="activeImage = '<?= htmlspecialchars($product['image_url'] ?? 'https://placehold.co/600x600') ?>'" class="w-20 h-20 rounded-xl border-2 border-transparent hover:border-primary-600 overflow-hidden flex-shrink-0 transition-colors focus:border-primary-600 focus:outline-none">
                            <img src="<?= htmlspecialchars($product['image_url'] ?? 'https://placehold.co/600x600') ?>" class="w-full h-full object-cover">
                        </button>
                        <button @click="activeImage = 'https://placehold.co/600x600/e2e8f0/64748b?text=Image+2'" class="w-20 h-20 rounded-xl border-2 border-transparent hover:border-primary-600 overflow-hidden flex-shrink-0 transition-colors focus:border-primary-600 focus:outline-none">
                            <img src="https://placehold.co/600x600/e2e8f0/64748b?text=Image+2" class="w-full h-full object-cover">
                        </button>
                        <button @click="activeImage = 'https://placehold.co/600x600/e2e8f0/64748b?text=Image+3'" class="w-20 h-20 rounded-xl border-2 border-transparent hover:border-primary-600 overflow-hidden flex-shrink-0 transition-colors focus:border-primary-600 focus:outline-none">
                            <img src="https://placehold.co/600x600/e2e8f0/64748b?text=Image+3" class="w-full h-full object-cover">
                        </button>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex flex-col">
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-2">
                             <?php if(isset($product['category_name'])): ?>
                            <span class="text-primary-600 text-sm font-medium"><?= htmlspecialchars($product['category_name']) ?></span>
                            <?php endif; ?>
                            <?php if(($product['status'] ?? 'available') !== 'unavailable'): ?>
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-bold">موجود</span>
                            <?php else: ?>
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full font-bold">ناموجود</span>
                            <?php endif; ?>
                        </div>
                        <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mb-4 leading-tight"><?= htmlspecialchars($product['name'] ?? 'عنوان محصول') ?></h1>

                        <div class="flex items-center gap-4 mb-6">
                             <div class="flex items-center gap-1 text-yellow-500">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-slate-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <span class="text-sm text-slate-500">(۱۲ دیدگاه)</span>
                        </div>

                        <p class="text-slate-600 leading-relaxed mb-8">
                            <?= htmlspecialchars($product['description'] ?? 'توضیحات پیش‌فرض محصول برای نمایش در طراحی.') ?>
                        </p>
                    </div>

                    <!-- Price & Actions -->
                    <div class="mt-auto bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-slate-600 font-medium">قیمت نهایی:</span>
                            <div class="text-left">
                                <?php if(!empty($product['old_price'])): ?>
                                <div class="text-slate-400 line-through text-sm mb-1"><?= number_format($product['old_price']) ?> تومان</div>
                                <?php endif; ?>
                                <div class="text-3xl font-bold text-slate-900"><?= number_format($product['price'] ?? 0) ?> تومان</div>
                            </div>
                        </div>

                         <div class="flex gap-4">
                            <div class="w-32 flex items-center border border-slate-200 rounded-xl bg-white">
                                <button class="w-full h-full text-slate-500 hover:text-primary-600 text-lg font-bold" onclick="document.getElementById('qty').value > 1 ? document.getElementById('qty').value-- : null">-</button>
                                <input id="qty" type="text" value="1" readonly class="w-full h-full text-center border-none focus:ring-0 text-slate-900 font-bold bg-transparent">
                                <button class="w-full h-full text-slate-500 hover:text-primary-600 text-lg font-bold" onclick="document.getElementById('qty').value++">+</button>
                            </div>

                            <button onclick="/* Add to cart logic */" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 px-6 rounded-xl transition-colors shadow-lg shadow-primary-500/20 text-lg">
                                افزودن به سبد خرید
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Tabs -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12" x-data="{ tab: 'specs' }">
             <div class="lg:col-span-2">
                 <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden min-h-[400px]">
                     <div class="flex border-b border-slate-100">
                         <button @click="tab = 'specs'" :class="{ 'text-primary-600 border-primary-600 bg-primary-50': tab === 'specs', 'text-slate-500 hover:text-slate-700 border-transparent': tab !== 'specs' }" class="flex-1 py-4 font-bold text-sm border-b-2 transition-colors">مشخصات فنی</button>
                         <button @click="tab = 'reviews'" :class="{ 'text-primary-600 border-primary-600 bg-primary-50': tab === 'reviews', 'text-slate-500 hover:text-slate-700 border-transparent': tab !== 'reviews' }" class="flex-1 py-4 font-bold text-sm border-b-2 transition-colors">نقد و بررسی</button>
                         <button @click="tab = 'faq'" :class="{ 'text-primary-600 border-primary-600 bg-primary-50': tab === 'faq', 'text-slate-500 hover:text-slate-700 border-transparent': tab !== 'faq' }" class="flex-1 py-4 font-bold text-sm border-b-2 transition-colors">سوالات متداول</button>
                     </div>

                     <div class="p-8">
                         <div x-show="tab === 'specs'" class="animate-fade-in">
                             <h3 class="font-bold text-lg mb-6">مشخصات محصول</h3>
                             <div class="space-y-4">
                                 <!-- Placeholder Specs -->
                                 <div class="flex justify-between py-3 border-b border-slate-50">
                                     <span class="text-slate-500">وزن</span>
                                     <span class="text-slate-900 font-medium">۲۵۰ گرم</span>
                                 </div>
                                  <div class="flex justify-between py-3 border-b border-slate-50">
                                     <span class="text-slate-500">ابعاد</span>
                                     <span class="text-slate-900 font-medium">۱۰x۵x۲ سانتی‌متر</span>
                                 </div>
                                  <div class="flex justify-between py-3 border-b border-slate-50">
                                     <span class="text-slate-500">جنس بدنه</span>
                                     <span class="text-slate-900 font-medium">پلاستیک فشرده</span>
                                 </div>
                                  <div class="flex justify-between py-3 border-b border-slate-50">
                                     <span class="text-slate-500">کشور سازنده</span>
                                     <span class="text-slate-900 font-medium">ایران</span>
                                 </div>
                             </div>
                         </div>

                         <div x-show="tab === 'reviews'" class="animate-fade-in" x-cloak>
                             <div class="flex items-center justify-between mb-8">
                                 <h3 class="font-bold text-lg">نظرات کاربران</h3>
                                 <button class="text-primary-600 font-medium text-sm border border-primary-200 px-4 py-2 rounded-lg hover:bg-primary-50 transition-colors">ثبت نظر</button>
                             </div>
                             <!-- Placeholder Review -->
                             <div class="bg-slate-50 p-6 rounded-xl border border-slate-100 mb-4">
                                 <div class="flex justify-between mb-2">
                                     <span class="font-bold text-slate-900">علی محمدی</span>
                                     <span class="text-xs text-slate-400">۱۴۰۳/۱۰/۱۲</span>
                                 </div>
                                 <p class="text-slate-600 text-sm leading-relaxed">محصول بسیار با کیفیتی هست. پیشنهاد می‌کنم.</p>
                             </div>
                         </div>

                         <div x-show="tab === 'faq'" class="animate-fade-in" x-cloak>
                             <h3 class="font-bold text-lg mb-6">سوالات متداول</h3>
                             <div class="space-y-4">
                                 <div x-data="{ open: false }" class="border border-slate-200 rounded-xl overflow-hidden">
                                     <button @click="open = !open" class="w-full flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 transition-colors text-right font-medium text-slate-700">
                                         <span>زمان ارسال چقدر است؟</span>
                                         <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                     </button>
                                     <div x-show="open" class="p-4 bg-white text-slate-600 text-sm leading-relaxed border-t border-slate-200">
                                         زمان ارسال برای تهران ۲۴ ساعت و شهرستان‌ها ۳ تا ۵ روز کاری می‌باشد.
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Related Products Sidebar -->
             <div class="lg:col-span-1">
                 <h3 class="font-bold text-slate-900 mb-6 text-lg">محصولات مشابه</h3>
                 <div class="space-y-4">
                     <!-- Loop 3 placeholder items -->
                     <a href="#" class="flex gap-4 p-3 rounded-xl hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-slate-100 group">
                         <div class="w-20 h-20 rounded-lg bg-slate-200 overflow-hidden flex-shrink-0">
                             <img src="https://placehold.co/150" class="w-full h-full object-cover">
                         </div>
                         <div class="flex flex-col justify-center">
                             <h4 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-primary-600 transition-colors">محصول مشابه ۱</h4>
                             <span class="text-sm text-primary-600 font-medium">۱۵۰,۰۰۰ تومان</span>
                         </div>
                     </a>
                     <a href="#" class="flex gap-4 p-3 rounded-xl hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-slate-100 group">
                         <div class="w-20 h-20 rounded-lg bg-slate-200 overflow-hidden flex-shrink-0">
                             <img src="https://placehold.co/150" class="w-full h-full object-cover">
                         </div>
                         <div class="flex flex-col justify-center">
                             <h4 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-primary-600 transition-colors">محصول مشابه ۲</h4>
                             <span class="text-sm text-primary-600 font-medium">۲۵۰,۰۰۰ تومان</span>
                         </div>
                     </a>
                     <a href="#" class="flex gap-4 p-3 rounded-xl hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-slate-100 group">
                         <div class="w-20 h-20 rounded-lg bg-slate-200 overflow-hidden flex-shrink-0">
                             <img src="https://placehold.co/150" class="w-full h-full object-cover">
                         </div>
                         <div class="flex flex-col justify-center">
                             <h4 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-primary-600 transition-colors">محصول مشابه ۳</h4>
                             <span class="text-sm text-primary-600 font-medium">۳۵۰,۰۰۰ تومان</span>
                         </div>
                     </a>
                 </div>
             </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/footer.tpl'; ?>
