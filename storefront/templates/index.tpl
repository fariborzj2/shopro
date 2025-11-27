<?php include 'header.tpl'; ?>

<!-- App Container -->
<main class="flex-grow">
    <!-- 1. Hero Section -->
    <section class="relative flex flex-wrap justify-center bg-white border-b border-gray-100 overflow-hidden">
        
        <div class="basis-2/4 grow mx-auto flex justify-center">
            <div class="relative z-10 px-4 sm:px-6 lg:px-8 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-18 xl:pb-22 pt-12">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-10">
                    <div class="sm:text-center lg:text-right">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">دنیایی از</span>
                            <span class="block text-primary-600 xl:inline">تکنولوژی و زیبایی</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 leading-relaxed">
                            جدیدترین محصولات دیجیتال و لوازم جانبی را با بهترین قیمت و گارانتی معتبر از فروشگاه مدرن بخواهید. تجربه خریدی سریع، امن و لذت‌بخش.
                        </p>
                        <div class="mt-8 sm:mt-10 flex lg:justify-start justify-center gap-4">
                            <div class="mt-3 sm:mt-0">
                                <a href="#products" class="w-full flex items-center justify-center px-4 py-2 text-base font-bold rounded-md text-white bg-primary-600 hover:bg-primary-700 md:py-3 md:text-lg md:px-10 transition-all transform hover:-translate-y-1 shadow-lg shadow-primary-500/30">
                                    <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    شروع خرید
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0">
                                <a href="/page/about-us" class="w-full flex items-center justify-center px-4 py-2 text-base font-bold rounded-md text-primary-700 bg-gray-50 hover:bg-gray-50 md:py-3 md:text-lg md:px-10 transition-all transform hover:-translate-y-1 hover:shadow-lg hover:shadow-primary-500/10">
                                    <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    درباره ما
                                </a>
                            </div>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="mt-8 flex items-center justify-center lg:justify-start gap-6 text-gray-400 grayscale opacity-70">
                            <div class="flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-sm font-medium">ضمانت اصالت</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span class="text-sm font-medium">ارسال سریع</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-sm font-medium">پشتیبانی ۲۴/۷</span>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Hero Image (Floating) -->
        <div class="basis-2/4 grow bg-gray-50 hidden overflow-hidden lg:flex">
            <div class="w-full p-20 pb-0 md:p-10 md:pb-0 flex justify-center items-end lg:w-full lg:h-full relative">
                <!-- Abstract Shapes Behind Image -->
                <img
                    class="max-w-full w-auto h-auto"
                    src="/images/mobile.png"
                    alt="Store Banner"
                >
            </div>
        </div>
    </section>

    <!-- 6. Competitive Advantage -->
    <section class="py-12 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 md:grid-cols-2 gap-4 lg:gap-8">
                <div class="flex flex-col lg:flex-row md:flex-row items-center gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-primary-50 transition-colors duration-300">
                    <div class="p-3 bg-white rounded-xl shadow-sm text-primary-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">تحویل فوری</h3>
                        <p class="text-sm text-gray-500">کمتر از ۲۴ ساعت</p>
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row md:flex-row items-center gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-primary-50 transition-colors duration-300">
                    <div class="p-3 bg-white rounded-xl shadow-sm text-primary-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">پشتیبانی ۷/۲۴</h3>
                        <p class="text-sm text-gray-500">همیشه پاسخگو</p>
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row md:flex-row items-center gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-primary-50 transition-colors duration-300">
                    <div class="p-3 bg-white rounded-xl shadow-sm text-primary-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">بهترین قیمت</h3>
                        <p class="text-sm text-gray-500">تضمین قیمت بازار</p>
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row md:flex-row items-center gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-primary-50 transition-colors duration-300">
                    <div class="p-3 bg-white rounded-xl shadow-sm text-primary-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">پشتیبانی تخصصی</h3>
                        <p class="text-sm text-gray-500">مشاوره پس از خرید</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        .testimonial-slider .swiper-button-next,
        .testimonial-slider .swiper-button-prev {
            color: #475569;
            background-color: white;
            border-radius: 9999px;
            width: 48px;
            height: 48px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
        }
        .testimonial-slider .swiper-button-next:after,
        .testimonial-slider .swiper-button-prev:after {
            font-size: 24px;
            font-weight: bold;
        }
        .testimonial-slider .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background-color: #cbd5e1;
            opacity: 1;
        }

        .testimonial-slider .swiper-pagination-bullet-active {
            background-color: #2563eb;
        }
    </style>

    <!-- 2. Products Section (Professional Card) -->
    <!-- Products Section -->
    <section id="products" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    جدیدترین محصولات ما
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    از بین دسته‌بندی‌های متنوع انتخاب کنید و بهترین‌ها را بیابید.
                </p>
            </div>

            <!-- Category Tabs -->
            <div class="mt-10 flex justify-center pb-8">
                <div class="flex space-x-2 space-x-reverse overflow-x-auto pb-2 no-scrollbar">
                    <button
                        @click.prevent="setActiveCategory('all')"
                        :class="{'bg-primary-600 text-white': activeCategory === 'all', 'bg-white text-gray-600 hover:bg-gray-100': activeCategory !== 'all'}"
                        class="px-6 py-2.5 rounded-md text-sm font-bold transition-all whitespace-nowrap"
                    >
                        همه محصولات
                    </button>
                    <template x-for="category in categories" :key="category.id">
                        <button
                            @click.prevent="setActiveCategory(category.id)"
                            :class="{'bg-primary-600 text-white': activeCategory === category.id, 'bg-white text-gray-600 hover:bg-gray-100': activeCategory !== category.id}"
                            class="px-6 py-2.5 rounded-md text-sm font-bold transition-all whitespace-nowrap"
                            x-text="category.name"
                        ></button>
                    </template>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <template x-for="product in filteredProducts" :key="product.id">
                    <article
                        @click="selectProduct(product)"
                        class="relative  group bg-white rounded-2xl hover:shadow-sm transition-all duration-300 border border-gray-100 cursor-pointer flex flex-col overflow-hidden"
                    >
                        <!-- Image -->
                        <div class="aspect-w-4 aspect-h-3 bg-gray-200 relative overflow-hidden">
                            <img
                                :src="product.imageUrl"
                                :alt="product.name"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex-1 flex flex-col">
                            <!-- Availability Badge -->
                            <div class="mb-2 absolute top-3 right-3">
                                <template x-if="product.status === 'available'">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        موجود
                                    </span>
                                </template>
                                <template x-if="product.status !== 'available'">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        ناموجود
                                    </span>
                                </template>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors" x-text="product.name"></h3>

                            <div class="flex flex-col mt-auto pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <!-- Old Price -->
                                        <template x-if="product.old_price && parseFloat(product.old_price) > parseFloat(product.price)">
                                            <span class="text-sm text-gray-400 line-through decoration-red-400" x-text="new Intl.NumberFormat('fa-IR').format(product.old_price) + ' تومان'"></span>
                                        </template>
                                        <!-- Current Price -->
                                        <span class="text-lg font-black text-primary-600" x-text="new Intl.NumberFormat('fa-IR').format(product.price) + ' تومان'"></span>
                                    </div>

                                    <button
                                        class="p-2 rounded-full bg-gray-50 text-gray-400 group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="product.status !== 'available'"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredProducts.length === 0" class="text-center py-12">
                <p class="text-gray-500 text-lg">محصولی در این دسته‌بندی یافت نشد.</p>
            </div>
        </div>
    </section>

    <!-- 7. Why Us Section -->
    <section class="py-12 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">چرا فروشگاه مدرن؟</h2>
            <p class="text-gray-600 leading-relaxed text-lg">
                ما در فروشگاه مدرن متعهد هستیم تا با کیفیت‌ترین محصولات دیجیتال را با ارزان‌ترین قیمت ممکن به دست شما برسانیم. تجربه خریدی بدون دغدغه، پشتیبانی واقعی و ارسال سریع، تفاوت ما با دیگران است.
            </p>
        </div>
    </section>

    <!-- 4. Trust Section -->
    <section class="py-16 bg-primary-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-extrabold mb-6">اعتماد شما، سرمایه ماست</h2>
                    <p class="text-primary-100 text-lg mb-8 leading-relaxed">
                        با بیش از ۱۰ سال سابقه فعالیت درخشان و داشتن نماد اعتماد الکترونیکی، مفتخریم که توانسته‌ایم رضایت ۹۸٪ مشتریان خود را جلب کنیم. پرداخت امن، ضمانت بازگشت وجه و کالای اصل، حقوق اولیه شماست.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-white/10 backdrop-blur-sm px-6 py-4 rounded-xl border border-white/20">
                            <span class="block text-3xl font-bold text-yellow-400">۳۵۰۰+</span>
                            <span class="text-sm text-gray-300">خرید موفق</span>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm px-6 py-4 rounded-xl border border-white/20">
                            <span class="block text-3xl font-bold text-yellow-400">۴.۸</span>
                            <span class="text-sm text-gray-300">امتیاز مشتریان</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 text-center">
                        <svg class="w-12 h-12 mx-auto text-green-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <h4 class="font-bold">ضمانت اصالت</h4>
                    </div>
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 text-center">
                        <svg class="w-12 h-12 mx-auto text-blue-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        <h4 class="font-bold">پرداخت امن</h4>
                    </div>
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 text-center">
                        <svg class="w-12 h-12 mx-auto text-yellow-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <h4 class="font-bold">ارسال سریع</h4>
                    </div>
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 text-center">
                        <svg class="w-12 h-12 mx-auto text-purple-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <h4 class="font-bold">پشتیبانی</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Brands Slider -->
    <section class="py-12 bg-white border-b border-gray-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 mb-8 font-medium">همکاری با برترین برندهای جهان</p>
            <div
                x-data="{
                    brands: $store.appStore.brands,
                    active: 0,
                    init() {
                         // Simple auto-scroll logic simulation for Alpine
                         // In production, CSS infinite scroll is smoother
                    }
                }"
                class="relative"
            >
                <div class="flex items-center justify-center gap-8 md:gap-16 flex-wrap opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                     <template x-for="brand in brands" :key="brand.name">
                        <div class="w-24 h-12 flex items-center justify-center hover:scale-110 transition-transform cursor-pointer" :title="brand.name">
                             <img :src="brand.logo" :alt="brand.name" class="max-h-full max-w-full object-contain">
                        </div>
                     </template>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Testimonials Section -->
    <section class="py-16 bg-gray-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">نظرات مشتریان</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    تجربه خریداران ما
                </p>
            </div>

            <div
                x-show="$store.appStore.reviews && $store.appStore.reviews.length > 0"
                x-cloak
                class="relative max-w-4xl mx-auto"
                x-init="initTestimonialSlider()"
            >
                <!-- Swiper -->
                <div class="swiper testimonial-slider">
                    <div class="swiper-wrapper pb-16">
                        <!-- Slides -->
                        <template x-for="review in $store.appStore.reviews" :key="review.id">
                            <div class="swiper-slide px-4 py-8">
                                <!-- Review Card -->
                                <div class="bg-white rounded-3xl p-8 shadow-xl w-full text-center relative max-w-lg border border-gray-100 mx-auto">
                                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2">
                                        <img :src="review.userAvatar" class="w-16 h-16 rounded-full border-4 border-white shadow-md" alt="Avatar">
                                    </div>
                                    <div class="mt-8">
                                        <div class="flex justify-center items-center space-x-1 space-x-reverse mb-4">
                                            <template x-for="i in 5">
                                                <svg class="w-5 h-5" :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            </template>
                                        </div>
                                        <p class="text-gray-600 leading-relaxed mb-6 italic">
                                            "<span x-text="review.comment"></span>"
                                        </p>
                                        <h4 class="font-bold text-gray-900" x-text="review.userName"></h4>
                                        <span class="text-xs text-gray-400 mt-1 block" x-text="review.date"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                </div>

                <!-- Navigation Buttons -->
                <div class="swiper-button-prev p-3 rounded-full bg-white shadow-lg text-gray-600 hover:text-primary-600 transition-all hover:scale-110"></div>
                <div class="swiper-button-next p-3 rounded-full bg-white shadow-lg text-gray-600 hover:text-primary-600 transition-all hover:scale-110"></div>

                <!-- CTA -->
                <div class="text-center mt-8">
                     <a href="#" class="inline-flex items-center text-primary-600 font-bold hover:text-primary-700">
                         مشاهده تجربه ۳۵۰۰+ خریدار دیگر
                         <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                     </a>
                </div>
            </div>

             <div x-show="!$store.appStore.reviews.length" x-cloak class="text-center py-12">
                <p class="text-gray-500">هنوز نظری ثبت نشده است.</p>
            </div>
        </div>
    </section>

    <!-- 8. Latest Blog Posts -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                 <div>
                    <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">وبلاگ</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        آخرین مطالب خواندنی
                    </p>
                 </div>
                 <a href="/blog" class="hidden sm:inline-flex items-center text-primary-600 font-bold hover:text-primary-700">
                     مشاهده همه
                     <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                 </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" x-data="{ posts: $store.appStore.blogPosts }">
                <template x-for="post in posts" :key="post.id">
                    <a :href="'/blog/' + post.slug" class="group flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                            <img :src="post.imageUrl" :alt="post.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <span class="text-xs text-gray-400 mb-2" x-text="post.date"></span>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors" x-text="post.title"></h3>
                            <p class="text-sm text-gray-500 line-clamp-3 mb-4" x-text="post.excerpt"></p>
                            <span class="mt-auto text-sm font-bold text-primary-600 inline-flex items-center">
                                ادامه مطلب
                                <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            </span>
                        </div>
                    </a>
                </template>
                 <div x-show="!posts.length" x-cloak class="col-span-full text-center py-8 text-gray-500">
                    مطلبی یافت نشد.
                </div>
            </div>
             <div class="mt-8 text-center sm:hidden">
                 <a href="/blog" class="inline-flex items-center text-primary-600 font-bold hover:text-primary-700">
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
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    spaceBetween: 20,
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
                alert('این محصول در حال حاضر موجود نیست و امکان خرید آن وجود ندارد.');
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

            let csrfToken = formData.get('csrf_token');
            if (!csrfToken) {
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag) csrfToken = metaTag.getAttribute('content');
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
                }
            }).catch(error => {
                console.error('Error submitting order:', error);
                alert('یک خطای پیش‌بینی نشده در هنگام پرداخت رخ داد.');
            });
        }
    }
}
</script>

<?php include 'footer.tpl'; ?>
