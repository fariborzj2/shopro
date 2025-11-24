<?php include 'header.tpl'; ?>

<!-- App Container -->
<main
    x-data="store(<?php echo htmlspecialchars($store_data, ENT_QUOTES, 'UTF-8'); ?>)"
    x-init="init()"
    class="flex-grow"
>
    <!-- Hero Section -->
    <section class="relative bg-white overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-gray-50 z-0"></div>

        <!-- Animated Shapes -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-32 -left-24 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 right-1/4 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-12">

                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-right">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">دنیایی از</span>
                            <span class="block text-primary-600 xl:inline">تکنولوژی و زیبایی</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 leading-relaxed">
                            جدیدترین محصولات دیجیتال و لوازم جانبی را با بهترین قیمت و گارانتی معتبر از فروشگاه مدرن بخواهید. تجربه خریدی سریع، امن و لذت‌بخش.
                        </p>
                        <div class="mt-8 sm:mt-10 sm:flex sm:justify-center lg:justify-start gap-4">
                            <div class="rounded-md shadow">
                                <a href="#products" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-2xl text-white bg-primary-600 hover:bg-primary-700 md:py-4 md:text-lg md:px-10 transition-all transform hover:-translate-y-1 hover:shadow-lg shadow-primary-500/30">
                                    <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    شروع خرید
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0">
                                <a href="/page/about-us" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-2xl text-primary-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10 transition-all border-gray-100 shadow-sm hover:shadow-md">
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
        <div class="lg:absolute lg:inset-y-0 lg:left-0 lg:w-1/2">
            <div class="h-56 w-full sm:h-72 md:h-96 lg:w-full lg:h-full relative">
                <!-- Abstract Shapes Behind Image -->
                <div class="absolute inset-0 bg-gradient-to-tr from-primary-100/50 to-transparent lg:rounded-tr-[100px] z-0"></div>
                <img
                    class="w-full h-full object-cover lg:rounded-tr-[100px] relative z-10 shadow-2xl transform hover:scale-105 transition-transform duration-700 ease-out"
                    src="https://placehold.co/800x800/f8fafc/3b82f6?text=Modern+Store"
                    alt="Store Banner"
                    style="clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%);"
                >
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
    </style>

    <!-- Products Section -->
    <section id="products" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">محصولات</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    جدیدترین محصولات ما
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    از بین دسته‌بندی‌های متنوع انتخاب کنید و بهترین‌ها را بیابید.
                </p>
            </div>

            <!-- Category Tabs -->
            <div class="mt-10 flex justify-center pb-8">
                <div class="flex space-x-2 space-x-reverse overflow-x-auto pb-2 no-scrollbar">
                    <button
                        @click.prevent="setActiveCategory('all')"
                        :class="{'bg-primary-600 text-white shadow-lg shadow-primary-500/30': activeCategory === 'all', 'bg-white text-gray-600 hover:bg-gray-100': activeCategory !== 'all'}"
                        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap"
                    >
                        همه محصولات
                    </button>
                    <template x-for="category in categories" :key="category.id">
                        <button
                            @click.prevent="setActiveCategory(category.id)"
                            :class="{'bg-primary-600 text-white shadow-lg shadow-primary-500/30': activeCategory === category.id, 'bg-white text-gray-600 hover:bg-gray-100': activeCategory !== category.id}"
                            class="px-6 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap"
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
                        class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 cursor-pointer flex flex-col overflow-hidden"
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
                            <div class="mb-2">
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
                            <p class="text-sm text-gray-500 line-clamp-2 mb-4 flex-grow" x-text="product.description || 'توضیحات محصول...'"></p>

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

                                    <button class="p-2 rounded-full bg-gray-50 text-gray-400 group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors">
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

    <!-- Testimonials Section -->
    <section class="py-16 bg-white relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-100 rounded-bl-full opacity-30 z-0"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-100 rounded-tr-full opacity-30 z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">نظرات مشتریان</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    آنچه دیگران درباره ما می‌گویند
                </p>
            </div>

            <div
                x-data="{
                    activeSlide: 0,
                    slides: [],
                    init() {
                        this.slides = this.$store.appStore.reviews;
                        if (this.slides.length > 0) {
                            setInterval(() => {
                                this.next();
                            }, 5000);
                        }
                    },
                    next() {
                        this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                    },
                    prev() {
                        this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                    }
                }"
                class="relative max-w-4xl mx-auto"
                x-show="slides.length > 0"
                x-cloak
            >
                <!-- Slider Container -->
                <div class="overflow-hidden relative min-h-[300px] flex items-center justify-center">
                    <template x-for="(review, index) in slides" :key="index">
                        <div
                            class="absolute inset-0 transition-all duration-700 ease-in-out transform flex flex-col justify-center items-center px-8 md:px-16"
                            :class="{
                                'translate-x-0 opacity-100 z-20': activeSlide === index,
                                'translate-x-full opacity-0 z-10': activeSlide < index,
                                '-translate-x-full opacity-0 z-10': activeSlide > index
                            }"
                        >
                            <!-- Review Card -->
                            <div class="bg-white/60 backdrop-blur-md border border-white/40 shadow-glass rounded-3xl p-8 md:p-10 w-full text-center relative">
                                <!-- Quote Icon -->
                                <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-primary-600 text-white p-3 rounded-full shadow-lg">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.896 14.321 15.923 14.929 15.081C15.537 14.239 16.413 13.535 17.557 12.969V11.233C16.819 11.233 16.275 11.206 15.925 11.152C15.575 11.098 15.225 10.982 14.875 10.804C14.525 10.626 14.225 10.358 13.975 10C13.725 9.642 13.6 9.176 13.6 8.6C13.6 7.962 13.829 7.424 14.287 6.986C14.745 6.548 15.304 6.329 15.965 6.329C16.626 6.329 17.185 6.548 17.643 6.986C18.101 7.424 18.33 7.962 18.33 8.6C18.33 9.138 18.121 9.666 17.703 10.184C17.285 10.702 16.711 11.176 15.981 11.606C15.823 11.698 15.669 11.776 15.519 11.84C15.369 11.904 15.219 11.946 15.069 11.966L15.069 12.352C15.069 13.118 14.685 13.898 13.917 14.692C13.149 15.486 12.333 16.038 11.469 16.348L11.469 21H14.017ZM6.54902 21L6.54902 18C6.54902 16.896 6.85302 15.923 7.46102 15.081C8.06902 14.239 8.94502 13.535 10.089 12.969V11.233C9.35102 11.233 8.80702 11.206 8.45702 11.152C8.10702 11.098 7.75702 10.982 7.40702 10.804C7.05702 10.626 6.75702 10.358 6.50702 10C6.25702 9.642 6.13202 9.176 6.13202 8.6C6.13202 7.962 6.36102 7.424 6.81902 6.986C7.27702 6.548 7.83602 6.329 8.49702 6.329C9.15802 6.329 9.71702 6.548 10.175 6.986C10.633 7.424 10.862 7.962 10.862 8.6C10.862 9.138 10.653 9.666 10.235 10.184C9.81702 10.702 9.24302 11.176 8.51302 11.606C8.35502 11.698 8.20102 11.776 8.05102 11.84C7.90102 11.904 7.75102 11.946 7.60102 11.966L7.60102 12.352C7.60102 13.118 7.21702 13.898 6.44902 14.692C5.68102 15.486 4.86502 16.038 4.00102 16.348L4.00102 21H6.54902Z" /></svg>
                                </div>

                                <p class="text-xl md:text-2xl font-medium text-gray-800 leading-relaxed mb-6 mt-4">
                                    "<span x-text="review.comment"></span>"
                                </p>

                                <div class="flex flex-col items-center">
                                    <!-- Stars -->
                                    <div class="flex items-center space-x-1 space-x-reverse mb-2">
                                        <template x-for="i in 5">
                                            <svg
                                                class="w-5 h-5"
                                                :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300'"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </template>
                                    </div>

                                    <h4 class="font-bold text-gray-900" x-text="review.userName"></h4>
                                    <span class="text-sm text-gray-500 mt-1" x-text="review.date"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Navigation Buttons -->
                <button
                    @click="next()"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 -ml-4 md:-ml-12 p-3 rounded-full bg-white shadow-lg text-gray-600 hover:text-primary-600 focus:outline-none transition-transform hover:scale-110"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button
                    @click="prev()"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 -mr-4 md:-mr-12 p-3 rounded-full bg-white shadow-lg text-gray-600 hover:text-primary-600 focus:outline-none transition-transform hover:scale-110"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <!-- Indicators -->
                <div class="flex justify-center mt-8 space-x-2 space-x-reverse">
                    <template x-for="(review, index) in slides" :key="index">
                        <button
                            @click="activeSlide = index"
                            class="w-3 h-3 rounded-full transition-colors duration-300"
                            :class="activeSlide === index ? 'bg-primary-600' : 'bg-gray-300 hover:bg-gray-400'"
                        ></button>
                    </template>
                </div>
            </div>

            <!-- Empty State for Reviews -->
            <div x-show="!$store.appStore.reviews.length" x-cloak class="text-center py-12">
                <p class="text-gray-500">هنوز نظری ثبت نشده است.</p>
            </div>
        </div>
    </section>

    <!-- Purchase Modal -->
    <div
        x-show="isModalOpen"
        class="relative z-50"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        x-cloak
    >
        <div
            x-show="isModalOpen"
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
                    x-show="isModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="isModalOpen = false"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <template x-if="selectedProduct">
                            <div>
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                                    <h3 class="text-xl font-bold text-gray-900" id="modal-title" x-text="selectedProduct.name"></h3>
                                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-500">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <form @submit.prevent="submitOrder" id="purchaseForm" method="POST" action="/api/payment/start">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" :value="selectedProduct.id">

                                    <div class="space-y-5 max-h-[60vh] overflow-y-auto px-1 -mx-1 custom-scrollbar">
                                        <template x-for="field in customFields" :key="field.id">
                                            <div>
                                                <label :for="'field_' + field.id" class="block text-sm font-bold text-gray-700 mb-2">
                                                    <span x-text="field.label"></span>
                                                    <span x-show="field.is_required" class="text-red-500">*</span>
                                                </label>

                                                <!-- Text/Number/Date -->
                                                <template x-if="['text', 'number', 'date', 'color'].includes(field.type)">
                                                    <input
                                                        :type="field.type"
                                                        :name="field.name"
                                                        :id="'field_' + field.id"
                                                        :placeholder="field.placeholder"
                                                        :required="field.is_required"
                                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50"
                                                    >
                                                </template>

                                                <!-- Textarea -->
                                                <template x-if="field.type === 'textarea'">
                                                    <textarea
                                                        :name="field.name"
                                                        :id="'field_' + field.id"
                                                        :placeholder="field.placeholder"
                                                        :required="field.is_required"
                                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50"
                                                        rows="3"
                                                    ></textarea>
                                                </template>

                                                <!-- Select -->
                                                <template x-if="field.type === 'select'">
                                                    <select
                                                        :name="field.name"
                                                        :id="'field_' + field.id"
                                                        :required="field.is_required"
                                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50"
                                                    >
                                                        <option value="" disabled selected>انتخاب کنید...</option>
                                                        <template x-for="option in field.options" :key="option.value">
                                                            <option :value="option.value" x-text="option.label"></option>
                                                        </template>
                                                    </select>
                                                </template>

                                                <!-- Radio -->
                                                <template x-if="field.type === 'radio'">
                                                    <div class="space-y-2">
                                                        <template x-for="option in field.options" :key="option.value">
                                                            <label class="flex items-center space-x-3 space-x-reverse cursor-pointer">
                                                                <input
                                                                    type="radio"
                                                                    :name="field.name"
                                                                    :id="'field_' + field.id + '_' + option.value"
                                                                    :value="option.value"
                                                                    :required="field.is_required"
                                                                    class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500"
                                                                >
                                                                <span class="text-sm text-gray-700" x-text="option.label"></span>
                                                            </label>
                                                        </template>
                                                    </div>
                                                </template>

                                                <!-- Checkbox -->
                                                <template x-if="field.type === 'checkbox'">
                                                     <div class="space-y-2">
                                                        <template x-for="option in field.options" :key="option.value">
                                                            <label class="flex items-center space-x-3 space-x-reverse cursor-pointer">
                                                                <input
                                                                    type="checkbox"
                                                                    :name="field.name + '[]'"
                                                                    :id="'field_' + field.id + '_' + option.value"
                                                                    :value="option.value"
                                                                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                                                >
                                                                <span class="text-sm text-gray-700" x-text="option.label"></span>
                                                            </label>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="mt-8 flex flex-col gap-3">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent bg-primary-600 px-4 py-3 text-base font-bold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:text-sm transition-colors">
                                            پرداخت نهایی
                                        </button>
                                        <button @click="isModalOpen = false" type="button" class="w-full inline-flex justify-center rounded-xl border border-gray-300 bg-white px-4 py-3 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:text-sm transition-colors">
                                            انصراف
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Alpine.js Store Logic -->
<script>
function store(data) {
    // Add data to a global store to be accessible by nested components
    if (!Alpine.store('appStore')) {
        Alpine.store('appStore', {
            reviews: data.reviews || []
        });
    }

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
            // Also update the store just in case
            Alpine.store('appStore').reviews = data.reviews || [];
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
