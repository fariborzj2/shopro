<?php include 'header.tpl'; ?>

<?php
// Retrieve and clear validation errors from session
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

// Get success/error messages from URL
$success_msg = isset($_GET['success_msg']) ? htmlspecialchars($_GET['success_msg']) : null;
$error_msg = isset($_GET['error_msg']) ? htmlspecialchars($_GET['error_msg']) : null;
?>

<main
    class="flex-grow bg-gray-50 py-16"
    x-data="store(<?php echo htmlspecialchars($store_data, ENT_QUOTES, 'UTF-8'); ?>)"
    x-init="init()"
>
    <div class="container">
        <!-- Flash Messages -->
        <?php if ($success_msg): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-8 shadow-md" role="alert">
                <p class="font-bold">موفقیت</p>
                <p><?= $success_msg ?></p>
            </div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-8 shadow-md" role="alert">
                <p class="font-bold">خطا</p>
                <p><?= $error_msg ?></p>
            </div>
        <?php endif; ?>

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

                                            <template x-if="review.status === 'pending'">
                                                <p class="text-xs text-yellow-600 font-semibold mt-2">(در انتظار تایید مدیر)</p>
                                            </template>

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

                                    <!-- AJAX Messages -->
                                    <template x-if="message.text">
                                        <div :class="{
                                            'bg-green-100 border-green-500 text-green-700': message.type === 'success',
                                            'bg-red-100 border-red-500 text-red-700': message.type === 'error'
                                        }" class="border-l-4 p-3 rounded-lg mb-4 text-sm" role="alert">
                                            <p x-text="message.text"></p>
                                        </div>
                                    </template>

                                    <form @submit.prevent="submitReview">
                                        <div class="mb-4">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">امتیاز شما</label>
                                            <div class="flex items-center gap-1 flex-row-reverse justify-end">
                                                <template x-for="i in 5">
                                                    <button type="button" @click="formData.rating = i" class="text-2xl transition-colors focus:outline-none transform hover:scale-110" :class="i <= formData.rating ? 'text-yellow-400' : 'text-gray-200'">★</button>
                                                </template>
                                            </div>
                                            <template x-if="errors.rating"><p class="text-red-500 text-xs mt-1" x-text="errors.rating"></p></template>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">دیدگاه</label>
                                            <textarea x-model="formData.comment" rows="3" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3" placeholder="نظر خود را بنویسید..."></textarea>
                                            <template x-if="errors.comment"><p class="text-red-500 text-xs mt-1" x-text="errors.comment"></p></template>
                                        </div>

                                        <button type="submit" :disabled="loading" class="w-full inline-flex justify-center items-center rounded-xl bg-primary-600 px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-primary-700 transition-colors disabled:opacity-50">
                                            <span x-show="!loading">ثبت نظر</span>
                                            <span x-show="loading">
                                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
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

            <!-- Reviews List -->
            <div class="space-y-6 mb-12">
                <?php
                $all_reviews = [];
                foreach ($reviews as $product_reviews) {
                    if (is_array($product_reviews)) {
                        $all_reviews = array_merge($all_reviews, $product_reviews);
                    }
                }
                // Sort reviews by date, newest first
                usort($all_reviews, function ($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
                ?>

                <?php if (empty($all_reviews)): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 text-center">
                        <p class="text-gray-500">هنوز هیچ نظری برای محصولات این دسته‌بندی ثبت نشده است. شما اولین نفر باشید!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($all_reviews as $review): ?>
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-bold text-gray-800"><?= htmlspecialchars($review['name']) ?></p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        در تاریخ <?= jdate('j F Y', strtotime($review['created_at'])) ?>
                                    </p>
                                </div>
                                <div class="flex items-center text-yellow-400">
                                    <span class="font-bold text-gray-700 ml-1"><?= htmlspecialchars($review['rating']) ?></span>
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            </div>
                            <p class="text-gray-600 mt-4 leading-relaxed"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            <?php if (!empty($review['admin_reply'])): ?>
                                <div class="mt-4 mr-4 bg-gray-50 border-r-4 border-primary-500 p-4 rounded-lg">
                                    <p class="font-bold text-sm text-primary-700">پاسخ مدیر</p>
                                    <p class="text-gray-600 mt-2 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($review['admin_reply'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Review Submission Form -->
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

                            <div class="mb-4" x-data="{ rating: <?= htmlspecialchars($old['rating'] ?? 5) ?> }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">امتیاز شما</label>
                                <div class="flex items-center gap-1 flex-row-reverse justify-end">
                                    <template x-for="i in 5">
                                        <button type="button" @click="rating = i" class="text-3xl transition-colors duration-150 focus:outline-none transform hover:scale-110" :class="i <= rating ? 'text-yellow-400' : 'text-gray-300'">★</button>
                                    </template>
                                </div>
                                <input type="hidden" name="rating" x-model="rating">
                                <?php if (isset($errors['rating'])): ?>
                                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['rating']) ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-6">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">متن نظر</label>
                                <textarea id="comment" name="comment" rows="4" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-4" placeholder="تجربه خود را با ما و دیگران به اشتراک بگذارید..."><?= htmlspecialchars($old['comment'] ?? '') ?></textarea>
                                <?php if (isset($errors['comment'])): ?>
                                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['comment']) ?></p>
                                <?php endif; ?>
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

        // Form state
        formData: { rating: 5, comment: '' },
        loading: false,
        message: { type: '', text: '' },
        errors: {},

        showReviews(productId) {
            this.selectedProduct = this.products.find(p => p.id === productId);
            // Reset form state when modal opens
            this.formData = { rating: 5, comment: '' };
            this.message.text = '';
            this.errors = {};
            this.loading = false;
            this.showModal = true;
        },

        submitReview() {
            if (!this.selectedProduct) return;

            this.loading = true;
            this.message.text = '';
            this.errors = {};

            const payload = {
                product_id: this.selectedProduct.id,
                rating: this.formData.rating,
                comment: this.formData.comment
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/reviews/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (body.new_csrf_token) {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', body.new_csrf_token);
                }

                if (status === 201) { // Created
                    this.message = { type: 'success', text: body.message };

                    // Add the new review to the product's review list
                    if (!this.selectedProduct.reviews) {
                        this.selectedProduct.reviews = [];
                    }

                    // Add properties for UI that might be missing from the backend response
                    const newReview = {
                        ...body.review,
                        user_name: "<?= htmlspecialchars($_SESSION['user_name'] ?? 'شما') ?>",
                        status: 'pending' // Mark as pending for UI
                    };

                    this.selectedProduct.reviews.unshift(newReview);

                    this.formData = { rating: 5, comment: '' }; // Reset form
                } else if (status === 422) { // Validation Error
                    this.message = { type: 'error', text: body.message };
                    this.errors = body.errors || {};
                } else { // Other errors
                    this.message = { type: 'error', text: body.message || 'یک خطای پیش‌بینی نشده رخ داد.' };
                }
            })
            .catch(() => {
                this.message = { type: 'error', text: 'خطا در ارتباط با سرور.' };
            })
            .finally(() => {
                this.loading = false;
                // Hide the message after 5 seconds, but only for success
                if (this.message.type === 'success') {
                    setTimeout(() => this.message.text = '', 5000);
                }
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
