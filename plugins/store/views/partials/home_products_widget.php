<?php
use App\Core\Hook;
?>
<!-- 1. Hero Section -->
<section class="relative bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 overflow-hidden transition-colors duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-24 flex flex-col-reverse lg:flex-row items-center gap-12">
        <div class="lg:w-1/2 text-center lg:text-right z-10">
            <h1 class="text-4xl lg:text-6xl font-black text-gray-900 dark:text-white leading-tight mb-6">
                <span class="block">دنیایی از</span>
                <span class="block text-primary-600 mt-2">تکنولوژی و زیبایی</span>
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                جدیدترین محصولات دیجیتال و لوازم جانبی را با بهترین قیمت و گارانتی معتبر از فروشگاه مدرن بخواهید.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                <a href="#products" class="btn btn-primary text-lg rounded-full px-8 py-3 shadow-xl shadow-primary-500/20">شروع خرید</a>
            </div>
        </div>
        <div class="lg:w-1/2 relative">
            <img src="/images/mobile.png" alt="Store Banner" class="relative z-10 w-full max-w-lg mx-auto drop-shadow-2xl">
        </div>
    </div>
</section>
<!-- Products Section -->
<section id="products" class="py-20 bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white sm:text-4xl mb-4">جدیدترین محصولات ما</h2>
        </div>
        <!-- Product Grid Alpine Logic -->
        <div class="grid grid-cols-1 grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-5">
            <template x-for="product in filteredProducts" :key="product.id">
                <article @click="selectProduct(product)" class="group bg-white p-3 rounded-2xl border border-gray-100 cursor-pointer">
                    <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden rounded-xl mb-2">
                        <img :src="product.imageUrl" :alt="product.name" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-lg font-bold text-gray-800" x-text="product.name"></h3>
                    <span class="text-md font-bold text-primary-600" x-text="new Intl.NumberFormat('fa-IR').format(product.price) + ' تومان'"></span>
                </article>
            </template>
        </div>
    </div>
</section>
