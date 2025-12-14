<?php include 'header.tpl'; ?>

<!-- App Container -->
<main class="flex-grow">
    <!-- 1. Hero Section (Admin Style) -->
    <section class="relative bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 overflow-hidden transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-24 flex flex-col-reverse lg:flex-row items-center gap-12">

            <!-- Text Content -->
            <div class="lg:w-1/2 text-center lg:text-right z-10">
                <h1 class="text-4xl lg:text-6xl font-black text-gray-900 dark:text-white leading-tight mb-6">
                    <span class="block">دنیایی از</span>
                    <span class="block text-primary-600 mt-2">تکنولوژی و زیبایی</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    جدیدترین محصولات دیجیتال و لوازم جانبی را با بهترین قیمت و گارانتی معتبر از فروشگاه مدرن بخواهید. تجربه خریدی سریع، امن و لذت‌بخش.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#products" class="btn btn-primary text-lg rounded-full px-8 py-3 shadow-xl shadow-primary-500/20 hover:translate-y-[-2px]">
                        <svg class="w-6 h-6 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        شروع خرید
                    </a>
                    <a href="/page/about-us" class="btn btn-secondary shadow-none hover:shadow-xl text-lg rounded-full px-8 py-3 bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:translate-y-[-2px]">
                        <svg class="w-6 h-6 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        درباره ما
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="mt-12 flex items-center justify-center lg:justify-start gap-8 text-gray-400 dark:text-gray-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm font-bold">ضمانت اصالت</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-sm font-bold">ارسال سریع</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm font-bold">پشتیبانی ۲۴/۷</span>
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="lg:w-1/2 relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-primary-100 to-transparent dark:from-primary-900/20 rounded-full blur-3xl opacity-60 animate-blob"></div>
                <img src="/images/mobile.png" alt="Store Banner" class="relative z-10 w-full max-w-lg mx-auto drop-shadow-2xl animate-float">
            </div>
        </div>
    </section>

    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        .testimonial-slider .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background-color: #cbd5e1;
            opacity: 1;
            transition: all 0.3s;
        }
        .testimonial-slider .swiper-pagination-bullet-active {
            width: 24px;
            border-radius: 4px;
            background-color: #2563eb;
        }
    </style>

    <!-- 6. Features Section (Cards) -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-5">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row items-start md:items-center gap-4 hover:shadow-lg transition-all duration-300">
                    <div class="p-3 md:p-4 bg-primary-50 dark:bg-primary-900/30 rounded-xl text-primary-600 dark:text-primary-400">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">تحویل فوری</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">کمتر از ۲۴ ساعت</p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row items-start md:items-center gap-4 hover:shadow-lg transition-all duration-300">
                    <div class="p-3 md:p-4 bg-primary-50 dark:bg-primary-900/30 rounded-xl text-primary-600 dark:text-primary-400">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">پشتیبانی ۷/۲۴</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">همیشه پاسخگو</p>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row items-start md:items-center gap-4 hover:shadow-lg transition-all duration-300">
                    <div class="p-3 md:p-4 bg-primary-50 dark:bg-primary-900/30 rounded-xl text-primary-600 dark:text-primary-400">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">بهترین قیمت</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">تضمین قیمت بازار</p>
                    </div>
                </div>
                <!-- Feature 4 -->
                <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row items-start md:items-center gap-4 hover:shadow-lg transition-all duration-300">
                    <div class="p-3 md:p-4 bg-primary-50 dark:bg-primary-900/30 rounded-xl text-primary-600 dark:text-primary-400">
                         <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">ضمانت بازگشت</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">۷ روز ضمانت کالا</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Products Section -->
    <section id="products" class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white sm:text-4xl mb-4">
                    جدیدترین محصولات ما
                </h2>
                <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">
                    از بین دسته‌بندی‌های متنوع انتخاب کنید و بهترین‌ها را بیابید.
                </p>
            </div>

            <!-- Category Tabs (Pills) -->
            <div class="flex justify-center mb-10 overflow-x-auto pb-4 no-scrollbar">
                <div class="flex space-x-reverse bg-gray-100 dark:bg-gray-800 p-1.5 rounded-full">
                    <button
                        @click.prevent="setActiveCategory('all')"
                        :class="activeCategory === 'all' ? 'bg-white dark:bg-gray-700 text-primary-600 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                        class="px-4 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap"
                    >
                        همه محصولات
                    </button>
                    <template x-for="category in categories" :key="category.id">
                        <button
                            @click.prevent="setActiveCategory(category.id)"
                            :class="activeCategory === category.id ? 'bg-white dark:bg-gray-700 text-primary-600 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                            class="px-4 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap"
                            x-text="category.name"
                        ></button>
                    </template>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-5">
                <template x-for="product in filteredProducts" :key="product.id">
                    <article
                        @click="selectProduct(product)"
                        class="group bg-white dark:bg-gray-800 p-3 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col h-full"
                    >
                        <!-- Image -->
                        <div class="relative aspect-[4/3] bg-gray-100 dark:bg-gray-700 overflow-hidden rounded-xl mb-2">
                            <img
                                :src="product.imageUrl"
                                :alt="product.name"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                            <!-- Badge -->
                             <div class="absolute top-3 right-3">
                                <template x-if="product.status === 'available'">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 backdrop-blur-sm">
                                        موجود
                                    </span>
                                </template>
                                <template x-if="product.status !== 'available'">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 backdrop-blur-sm">
                                        ناموجود
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 flex flex-col">
                            <h3 class="text-sm lg:text-lg font-bold text-gray-800 dark:text-white mb-2 line-clamp-1 group-hover:text-primary-600 transition-colors" x-text="product.name"></h3>

                            <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <template x-if="product.old_price && parseFloat(product.old_price) > parseFloat(product.price)">
                                        <span class="text-xs text-gray-400 line-through decoration-red-400" x-text="new Intl.NumberFormat('fa-IR').format(product.old_price) + ' تومان'"></span>
                                    </template>
                                    <span class="text-md font-bold text-gray-600 dark:text-white" x-text="new Intl.NumberFormat('fa-IR').format(product.price) + ' تومان'"></span>
                                </div>

                                <button
                                    class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 text-gray-400 dark:text-gray-300 group-hover:bg-primary-600 group-hover:text-white transition-colors hidden lg:flex items-center justify-center"
                                    :aria-label="'خرید ' + product.name"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredProducts.length === 0" class="text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">محصولی در این دسته‌بندی یافت نشد.</p>
            </div>
        </div>
    </section>

    <!-- 7. Why Us Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-6">چرا فروشگاه مدرن؟</h2>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-lg">
                ما در فروشگاه مدرن متعهد هستیم تا با کیفیت‌ترین محصولات دیجیتال را با ارزان‌ترین قیمت ممکن به دست شما برسانیم. تجربه خریدی بدون دغدغه، پشتیبانی واقعی و ارسال سریع، تفاوت ما با دیگران است.
            </p>
        </div>
    </section>

    <!-- 5. Brands Slider -->
    <section class="py-12 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <p class="text-center text-sm font-bold text-gray-400 dark:text-gray-500 uppercase mb-10">همکاری با برترین برندهای جهان</p>
            <div
                x-data="{ brands: $store.appStore.brands }"
                class="flex items-center justify-center gap-12 flex-wrap opacity-50 grayscale hover:grayscale-0 transition-all duration-500"
            >
                 <template x-for="brand in brands" :key="brand.name">
                    <div class="w-24 h-12 flex items-center justify-center hover:scale-110 transition-transform cursor-pointer" :title="brand.name">
                         <img :src="brand.logo" :alt="brand.name" class="max-h-full max-w-full object-contain dark:invert">
                    </div>
                 </template>
            </div>
        </div>
    </section>

    <!-- 3. Testimonials Section -->
    <section class="py-20 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-primary-600 font-bold tracking-wide uppercase mb-2">نظرات مشتریان</h2>
                <h2 class="text-3xl leading-8 font-black tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    تجربه خریداران ما
                </h2>
            </div>

            <div
                x-show="$store.appStore.reviews && $store.appStore.reviews.length > 0"
                x-cloak
                class="relative max-w-4xl mx-auto"
                x-init="initTestimonialSlider()"
            >
                <!-- Swiper -->
                <div class="swiper testimonial-slider !pb-12">
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        <template x-for="review in $store.appStore.reviews" :key="review.id">
                            <div class="swiper-slide p-4">
                                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-card border border-gray-100 dark:border-gray-800 text-center relative max-w-lg mx-auto">
                                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2">
                                        <img :src="review.userAvatar" class="w-16 h-16 rounded-full border-4 border-white dark:border-gray-800 shadow-lg" alt="Avatar">
                                    </div>
                                    <div class="mt-8">
                                        <div class="flex justify-center items-center gap-1 mb-6">
                                            <template x-for="i in 5">
                                                <svg class="w-5 h-5" :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-700'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            </template>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-6 italic text-lg">
                                            "<span x-text="review.comment"></span>"
                                        </p>
                                        <h4 class="font-bold text-gray-900 dark:text-white" x-text="review.userName"></h4>
                                        <span class="text-xs text-gray-400 dark:text-gray-500 mt-1 block" x-text="review.date"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>

             <div x-show="!$store.appStore.reviews.length" x-cloak class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">هنوز نظری ثبت نشده است.</p>
            </div>
        </div>
    </section>

    <!-- 8. Latest Blog Posts -->
    <section class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                 <div class="text-center md:text-right w-full md:w-auto">
                    <h2 class="text-base text-primary-600 font-bold tracking-wide uppercase mb-2">وبلاگ</h2>
                    <p class="text-3xl font-black tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        آخرین مطالب خواندنی
                    </p>
                 </div>
                 <a href="/blog" class="hidden md:inline-flex btn btn-ghost text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20">
                     مشاهده همه
                     <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                 </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5" x-data="{ posts: $store.appStore.blogPosts }">
                <template x-for="post in posts" :key="post.id">
                    <a :href="'/blog/' + post.category_slug + '/' + post.id + '-' + post.slug" class="group p-3 flex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="aspect-video bg-gray-200 dark:bg-gray-700 overflow-hidden rounded-xl">
                            <img :src="post.imageUrl" :alt="post.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class=" flex-1 flex flex-col">
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 mb-3 block" x-text="post.date"></span>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-primary-600 transition-colors" x-text="post.title"></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-3 mb-4 leading-relaxed" x-text="post.excerpt"></p>
                            <span class="mt-auto text-sm font-bold text-primary-600 inline-flex items-center">
                                ادامه مطلب
                                <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            </span>
                        </div>
                    </a>
                </template>
                 <div x-show="!posts.length" x-cloak class="col-span-full text-center py-12 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
                    <p class="text-gray-500 dark:text-gray-400">مطلبی یافت نشد.</p>
                </div>
            </div>
             <div class="mt-8 text-center md:hidden">
                 <a href="/blog" class="inline-flex btn btn-ghost text-primary-600">
                     مشاهده همه مطالب
                     <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                 </a>
            </div>
        </div>
    </section>

</main>

<?php include 'partials/_purchase_modal.tpl'; ?>

<!-- Alpine.js Store Logic -->
<script>
function store(data) {
    // Add data to a global store to be accessible by nested components
    if (!Alpine.store('appStore')) {
        Alpine.store('appStore', {
            reviews: data.reviews || [],
            brands: data.brands || [],
            blogPosts: data.blogPosts || []
        });
    } else {
        // Update store if already exists (for HMR or subsequent loads)
        Alpine.store('appStore').reviews = data.reviews || [];
        Alpine.store('appStore').brands = data.brands || [];
        Alpine.store('appStore').blogPosts = data.blogPosts || [];
    }

    return {
        initTestimonialSlider() {
            // Ensure elements are rendered before initializing
            this.$nextTick(() => {
                new Swiper('.testimonial-slider', {
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    spaceBetween: 24,
                });
            });
        },
        categories: [],
        products: [],
        activeCategory: 'all',
        selectedProduct: null,
        customFields: [],
        isModalOpen: false,
        isUserLoggedIn: false,
        isSubmitting: false,

        init() {
            this.categories = data.categories || [];
            this.products = data.products || [];
            this.isUserLoggedIn = data.isUserLoggedIn || false;
            // Also update the store just in case
            Alpine.store('appStore').reviews = data.reviews || [];
        },
        get filteredProducts() {
            if (this.activeCategory === 'all') return this.products;
            return this.products.filter(p => p.category == this.activeCategory);
        },
        setActiveCategory(categoryId) { this.activeCategory = categoryId; },
        selectProduct(product) {
            if (product.status !== 'available') {
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { message: 'این محصول در حال حاضر موجود نیست.', type: 'error' }
                }));
                return;
            }

            // Check against global Auth store if available, otherwise fallback to local prop (for initial load)
            const isLoggedIn = (Alpine.store('auth') && Alpine.store('auth').check()) || this.isUserLoggedIn;

            if (!isLoggedIn) {
                window.dispatchEvent(new CustomEvent('open-auth-modal'));
                return;
            }

            this.selectedProduct = product;
            this.isModalOpen = true;
            this.customFields = [];

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
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const form = document.getElementById('purchaseForm');
            const formData = new FormData(form);

            const payloadFields = [];
            this.customFields.forEach(field => {
                let val = null;
                if (field.type === 'checkbox') {
                    const values = formData.getAll(field.name + '[]');
                    if (values.length) val = values.join(', ');
                } else {
                    val = formData.get(field.name);
                }

                if (val) {
                    payloadFields.push({
                        name: field.name,
                        label: field.label,
                        value: val
                    });
                }
            });

            const payload = {
                product_id: this.selectedProduct.id,
                custom_fields: payloadFields
            };

            let csrfToken = '';
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                csrfToken = metaTag.getAttribute('content');
            } else {
                csrfToken = formData.get('csrf_token');
            }

            fetch(form.action, {
                method: form.method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (body.new_csrf_token) {
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) metaTag.setAttribute('content', body.new_csrf_token);
                }

                if (status === 200 && body.payment_url) {
                    window.location.href = body.payment_url;
                } else {
                    alert('خطا: ' + (body.error || 'امکان اتصال به درگاه پرداخت وجود ندارد.'));
                    this.isSubmitting = false;
                }
            }).catch(error => {
                console.error('Error submitting order:', error);
                alert('یک خطای پیش‌بینی نشده در هنگام پرداخت رخ داد.');
                this.isSubmitting = false;
            });
        }
    }
}
</script>

<?php include 'footer.tpl'; ?>