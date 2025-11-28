<?php include 'header.tpl'; ?>

<!-- App Container -->
<main class="flex-grow">
    <!-- 1. Hero Section -->
    <section class="relative flex flex-wrap justify-center bg-gradient-to-br from-white to-gray-50 border-b border-gray-100/50 overflow-hidden min-h-[600px] lg:min-h-[700px]">
        <!-- Decorative Background Blobs -->
        <div class="absolute top-0 left-0 -ml-20 -mt-20 w-96 h-96 rounded-full bg-primary-100 blur-3xl opacity-30 mix-blend-multiply animate-blob"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-accent-100 blur-3xl opacity-30 mix-blend-multiply animate-blob animation-delay-2000"></div>
        
        <div class="container relative z-10 flex flex-col lg:flex-row items-center h-full py-12 lg:py-0">
            <!-- Text Content -->
            <div class="w-full lg:w-1/2 text-center lg:text-right pt-10 lg:pt-0 lg:pl-10 order-2 lg:order-1">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-primary-50 border border-primary-100 text-primary-700 text-sm font-bold mb-6 animate-fade-in-up">
                    <span class="relative flex h-3 w-3 ml-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                    </span>
                    جدیدترین محصولات دیجیتال رسید!
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-900 leading-tight mb-6 animate-fade-in-up animation-delay-200">
                    <span class="block mb-2">دنیایی از</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-800">تکنولوژی و زیبایی</span>
                </h1>

                <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed animate-fade-in-up animation-delay-400">
                    تجربه خریدی هوشمندانه با ضمانت اصالت کالا و ارسال فوری. ما بهترین‌های تکنولوژی را برای شما گلچین کرده‌ایم.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 animate-fade-in-up animation-delay-600">
                    <a href="#products" class="btn btn-primary w-full sm:w-auto px-8 py-4 text-lg">
                        <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        مشاهده محصولات
                    </a>
                    <a href="/page/about-us" class="btn btn-secondary w-full sm:w-auto px-8 py-4 text-lg">
                        درباره ما
                    </a>
                </div>

                <!-- Trust Stats -->
                <div class="mt-12 pt-8 border-t border-gray-200/60 flex items-center justify-center lg:justify-start gap-8 lg:gap-12 animate-fade-in-up animation-delay-800">
                    <div class="text-center lg:text-right">
                        <p class="text-2xl font-black text-gray-900">۳۵۰۰+</p>
                        <p class="text-sm text-gray-500 font-medium">مشتری راضی</p>
                    </div>
                    <div class="text-center lg:text-right">
                        <p class="text-2xl font-black text-gray-900">۵۰۰+</p>
                        <p class="text-sm text-gray-500 font-medium">تنوع محصول</p>
                    </div>
                    <div class="text-center lg:text-right">
                        <p class="text-2xl font-black text-gray-900">۲۴/۷</p>
                        <p class="text-sm text-gray-500 font-medium">پشتیبانی</p>
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="w-full lg:w-1/2 flex justify-center items-center relative order-1 lg:order-2 mb-10 lg:mb-0">
                <div class="relative w-full max-w-lg lg:max-w-xl animate-float">
                    <!-- Background Circle -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-primary-100 to-transparent rounded-full opacity-50 blur-2xl transform scale-90 translate-y-10"></div>
                    <img
                        src="/images/mobile.png"
                        alt="Store Banner"
                        class="relative z-10 w-full h-auto drop-shadow-2xl transform hover:scale-105 transition-transform duration-500"
                    >

                    <!-- Floating Cards -->
                    <div class="absolute -bottom-10 -right-10 lg:right-0 z-20 bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-floating animate-bounce-slow hidden sm:block">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold">وضعیت سفارش</p>
                                <p class="text-sm font-black text-gray-900">ارسال شد</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Features / Competitive Advantage -->
    <section class="py-16 bg-white relative z-20 -mt-8 rounded-t-3xl shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.05)]">
        <div class="container">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-card hover:border-primary-100 transition-all duration-300">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary-600 mb-4 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300">
                         <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">ارسال فوری</h3>
                    <p class="text-sm text-gray-500">تحویل در تهران زیر ۲۴ ساعت</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-card hover:border-primary-100 transition-all duration-300">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary-600 mb-4 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">ضمانت اصالت</h3>
                    <p class="text-sm text-gray-500">تضمین اورجینال بودن کالا</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-card hover:border-primary-100 transition-all duration-300">
                     <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary-600 mb-4 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">پشتیبانی ۲۴/۷</h3>
                    <p class="text-sm text-gray-500">پاسخگویی در تمام روزهای هفته</p>
                </div>

                <!-- Feature 4 -->
                <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-card hover:border-primary-100 transition-all duration-300">
                     <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary-600 mb-4 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300">
                         <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">بهترین قیمت</h3>
                    <p class="text-sm text-gray-500">کمترین قیمت بازار</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-20 bg-gray-50">
        <div class="container">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-primary-600 font-bold tracking-wider uppercase text-sm">فروشگاه</span>
                <h2 class="section-title mt-2">جدیدترین محصولات</h2>
                <p class="text-gray-500 text-lg">از میان بهترین برندهای دنیا انتخاب کنید و با خیال راحت خرید کنید.</p>
            </div>

            <!-- Category Filter -->
            <div class="flex justify-center mb-12">
                <div class="inline-flex p-1.5 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto max-w-full">
                    <button
                        @click.prevent="setActiveCategory('all')"
                        :class="activeCategory === 'all' ? 'bg-gray-900 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50'"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap"
                    >
                        همه محصولات
                    </button>
                    <template x-for="category in categories" :key="category.id">
                        <button
                            @click.prevent="setActiveCategory(category.id)"
                            :class="activeCategory === category.id ? 'bg-gray-900 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50'"
                            class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap"
                            x-text="category.name"
                        ></button>
                    </template>
                </div>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <template x-for="product in filteredProducts" :key="product.id">
                    <article
                        @click="selectProduct(product)"
                        class="group bg-white rounded-3xl border border-gray-100 overflow-hidden cursor-pointer transition-all duration-300 hover:shadow-card hover:border-gray-200 flex flex-col h-full relative"
                    >
                        <!-- Image Container -->
                        <div class="aspect-w-1 aspect-h-1 bg-gray-100 relative overflow-hidden p-8">
                            <img
                                :src="product.imageUrl"
                                :alt="product.name"
                                class="w-full h-full object-contain object-center group-hover:scale-110 transition-transform duration-500 mix-blend-multiply"
                                loading="lazy"
                            >

                            <!-- Badges -->
                            <div class="absolute top-4 right-4 flex flex-col gap-2">
                                <template x-if="product.status !== 'available'">
                                    <span class="px-3 py-1 rounded-lg bg-red-50 text-red-600 text-xs font-bold border border-red-100 backdrop-blur-sm">
                                        ناموجود
                                    </span>
                                </template>
                                <template x-if="product.old_price && parseFloat(product.old_price) > parseFloat(product.price) && product.status === 'available'">
                                     <span class="px-3 py-1 rounded-lg bg-red-500 text-white text-xs font-bold shadow-lg shadow-red-500/30">
                                        تخفیف ویژه
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="mb-4">
                                <h3 class="font-bold text-gray-900 text-lg leading-tight mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors" x-text="product.name"></h3>
                                <p class="text-sm text-gray-400 line-clamp-1" x-text="categories.find(c => c.id == product.category)?.name || 'دسته‌بندی'"></p>
                            </div>

                            <div class="mt-auto flex items-end justify-between">
                                <div class="flex flex-col">
                                    <template x-if="product.old_price && parseFloat(product.old_price) > parseFloat(product.price)">
                                        <span class="text-sm text-gray-400 line-through mb-1" x-text="new Intl.NumberFormat('fa-IR').format(product.old_price)"></span>
                                    </template>
                                    <div class="flex items-center gap-1">
                                        <span class="text-xl font-black text-gray-900" x-text="new Intl.NumberFormat('fa-IR').format(product.price)"></span>
                                        <span class="text-xs text-gray-500 font-bold mb-1">تومان</span>
                                    </div>
                                </div>

                                <button
                                    class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 transform group-hover:scale-110 shadow-sm"
                                    :class="product.status === 'available' ? 'bg-primary-600 text-white shadow-primary-500/30 hover:bg-primary-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                                    :disabled="product.status !== 'available'"
                                >
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                </button>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredProducts.length === 0" class="text-center py-20" style="display: none;">
                <div class="bg-white rounded-3xl p-10 max-w-md mx-auto border border-gray-100 shadow-soft">
                     <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-gray-500 text-lg font-medium">محصولی در این دسته‌بندی یافت نشد.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us (Text Content) -->
    <section class="py-24 bg-white">
        <div class="container text-center max-w-4xl">
            <h2 class="text-3xl font-black text-gray-900 mb-6">چرا هزاران نفر به ما اعتماد کرده‌اند؟</h2>
            <p class="text-xl text-gray-500 leading-loose">
                فروشگاه مدرن تنها یک وب‌سایت خرید آنلاین نیست؛ ما تیمی از عاشقان تکنولوژی هستیم که متعهدیم بهترین تجربه خرید اینترنتی را برای شما رقم بزنیم. از مشاوره پیش از خرید تا پشتیبانی مادام‌العمر، در کنار شما هستیم.
            </p>
        </div>
    </section>

    <!-- Trust Stats (Dark Section) -->
    <section class="py-24 bg-gray-900 text-white relative overflow-hidden">
        <!-- Abstract Background -->
        <div class="absolute inset-0 opacity-10 bg-[url('/images/pattern.svg')]"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary-600 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob"></div>

        <div class="container relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-primary-400 font-bold tracking-wider uppercase mb-2 block">آمار و ارقام</span>
                    <h2 class="text-4xl md:text-5xl font-black mb-6 leading-tight">بزرگترین فروشگاه آنلاین <br>لوازم دیجیتال</h2>
                    <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                        ما با تکیه بر اعتماد شما، هر روز بزرگتر می‌شویم. اعداد دروغ نمی‌گویند؛ رضایت مشتریان، اولویت اول و آخر ماست.
                    </p>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-6 rounded-2xl hover:bg-white/10 transition-colors">
                            <span class="block text-4xl font-black text-white mb-1">۹۸٪</span>
                            <span class="text-gray-400">رضایت مشتریان</span>
                        </div>
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-6 rounded-2xl hover:bg-white/10 transition-colors">
                            <span class="block text-4xl font-black text-white mb-1">۵ سال</span>
                            <span class="text-gray-400">سابقه درخشان</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                     <div class="bg-gray-800 p-8 rounded-3xl text-center transform hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-16 h-16 mx-auto bg-gray-700 rounded-2xl flex items-center justify-center text-green-400 mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h4 class="font-bold text-lg">ضمانت بازگشت</h4>
                     </div>
                     <div class="bg-gray-800 p-8 rounded-3xl text-center transform translate-y-8 hover:translate-y-6 transition-transform duration-300">
                        <div class="w-16 h-16 mx-auto bg-gray-700 rounded-2xl flex items-center justify-center text-blue-400 mb-4">
                             <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <h4 class="font-bold text-lg">پرداخت امن</h4>
                     </div>
                     <div class="bg-gray-800 p-8 rounded-3xl text-center transform hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-16 h-16 mx-auto bg-gray-700 rounded-2xl flex items-center justify-center text-yellow-400 mb-4">
                             <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h4 class="font-bold text-lg">ارسال اکسپرس</h4>
                     </div>
                     <div class="bg-gray-800 p-8 rounded-3xl text-center transform translate-y-8 hover:translate-y-6 transition-transform duration-300">
                        <div class="w-16 h-16 mx-auto bg-gray-700 rounded-2xl flex items-center justify-center text-purple-400 mb-4">
                             <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <h4 class="font-bold text-lg">مشاوره رایگان</h4>
                     </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Slider -->
    <section class="py-16 bg-white border-b border-gray-100">
        <div class="container">
            <p class="text-center text-gray-400 text-sm font-bold uppercase tracking-widest mb-10">همکاری با برترین برندهای جهان</p>
            <div x-data="{ brands: $store.appStore.brands }" class="relative overflow-hidden">
                <div class="flex items-center justify-center flex-wrap gap-12 md:gap-20 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                     <template x-for="brand in brands" :key="brand.name">
                        <div class="w-24 h-12 flex items-center justify-center hover:scale-110 transition-transform cursor-pointer" :title="brand.name">
                             <img :src="brand.logo" :alt="brand.name" class="max-h-full max-w-full object-contain filter hover:brightness-0 hover:invert-[.3] transition-all">
                        </div>
                     </template>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-gray-50 relative overflow-hidden">
        <div class="container relative z-10">
            <div class="text-center mb-16">
                <h2 class="section-title">نظرات مشتریان</h2>
                <p class="text-gray-500 text-lg">دیگران درباره ما چه می‌گویند؟</p>
            </div>

            <div
                x-show="$store.appStore.reviews && $store.appStore.reviews.length > 0"
                x-cloak
                class="relative max-w-5xl mx-auto"
                x-init="initTestimonialSlider()"
            >
                <div class="swiper testimonial-slider !pb-16 !px-4">
                    <div class="swiper-wrapper">
                        <template x-for="review in $store.appStore.reviews" :key="review.id">
                            <div class="swiper-slide h-auto">
                                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 h-full flex flex-col relative mx-2">
                                    <!-- Quote Icon -->
                                    <div class="absolute top-6 left-6 text-primary-100">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017V5H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM5.0166 21L5.0166 18C5.0166 16.8954 5.91203 16 7.0166 16H10.0166C10.5689 16 11.0166 15.5523 11.0166 15V9C11.0166 8.44772 10.5689 8 10.0166 8H6.0166C5.46432 8 5.0166 8.44772 5.0166 9V11C5.0166 11.5523 4.56889 12 4.0166 12H3.0166V5H13.0166V15C13.0166 18.3137 10.3303 21 7.0166 21H5.0166Z"></path></svg>
                                    </div>

                                    <div class="flex items-center gap-4 mb-6">
                                        <img :src="review.userAvatar" class="w-14 h-14 rounded-full border-2 border-white shadow-md object-cover" alt="Avatar">
                                        <div>
                                            <h4 class="font-bold text-gray-900" x-text="review.userName"></h4>
                                            <span class="text-xs text-gray-400 block mt-0.5" x-text="review.date"></span>
                                        </div>
                                    </div>

                                    <div class="flex mb-4">
                                        <template x-for="i in 5">
                                            <svg class="w-5 h-5" :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        </template>
                                    </div>

                                    <p class="text-gray-600 leading-relaxed italic relative z-10">
                                        "<span x-text="review.comment"></span>"
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

             <div x-show="!$store.appStore.reviews.length" x-cloak class="text-center py-12">
                <p class="text-gray-500 bg-white inline-block px-8 py-4 rounded-2xl shadow-sm border border-gray-100">هنوز نظری ثبت نشده است.</p>
            </div>
        </div>
    </section>

    <!-- Latest Blog Posts -->
    <section class="py-20 bg-white">
        <div class="container">
            <div class="flex justify-between items-end mb-12">
                 <div>
                    <h2 class="section-title">مجله تکنولوژی</h2>
                    <p class="text-gray-500 text-lg">دانستنی‌های روز دنیای دیجیتال</p>
                 </div>
                 <a href="/blog" class="hidden sm:inline-flex items-center text-primary-600 font-bold hover:text-primary-800 transition-colors">
                     مشاهده همه مطالب
                     <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                 </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" x-data="{ posts: $store.appStore.blogPosts }">
                <template x-for="post in posts" :key="post.id">
                    <a :href="'/blog/' + post.slug" class="group flex flex-col bg-white rounded-3xl overflow-hidden border border-gray-100 hover:shadow-floating transition-all duration-300 transform hover:-translate-y-1">
                        <div class="aspect-w-16 aspect-h-10 bg-gray-200 overflow-hidden">
                            <img :src="post.imageUrl" :alt="post.title" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 mb-3 text-xs font-bold text-primary-600">
                                <span class="bg-primary-50 px-2 py-1 rounded-md">مقاله</span>
                                <span class="text-gray-400 font-medium" x-text="post.date"></span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 leading-tight group-hover:text-primary-600 transition-colors" x-text="post.title"></h3>
                            <p class="text-sm text-gray-500 line-clamp-3 mb-6 leading-relaxed" x-text="post.excerpt"></p>

                            <span class="mt-auto text-sm font-bold text-gray-900 group-hover:text-primary-600 inline-flex items-center transition-colors">
                                مطالعه کنید
                                <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            </span>
                        </div>
                    </a>
                </template>
                 <div x-show="!posts.length" x-cloak class="col-span-full text-center py-12">
                    <p class="text-gray-500">مطلبی یافت نشد.</p>
                </div>
            </div>

             <div class="mt-10 text-center sm:hidden">
                 <a href="/blog" class="btn btn-secondary w-full">
                     مشاهده همه مطالب
                 </a>
            </div>
        </div>
    </section>

</main>

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
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }
    .animate-bounce-slow {
        animation: bounce 3s infinite;
    }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out forwards;
        opacity: 0;
    }
    .animation-delay-200 { animation-delay: 0.2s; }
    .animation-delay-400 { animation-delay: 0.4s; }
    .animation-delay-600 { animation-delay: 0.6s; }
    .animation-delay-800 { animation-delay: 0.8s; }
</style>

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
                        dynamicBullets: true,
                    },
                    slidesPerView: 1,
                    breakpoints: {
                        640: { slidesPerView: 1, spaceBetween: 20 },
                        768: { slidesPerView: 2, spaceBetween: 30 },
                        1024: { slidesPerView: 3, spaceBetween: 30 },
                    },
                    spaceBetween: 30,
                    grabCursor: true,
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
