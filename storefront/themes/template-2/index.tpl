<?php include 'header.tpl'; ?>

<div class="main-content-wrapper">
    <!-- Hero -->
    <div class="section pd-td-40 hero">
        <div class="center">
            <div class="hero-info text-center max-w600 m-auto relative">
                <div class="star-amin float-star"></div>
                <div class="star-amin float-star"></div>
                <div class="star-amin float-star"></div>
                <div class="color-bright"><span class="icon-crown-1 gr-text font-size-1-5"></span> <?php echo htmlspecialchars($settings['site_title'] ?? 'فروشگاه'); ?></div>
                <h1 class="title-size-1">خرید <span class="gr-text">محصولات دیجیتال</span></h1>
                <p class="font-size-1-1">
                    ما بهترین سرویس‌ها را با ارزان‌ترین قیمت برای شما فراهم کرده‌ایم. لذت خریدی آسان و سریع را تجربه کنید.
                </p>
                <div class="d-flex-wrap just-center mt-40 fix-mr5">
                    <a href="#products" class="btn bg-gr m-5 basis140">شروع خرید</a>
                    <a href="/page/about-us" class="btn border m-5 basis140 color-text">درباره ما</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Image -->
    <div class="section pd-td-40 mob-bg">
        <div class="center ">
            <div class="img max-w700 m-auto"><img src="/template-2/images/mobile.avif" alt=""></div>
        </div>
    </div>

    <!-- Purchase Section -->
    <div id="products" class="section pd-d-40 bg-purple-50">
        <div class="center">
            <div class="max-w400 text-center m-auto">
                <h2 class="title-size-4 font purple-gray">انتخاب محصول</h2>
                <p>
                    محصول مورد نظر خود را انتخاب کنید و به سادگی خرید خود را انجام دهید.
                </p>
            </div>

            <div class="max-w1000 mt-40 m-auto">
                <div class="pr-container bg-white pd-20 radius-20 tab-container">

                    <!-- Category Tabs -->
                    <div class="d-flex scrollhide touch-pan-x fix-mr10" role="tablist">
                        <!-- All Products Tab Button -->
                        <div
                            @click="setActiveCategory('all')"
                            :class="{'active': activeCategory === 'all'}"
                            class="product-tab-btn"
                            role="tab" tabindex="0"
                            style="cursor: pointer;"
                        >
                            <div><i class="icon-star-3 font-size-3 gr-text"></i></div>
                            <div class="line20">
                                <div class="gr-text font-size-1-5">همه</div>
                                <div class="purple-gray">همه محصولات</div>
                            </div>
                        </div>

                        <!-- Dynamic Categories -->
                        <template x-for="category in categories" :key="category.id">
                            <div
                                @click="setActiveCategory(category.id)"
                                :class="{'active': activeCategory === category.id}"
                                class="product-tab-btn"
                                role="tab" tabindex="0"
                                style="cursor: pointer;"
                            >
                                <div><i class="icon-flash-3 font-size-3 gr-text"></i></div>
                                <div class="line20">
                                    <div class="gr-text font-size-1-5" x-text="category.name"></div>
                                    <div class="purple-gray" x-text="category.slug"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Product List Content -->
                    <div class="tab-content" style="display: block;">
                        <div class="product-box mt-20 fix-mr10">

                            <template x-for="product in filteredProducts" :key="product.id">
                                <div class="product-item d-flex align-center" :class="{'selected': selectedProduct && selectedProduct.id === product.id}">
                                    <input
                                        type="radio"
                                        name="product"
                                        :id="'product-' + product.id"
                                        class="mb-10"
                                        @change="selectProduct(product)"
                                        :checked="selectedProduct && selectedProduct.id === product.id"
                                    >
                                    <label :for="'product-' + product.id" class="d-flex align-center just-between grow-1 mr-10 cursor-pointer w-full">
                                        <div class="line20">
                                            <div class="" x-text="product.name"></div>
                                            <div :class="product.status === 'available' ? 'green' : 'red'" x-text="product.status === 'available' ? 'موجود' : 'ناموجود'"></div>
                                        </div>

                                        <div class="">
                                            <!-- <div class="offer">20%</div> -->
                                            <div class=""><span class="font-size-1-2" x-text="new Intl.NumberFormat('fa-IR').format(product.price)"></span><span> تومان</span></div>
                                        </div>
                                    </label>
                                </div>
                            </template>

                            <div x-show="filteredProducts.length === 0" class="text-center py-4">
                                <p>محصولی یافت نشد.</p>
                            </div>

                        </div>
                    </div>

                    <!-- Custom Fields Form -->
                    <form id="purchaseForm" method="POST" action="/api/payment/start" @submit.prevent="submitOrder">

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="product_id" :value="selectedProduct?.id">

                        <div x-show="selectedProduct && customFields.length > 0" class="d-flex-wrap align-end mt-20 pd-20 bg-light-purple radius-20">

                            <div class="d-flex align-center ml-20">
                                <div class="line-nr ml-5"><i class="icon-personalcard-1 font-size-4 gr-text"></i></div>
                                <div class="font-size-1-5 line20 font-bold gr-text">
                                    اطلاعات <br> مورد نیاز
                                </div>
                            </div>

                            <div class="list-input grow-1" style="margin: -5px;">
                                <template x-for="field in customFields" :key="field.name">
                                    <div class="input-item">
                                        <div class="input-label purple-gray" x-text="field.label"></div>

                                        <!-- Text/Number/Email/Tel Input -->
                                        <template x-if="['text', 'number', 'email', 'tel'].includes(field.type)">
                                            <div class="input-text bg-white">
                                                <input
                                                    :type="field.type"
                                                    :name="field.name"
                                                    :placeholder="field.placeholder || field.label"
                                                    :required="field.required"
                                                    class="text-right"
                                                    dir="ltr"
                                                >
                                                <div class="icon border-right"><i class="icon-edit-2 gr-text"></i></div>
                                            </div>
                                        </template>

                                        <!-- Textarea -->
                                        <template x-if="field.type === 'textarea'">
                                            <div class="input-text bg-white">
                                                <textarea
                                                    :name="field.name"
                                                    :placeholder="field.placeholder || field.label"
                                                    :required="field.required"
                                                    class="text-right w-full p-2"
                                                    rows="2"
                                                ></textarea>
                                            </div>
                                        </template>

                                        <!-- Select -->
                                        <template x-if="field.type === 'select'">
                                            <div class="input-text bg-white">
                                                <select :name="field.name" :required="field.required" class="w-full bg-transparent p-2">
                                                    <option value="" disabled selected>انتخاب کنید...</option>
                                                    <template x-for="option in field.options" :key="option.value">
                                                        <option :value="option.value" x-text="option.label"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                        </div>

                        <!-- Submit Button Section -->
                        <div x-show="selectedProduct" class="mt-20 pd-20 bg-light-purple radius-20 ">

                            <div class="d-flex-wrap" style="margin: -10px;">
                                <div class="pr-feature-list m-10 grow-2 basis300">
                                    <ul>
                                        <li>تحویل فوری و اتوماتیک</li>
                                        <li>پشتیبانی ۲۴ ساعته</li>
                                    </ul>
                                </div>

                                <div class="basis200 grow-1 m-10 self-end mob-order-1">
                                    <div class="d-flex just-between mb-20">
                                        <div class="line24">
                                            <div class="">مبلغ قابل پرداخت:</div>
                                            <div class=""><span class="gr-text font-size-1-5" x-text="new Intl.NumberFormat('fa-IR').format(selectedProduct?.price || 0)"></span> <span> تومان</span></div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn bg-gr full-width">پرداخت نهایی</button>
                                </div>
                            </div>

                        </div>
                    </form>

                    <div x-show="!selectedProduct" class="mt-20 text-center text-gray-500">
                        لطفا یک محصول را انتخاب کنید.
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Features -->
    <div class="section pd-td-40">
        <div class="center ">
            <div class="max-w400 text-center m-auto mb-40">
                <h2 class="title-size-4">چرا ما را انتخاب میکنید؟</h2>
                <p>
                    خدمات متمایز و پشتیبانی حرفه‌ای، تفاوت ماست.
                </p>
            </div>

            <div class="max-w1000 m-auto">
                <div class="feature-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="family-gr-purple  pd-30 radius-20">
                                <div class="bg-white pd-30 radius-20">
                                    <div class="img pd-td-20"><img src="/template-2/images/feature-1.svg" class="m-auto" alt=""></div>
                                </div>
                                <div class="text-center mt-20">
                                    <h3 class="line38">تحویل فوری</h3>
                                    <p>سفارشات شما در سریع‌ترین زمان ممکن پردازش می‌شوند.</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="family-gr-purple  pd-30 radius-20">
                                <div class="bg-white pd-30 radius-20">
                                    <div class="img pd-td-20"><img src="/template-2/images/feature-2.svg" class="m-auto" alt=""></div>
                                </div>
                                <div class="text-center mt-20">
                                    <h3 class="line38">امنیت بالا</h3>
                                    <p>تمام اطلاعات شما به صورت رمزنگاری شده و امن نگهداری می‌شود.</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="family-gr-purple  pd-30 radius-20">
                                <div class="bg-white pd-30 radius-20">
                                    <div class="img pd-td-20"><img src="/template-2/images/feature-3.svg" class="m-auto" alt=""></div>
                                </div>
                                <div class="text-center mt-20">
                                    <h3 class="line38">پشتیبانی ۲۴/۷</h3>
                                    <p>تیم پشتیبانی ما در تمام ساعات شبانه روز پاسخگوی شماست.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Slider -->
    <div class="section pd-td-40">
        <div class="center">
            <div class="bg-light-purple radius-30 pd-30 overhide">
                <div class="text-center m-auto m-td-40 radius-20">
                    <h2 class="title-size-3 font purple-gray">
                        آخرین مطالب بلاگ
                    </h2>
                    <div class="d-flex just-center">
                        <a href="/blog" class="btn border m-5 purple-gray">مشاهده همه <i class="icon-arrow-left mr-5"></i></a>
                    </div>
                </div>

                <div class="blog-slider" x-show="$store.appStore.blogPosts.length > 0">
                    <div class="swiper-wrapper">
                        <template x-for="post in $store.appStore.blogPosts" :key="post.id">
                            <div class="swiper-slide">
                                <div class="overhide radius-20 opc-slide bg-white h-full flex flex-col">
                                    <a :href="'/blog/' + post.slug" class="img block aspect-video w-full overflow-hidden">
                                        <img :src="post.imageUrl" class="w-full h-full object-cover" :alt="post.title">
                                    </a>
                                    <div class="pd-20 flex-1 flex flex-col">
                                        <h2 class="ellipsis-y line-clamp-2 font-size-1-2 mb-2">
                                            <a :href="'/blog/' + post.slug" class="color-text" x-text="post.title"></a>
                                        </h2>
                                        <p class="ellipsis-y line-clamp-3 pd-td-10 color-bright line24 flex-1" x-text="post.excerpt"></p>
                                        <div class="d-flex align-center color-bright nowrap overhide mt-auto pt-4">
                                            <div class="ml-5">
                                                <i class="icon-calendar-2"></i>
                                                <time x-text="post.date" class="ellipsis-x"></time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="d-flex just-center mt-20">
                        <div class="btn border m-5 blog-next"><i class="icon-arrow-right-1 ml-5"></i> بعدی</div>
                        <div class="btn border m-5 blog-prev">قبلی <i class="icon-arrow-left mr-5"></i></div>
                    </div>
                </div>

                <div x-show="!$store.appStore.blogPosts.length" class="text-center p-4">
                    مطلبی برای نمایش وجود ندارد.
                </div>

            </div>
        </div>
    </div>
</div>

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
        Alpine.store('appStore').reviews = data.reviews || [];
        Alpine.store('appStore').brands = data.brands || [];
        Alpine.store('appStore').blogPosts = data.blogPosts || [];
    }

    return {
        categories: [],
        products: [],
        activeCategory: 'all',
        selectedProduct: null,
        customFields: [],
        isUserLoggedIn: false,

        init() {
            this.categories = data.categories || [];
            this.products = data.products || [];
            this.isUserLoggedIn = data.isUserLoggedIn || false;

            // Auto select first category if needed, or 'all'
            // this.activeCategory = this.categories.length > 0 ? this.categories[0].id : 'all';
        },

        get filteredProducts() {
            if (this.activeCategory === 'all') return this.products;
            return this.products.filter(p => p.category == this.activeCategory);
        },

        setActiveCategory(categoryId) {
            this.activeCategory = categoryId;
            this.selectedProduct = null;
            this.customFields = [];
        },

        selectProduct(product) {
            if (product.status !== 'available') {
                // Using the template's cute-alert or standard alert
                if (window.cuteToast) {
                    cuteToast({ type: 'warning', message: 'این محصول در حال حاضر موجود نیست.', timer: 3000 });
                } else {
                    alert('این محصول در حال حاضر موجود نیست.');
                }
                return;
            }

            // Auth Check
            const isLoggedIn = (Alpine.store('auth') && Alpine.store('auth').check()) || this.isUserLoggedIn;

            if (!isLoggedIn) {
                window.dispatchEvent(new CustomEvent('open-auth-modal'));
                return;
            }

            this.selectedProduct = product;
            this.customFields = [];

            // Fetch custom fields
            fetch(`/api/product-details/${product.id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                         if (window.cuteToast) {
                            cuteToast({ type: 'error', message: data.error, timer: 3000 });
                        } else {
                            alert(data.error);
                        }
                        this.selectedProduct = null;
                    } else {
                        // Transform custom_fields if needed (parsing options)
                        this.customFields = data.custom_fields.map(field => {
                            if (field.type === 'select' || field.type === 'radio') {
                                // Backend might send options as a string or array?
                                // Memory says "options column ... stores values as newline-separated strings"
                                // BUT `ApiController::productDetails` parses it.
                                // Let's assume it is parsed.
                            }
                            return field;
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.selectedProduct = null;
                });
        },

        submitOrder() {
            if (!this.selectedProduct) return;

            const form = document.getElementById('purchaseForm');
            const formData = new FormData(form);

            const payloadFields = [];

            // Collect custom fields
            this.customFields.forEach(field => {
                let val = null;
                if (field.type === 'checkbox') {
                    const values = formData.getAll(field.name + '[]'); // if array
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

            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                    const msg = body.error || 'خطا در اتصال به درگاه پرداخت.';
                     if (window.cuteToast) {
                        cuteToast({ type: 'error', message: msg, timer: 5000 });
                    } else {
                        alert(msg);
                    }
                }
            }).catch(error => {
                console.error('Error submitting order:', error);
                 if (window.cuteToast) {
                    cuteToast({ type: 'error', message: 'خطای پیش‌بینی نشده در پرداخت.', timer: 5000 });
                } else {
                    alert('خطای پیش‌بینی نشده در پرداخت.');
                }
            });
        }
    }
}
</script>

<?php include 'footer.tpl'; ?>