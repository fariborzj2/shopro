<?php include 'header.tpl'; ?>

<main
    class="flex-grow bg-gray-50 py-16"
    x-data="store(<?php echo htmlspecialchars($store_data, ENT_QUOTES, 'UTF-8'); ?>)"
    x-init="init()"
>
    <div class="container">
        <!-- Category Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">
                <?= htmlspecialchars($category->name_fa) ?>
            </h1>
            <div class="text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed">
                <?= strip_tags_except($category->description) ?>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <template x-for="product in products" :key="product.id">
                <article
                    @click="selectProduct(product)"
                    class="relative group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 cursor-pointer flex flex-col overflow-hidden"
                >
                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-200">
                        <img
                            :src="product.imageUrl"
                            :alt="product.name"
                            class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
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

                        <h2 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors" x-text="product.name"></h2>
                        <div class="mt-auto pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <!-- Old Price -->
                                    <template x-if="product.old_price && parseFloat(product.old_price) > parseFloat(product.price)">
                                        <span class="text-sm text-gray-400 line-through decoration-red-400" x-text="new Intl.NumberFormat('fa-IR').format(product.old_price) + ' تومان'"></span>
                                    </template>
                                    <!-- Current Price -->
                                    <span class="text-xl font-black text-primary-600" x-text="new Intl.NumberFormat('fa-IR').format(product.price) + ' تومان'"></span>
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
    </div>

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

    <!-- Reviews Section -->
    <div class="container mt-16">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-10">نظرات کاربران</h2>
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($lastPurchasedProduct): ?>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">ثبت نظر جدید</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            شما در حال ثبت نظر برای محصول <span class="font-semibold text-primary-600"><?= htmlspecialchars($lastPurchasedProduct->name_fa) ?></span> هستید.
                        </p>
                        <form action="/reviews/store" method="POST">
                            <?php csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?= $lastPurchasedProduct->id ?>">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">نام شما</label>
                                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" readonly class="block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 shadow-sm sm:text-sm py-3 px-4">
                                </div>
                                <div>
                                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">شماره موبایل</label>
                                    <input type="text" id="mobile" name="mobile" value="<?= htmlspecialchars($_SESSION['user_mobile'] ?? '') ?>" readonly class="block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 shadow-sm sm:text-sm py-3 px-4">
                                </div>
                            </div>

                            <div class="mb-4" x-data="{ rating: 5 }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">امتیاز شما</label>
                                <div class="flex items-center gap-1 flex-row-reverse justify-end">
                                    <template x-for="i in 5">
                                        <button type="button" @click="rating = i" class="text-3xl transition-colors duration-150 focus:outline-none transform hover:scale-110" :class="i <= rating ? 'text-yellow-400' : 'text-gray-300'">★</button>
                                    </template>
                                </div>
                                <input type="hidden" name="rating" x-model="rating" required>
                            </div>

                            <div class="mb-6">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">متن نظر</label>
                                <textarea id="comment" name="comment" rows="4" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-4" placeholder="تجربه خود را با ما و دیگران به اشتراک بگذارید..."></textarea>
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent bg-primary-600 px-6 py-3 text-base font-bold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                                ثبت نظر
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                            <p class="text-blue-800">
                                شما هنوز محصولی از این دسته‌بندی خریداری نکرده‌اید. پس از خرید، می‌توانید نظر خود را ثبت کنید.
                            </p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="bg-gray-50 border rounded-xl p-6 text-center">
                        <p class="text-gray-600 mb-4">برای ارسال نظر ابتدا وارد حساب کاربری شوید.</p>
                        <button @click.prevent="$dispatch('open-auth-modal')" class="font-bold text-primary-600 hover:underline">
                            ورود یا ثبت‌نام
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Alpine.js Store Logic -->
<script>
function store(data) {
    return {
        products: [],
        selectedProduct: null,
        customFields: [],
        isModalOpen: false,
        isUserLoggedIn: false,

        init() {
            this.products = data.products || [];
            this.isUserLoggedIn = data.isUserLoggedIn || false;
        },

        selectProduct(product) {
            if (product.status !== 'available') {
                alert('این محصول در حال حاضر موجود نیست و امکان خرید آن وجود ندارد.');
                return;
            }

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
                "name": "<?= htmlspecialchars($product->name) ?>",
                "image": "<?= htmlspecialchars($product->imageUrl) ?>",
                "offers": {
                    "@type": "Offer",
                    "price": "<?= htmlspecialchars($product->price) ?>",
                    "priceCurrency": "IRR"
                },
                "review": [
                    <?php if (!empty($product->reviews)): ?>
                    <?php foreach ($product->reviews as $r_index => $review): ?>
                    {
                        "@type": "Review",
                        "reviewRating": {
                            "@type": "Rating",
                            "ratingValue": "<?= htmlspecialchars($review->rating) ?>"
                        },
                        "author": {
                            "@type": "Person",
                            "name": "<?= htmlspecialchars($review->user_name) ?>"
                        },
                        "reviewBody": "<?= htmlspecialchars($review->comment) ?>"
                    }<?= $r_index < count($product->reviews) - 1 ? ',' : '' ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                ]
            }<?= $index < count(json_decode($store_data)->products) - 1 ? ',' : '' ?>
            <?php endforeach; ?>
        ]
    }
}
</script>

<?php include 'footer.tpl'; ?>
