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

<main class="flex-grow bg-gray-50 py-16">
    <div class="container">
        <!-- Flash Messages -->
        <?php if ($success_msg): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-8 shadow-sm flex items-center" role="alert">
                <svg class="w-6 h-6 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <p class="font-bold">موفقیت</p>
                    <p class="text-sm"><?php echo $success_msg; ?></p>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-8 shadow-sm flex items-center" role="alert">
                 <svg class="w-6 h-6 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <p class="font-bold">خطا</p>
                    <p class="text-sm"><?php echo $error_msg; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Category Header -->
        <div class="text-center mb-16 relative">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700">
                    <?php echo htmlspecialchars($category->name_fa); ?>
                </span>
            </h1>
            <div class="text-xl text-gray-500 max-w-3xl mx-auto leading-relaxed">
                <?php echo strip_tags_except($category->description); ?>
            </div>

            <!-- Decorative line -->
            <div class="w-24 h-1 bg-primary-500 mx-auto mt-8 rounded-full opacity-50"></div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <template x-for="product in products" :key="product.id">
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
        <div x-show="products.length === 0" class="text-center py-20" style="display: none;">
            <div class="bg-white rounded-3xl p-10 max-w-md mx-auto border border-gray-100 shadow-soft">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-gray-500 text-lg font-medium">محصولی در این دسته‌بندی یافت نشد.</p>
            </div>
        </div>
    </div>
                                    

    <!-- Reviews Section -->
    <div class="container mt-24">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-10 border-b border-gray-200 pb-4">
                <h2 class="text-3xl font-black text-gray-900">نظرات کاربران</h2>
                <span class="text-gray-500 text-sm font-medium">تجربیات خریداران این دسته‌بندی</span>
            </div>

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
                    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                        <p class="text-gray-500">هنوز هیچ نظری برای محصولات این دسته‌بندی ثبت نشده است. شما اولین نفر باشید!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($all_reviews as $review): ?>
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-8 border border-gray-100">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500">
                                        <?php echo mb_substr($review['name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900"><?php echo htmlspecialchars($review['name']); ?></p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            <?php echo jdate('j F Y', strtotime($review['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center bg-yellow-50 px-2 py-1 rounded-lg border border-yellow-100">
                                    <span class="font-bold text-yellow-700 ml-1 text-sm"><?php echo htmlspecialchars($review['rating']); ?></span>
                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            </div>

                            <p class="text-gray-600 leading-relaxed text-base">
                                <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                            </p>

                            <?php if (!empty($review['admin_reply'])): ?>
                                <div class="mt-6 mr-6 bg-gray-50 border-r-2 border-primary-400 p-4 rounded-l-xl">
                                    <p class="font-bold text-xs text-primary-600 mb-2 flex items-center">
                                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                        پاسخ فروشگاه
                                    </p>
                                    <p class="text-gray-600 text-sm leading-relaxed"><?php echo nl2br(htmlspecialchars($review['admin_reply'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Review Submission Form -->
            <div class="bg-white rounded-3xl shadow-card p-8 border border-gray-100 overflow-hidden relative">
                <!-- Background decoration -->
                <div class="absolute top-0 left-0 w-32 h-32 bg-primary-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -translate-x-1/2 -translate-y-1/2"></div>

                <div class="relative z-10">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($lastPurchasedProduct): ?>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">تجربه خود را ثبت کنید</h3>
                            <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                                نظر شما درباره محصول <span class="font-bold text-primary-600 px-1 bg-primary-50 rounded"><?php echo htmlspecialchars($lastPurchasedProduct->name_fa); ?></span> به دیگران کمک می‌کند تا انتخاب بهتری داشته باشند.
                            </p>

                            <form action="/reviews/store" method="POST" class="space-y-6">
                                <?php csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo $lastPurchasedProduct->id; ?>">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="form-label">نام شما</label>
                                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" readonly class="form-input bg-gray-100 cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="form-label">شماره موبایل</label>
                                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_mobile'] ?? ''); ?>" readonly class="form-input bg-gray-100 cursor-not-allowed">
                                    </div>
                                </div>

                                <div x-data="{ rating: <?php echo htmlspecialchars($old['rating'] ?? 5); ?> }">
                                    <label class="form-label mb-3">امتیاز شما</label>
                                    <div class="flex items-center gap-2 flex-row-reverse justify-end">
                                        <template x-for="i in 5">
                                            <button
                                                type="button"
                                                @click="rating = i"
                                                @mouseenter="tempRating = i"
                                                @mouseleave="tempRating = rating"
                                                class="text-4xl transition-all duration-200 focus:outline-none transform hover:scale-110"
                                                :class="i <= rating ? 'text-yellow-400 drop-shadow-sm' : 'text-gray-200'"
                                            >★</button>
                                        </template>
                                    </div>
                                    <input type="hidden" name="rating" x-model="rating">
                                    <?php if (isset($errors['rating'])): ?>
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <?php echo htmlspecialchars($errors['rating']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <label for="comment" class="form-label">متن نظر</label>
                                    <textarea id="comment" name="comment" rows="5" required class="form-input" placeholder="نقاط قوت و ضعف محصول را بنویسید..."><?php echo htmlspecialchars($old['comment'] ?? ''); ?></textarea>
                                    <?php if (isset($errors['comment'])): ?>
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <?php echo htmlspecialchars($errors['comment']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <button type="submit" class="btn btn-primary w-full shadow-xl shadow-primary-500/20">
                                    ثبت دیدگاه ارزشمند شما
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-600">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                </div>
                                <h4 class="font-bold text-blue-900 text-lg mb-2">هنوز خریدی انجام نداده‌اید</h4>
                                <p class="text-blue-700/80 leading-relaxed">
                                    برای ثبت نظر، ابتدا باید محصولی از این دسته‌بندی خریداری کرده باشید. این کار به اطمینان از واقعی بودن نظرات کمک می‌کند.
                                </p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 text-center">
                            <h4 class="font-bold text-gray-900 text-lg mb-4">نظر خود را به اشتراک بگذارید</h4>
                            <p class="text-gray-500 mb-6">برای ثبت نظر ابتدا وارد حساب کاربری خود شوید.</p>
                            <button @click.prevent="$dispatch('open-auth-modal')" class="btn btn-secondary">
                                ورود به حساب کاربری
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
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

<!-- JSON-LD Schema for CollectionPage -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "<?php echo htmlspecialchars($category->name_fa); ?>",
    "description": "<?php echo htmlspecialchars($category->description); ?>",
    "mainEntity": {
        "@type": "ItemList",
        "itemListElement": [
            <?php foreach (json_decode($store_data)->products as $index => $product) : ?>
            {
                "@type": "Product",
                "name": "<?php echo htmlspecialchars($product->name); ?>",
                "image": "<?php echo htmlspecialchars($product->imageUrl); ?>",
                "offers": {
                    "@type": "Offer",
                    "price": "<?php echo htmlspecialchars($product->price); ?>",
                    "priceCurrency": "IRR"
                },
                "review": [
                    <?php if (!empty($product->reviews)): ?>
                    <?php foreach ($product->reviews as $r_index => $review): ?>
                    {
                        "@type": "Review",
                        "reviewRating": {
                            "@type": "Rating",
                            "ratingValue": "<?php echo htmlspecialchars($review->rating); ?>"
                        },
                        "author": {
                            "@type": "Person",
                            "name": "<?php echo htmlspecialchars($review->user_name); ?>"
                        },
                        "reviewBody": "<?php echo htmlspecialchars($review->comment); ?>"
                    }<?php echo $r_index < count($product->reviews) - 1 ? ',' : ''; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                ]
            }<?php echo $index < count(json_decode($store_data)->products) - 1 ? ',' : ''; ?>
            <?php endforeach; ?>
        ]
    }
}
</script>

<?php include 'footer.tpl'; ?>
