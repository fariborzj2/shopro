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

<main class="flex-grow bg-gray-50 dark:bg-gray-900 py-16 transition-colors duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        <?php if ($success_msg): ?>
            <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-xl mb-8 shadow-sm flex items-center" role="alert">
                <svg class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div>
                    <p class="font-bold">موفقیت</p>
                    <p><?php echo $success_msg; ?></p>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-xl mb-8 shadow-sm flex items-center" role="alert">
                 <svg class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-bold">خطا</p>
                    <p><?php echo $error_msg; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Category Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6 leading-tight">
                <?php echo htmlspecialchars($category->name_fa); ?>
            </h1>
            <div class="text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
                <?php echo strip_tags_except($category->description); ?>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <template x-for="product in products" :key="product.id">
                <article
                    @click="selectProduct(product)"
                    class="group bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col h-full"
                >
                    <!-- Image -->
                    <div class="relative aspect-[4/3] bg-gray-100 dark:bg-gray-700 overflow-hidden">
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
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1 group-hover:text-primary-600 transition-colors" x-text="product.name"></h3>

                        <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex flex-col">
                                <!-- Old Price -->
                                <template x-if="product.old_price && parseFloat(product.old_price) > parseFloat(product.price)">
                                    <span class="text-xs text-gray-400 line-through decoration-red-400" x-text="new Intl.NumberFormat('fa-IR').format(product.old_price) + ' تومان'"></span>
                                </template>
                                <!-- Current Price -->
                                <span class="text-lg font-black text-primary-600" x-text="new Intl.NumberFormat('fa-IR').format(product.price) + ' تومان'"></span>
                            </div>

                            <button
                                class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-400 dark:text-gray-300 group-hover:bg-primary-600 group-hover:text-white transition-colors flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="product.status !== 'available'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                    </div>
                </article>
            </template>
        </div>
    </div>


    <!-- Reviews Section -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white text-center mb-10">نظرات کاربران</h2>

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
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-12 border border-gray-100 dark:border-gray-700 text-center">
                         <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        <p class="text-gray-500 dark:text-gray-400">هنوز هیچ نظری برای محصولات این دسته‌بندی ثبت نشده است. شما اولین نفر باشید!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($all_reviews as $review): ?>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($review['name']); ?></p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        در تاریخ <?php echo jdate('j F Y', strtotime($review['created_at'])); ?>
                                    </p>
                                </div>
                                <div class="flex items-center text-yellow-400">
                                    <span class="font-bold text-gray-700 dark:text-gray-200 ml-1 pt-1"><?php echo htmlspecialchars($review['rating']); ?></span>
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 mt-4 leading-relaxed"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            <?php if (!empty($review['admin_reply'])): ?>
                                <div class="mt-4 mr-4 bg-primary-50 dark:bg-primary-900/20 border-r-4 border-primary-500 p-4 rounded-lg">
                                    <p class="font-bold text-sm text-primary-700 dark:text-primary-400">پاسخ مدیر</p>
                                    <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm leading-relaxed"><?php echo nl2br(htmlspecialchars($review['admin_reply'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Review Submission Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-8 border border-gray-100 dark:border-gray-700">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($lastPurchasedProduct): ?>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">ثبت نظر جدید</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                            شما در حال ثبت نظر برای محصول <span class="font-bold text-primary-600"><?php echo htmlspecialchars($lastPurchasedProduct->name_fa); ?></span> هستید.
                        </p>
                        <form action="/reviews/store" method="POST">
                            <?php csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo $lastPurchasedProduct->id; ?>">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">نام شما</label>
                                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" readonly class="block w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 shadow-sm sm:text-sm py-3 px-4 cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="mobile" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">شماره موبایل</label>
                                    <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($_SESSION['user_mobile'] ?? ''); ?>" readonly class="block w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 shadow-sm sm:text-sm py-3 px-4 cursor-not-allowed">
                                </div>
                            </div>

                            <div class="mb-4" x-data="{ rating: <?php echo htmlspecialchars($old['rating'] ?? 5); ?> }">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">امتیاز شما</label>
                                <div class="flex items-center gap-1 flex-row-reverse justify-end">
                                    <template x-for="i in 5">
                                        <button type="button" @click="rating = i" class="text-3xl transition-colors duration-150 focus:outline-none transform hover:scale-110" :class="i <= rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'">★</button>
                                    </template>
                                </div>
                                <input type="hidden" name="rating" x-model="rating">
                                <?php if (isset($errors['rating'])): ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo htmlspecialchars($errors['rating']); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-6">
                                <label for="comment" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">متن نظر</label>
                                <textarea id="comment" name="comment" rows="4" required class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-4 bg-white dark:bg-gray-700 dark:text-white" placeholder="تجربه خود را با ما و دیگران به اشتراک بگذارید..."><?php echo htmlspecialchars($old['comment'] ?? ''); ?></textarea>
                                <?php if (isset($errors['comment'])): ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo htmlspecialchars($errors['comment']); ?></p>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary-500/30">
                                ثبت نظر
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-900 rounded-xl p-8 text-center">
                             <svg class="w-12 h-12 text-primary-300 dark:text-primary-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            <p class="text-primary-800 dark:text-primary-300 font-medium">
                                شما هنوز محصولی از این دسته‌بندی خریداری نکرده‌اید. پس از خرید، می‌توانید نظر خود را ثبت کنید.
                            </p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-xl p-8 text-center">
                        <p class="text-gray-600 dark:text-gray-300 mb-4 font-medium">برای ارسال نظر ابتدا وارد حساب کاربری شوید.</p>
                        <button @click.prevent="$dispatch('open-auth-modal')" class="btn btn-primary shadow-lg shadow-primary-500/30">
                            ورود یا ثبت‌نام
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
        products: [],
        isModalOpen: false,
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
