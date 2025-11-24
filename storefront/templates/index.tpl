<?php include 'header.tpl'; ?>

<!-- Page-Specific Styles -->
<style>
    /* Hero Section */
    .hero-section {
        text-align: center;
        padding-block: 5rem 4rem;
        position: relative;
    }

    .hero-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, var(--color-text-main) 0%, var(--color-primary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        font-size: 1.125rem;
        color: var(--color-text-muted);
        max-width: 600px;
        margin-inline: auto;
        margin-bottom: 2.5rem;
    }

    /* Tabs Navigation */
    .tabs-nav {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--color-border);
        scrollbar-width: none; /* Firefox */
    }
    .tabs-nav::-webkit-scrollbar { display: none; }

    .tab-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 2rem;
        font-weight: 600;
        white-space: nowrap;
        border: 1px solid transparent;
        transition: var(--transition-smooth);
        color: var(--color-text-muted);
        background: rgba(255, 255, 255, 0.3);
    }

    .tab-btn.active {
        background: var(--color-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .tab-btn:not(.active):hover {
        background: white;
        color: var(--color-text-main);
        border-color: var(--color-border);
    }

    /* Bento Grid for Products */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 2rem;
    }

    .product-card {
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition-smooth);
        border: 1px solid rgba(255,255,255,0.6);
        position: relative;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
        z-index: 2;
    }

    .product-image-wrapper {
        aspect-ratio: 4/3;
        overflow: hidden;
        background: #f1f5f9;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex-grow: 1;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--color-text-main);
    }

    .product-price {
        font-size: 1rem;
        font-weight: 600;
        color: var(--color-primary);
        margin-top: auto;
    }
</style>

<!-- App Container -->
<main
    x-data="store(<?php echo htmlspecialchars($store_data, ENT_QUOTES, 'UTF-8'); ?>)"
    x-init="init()"
    x-cloak
>
    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">تجربه خریدی بی‌نظیر</h1>
        <p class="hero-subtitle">محصولات با کیفیت و ارسال سریع را با ما تجربه کنید. انتخابی هوشمندانه برای سبک زندگی مدرن.</p>
        <div>
            <a href="#products" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">شروع خرید</a>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" style="padding-block: 4rem;">
        <!-- Category Tabs -->
        <nav class="tabs-nav" aria-label="دسته‌بندی‌ها">
            <button
                @click.prevent="setActiveCategory('all')"
                :class="{'active': activeCategory === 'all'}"
                class="tab-btn"
            >
                همه محصولات
            </button>
            <template x-for="category in categories" :key="category.id">
                <button
                    @click.prevent="setActiveCategory(category.id)"
                    :class="{'active': activeCategory === category.id}"
                    class="tab-btn"
                    x-text="category.name"
                ></button>
            </template>
        </nav>

        <!-- Product Grid -->
        <div class="product-grid">
            <template x-for="product in filteredProducts" :key="product.id">
                <article
                    @click="selectProduct(product)"
                    class="product-card glass-panel"
                >
                    <div class="product-image-wrapper">
                        <img :src="product.imageUrl" :alt="product.name" class="product-image" loading="lazy">
                    </div>
                    <div class="product-content">
                        <h3 class="product-title" x-text="product.name"></h3>
                        <p class="product-price" x-text="new Intl.NumberFormat('fa-IR').format(product.price) + ' تومان'"></p>
                    </div>
                </article>
            </template>
        </div>
    </section>

    <!-- Purchase Modal -->
    <div
        x-show="isModalOpen"
        style="display: none;"
        class="modal-overlay"
        x-transition:enter="fade-enter-active"
        x-transition:enter-start="fade-enter-from"
        x-transition:enter-end="fade-enter-to"
        x-transition:leave="fade-leave-active"
        x-transition:leave-start="fade-leave-from"
        x-transition:leave-end="fade-leave-to"
    >
        <!-- Modal Backdrop -->
        <div class="absolute inset-0" @click="isModalOpen = false"></div>

        <!-- Modal Content -->
        <div
            x-show="isModalOpen"
            class="modal-content glass-panel"
            style="max-width: 600px; max-height: 90vh; overflow-y: auto;"
            x-transition:enter="slide-up-enter-active"
            x-transition:enter-start="slide-up-enter-from"
            x-transition:enter-end="slide-up-enter-to"
            x-transition:leave="slide-up-leave-active"
            x-transition:leave-start="slide-up-leave-from"
            x-transition:leave-end="slide-up-leave-to"
            @click.outside="isModalOpen = false"
        >
             <div style="padding: 1rem;">
                <template x-if="selectedProduct">
                    <div>
                        <div class="product-image-wrapper" style="border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                            <img :src="selectedProduct.imageUrl" :alt="selectedProduct.name" class="product-image">
                        </div>

                        <h3 class="hero-title" style="font-size: 1.8rem; margin-bottom: 2rem;" x-text="selectedProduct.name"></h3>

                        <form @submit.prevent="submitOrder" id="purchaseForm" method="POST" action="/api/payment/start">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" :value="selectedProduct.id">

                            <!-- Custom Fields -->
                            <div class="form-group">
                                <template x-for="field in customFields" :key="field.id">
                                    <div style="margin-bottom: 1.5rem;">
                                        <label :for="'field_' + field.id" class="form-label">
                                            <span x-text="field.label"></span>
                                            <span x-show="field.is_required" style="color: var(--color-danger)">*</span>
                                        </label>

                                        <!-- Text/Number/Date Inputs -->
                                        <template x-if="['text', 'number', 'date', 'color'].includes(field.type)">
                                            <input
                                                :type="field.type"
                                                :name="field.name"
                                                :id="'field_' + field.id"
                                                :placeholder="field.placeholder"
                                                :required="field.is_required"
                                                class="form-control"
                                            >
                                        </template>

                                        <!-- Textarea -->
                                        <template x-if="field.type === 'textarea'">
                                            <textarea
                                                :name="field.name"
                                                :id="'field_' + field.id"
                                                :placeholder="field.placeholder"
                                                :required="field.is_required"
                                                class="form-control"
                                                rows="3"
                                            ></textarea>
                                        </template>

                                        <!-- Select -->
                                        <template x-if="field.type === 'select'">
                                            <select
                                                :name="field.name"
                                                :id="'field_' + field.id"
                                                :required="field.is_required"
                                                class="form-control"
                                            >
                                                <option value="" disabled selected>انتخاب کنید...</option>
                                                <template x-for="option in field.options" :key="option.value">
                                                    <option :value="option.value" x-text="option.label"></option>
                                                </template>
                                            </select>
                                        </template>

                                        <!-- Radio -->
                                        <template x-if="field.type === 'radio'">
                                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                                <template x-for="option in field.options" :key="option.value">
                                                    <label class="flex items-center" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                                        <input
                                                            type="radio"
                                                            :name="field.name"
                                                            :id="'field_' + field.id + '_' + option.value"
                                                            :value="option.value"
                                                            :required="field.is_required"
                                                            style="accent-color: var(--color-primary);"
                                                        >
                                                        <span x-text="option.label"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </template>

                                        <!-- Checkbox -->
                                        <template x-if="field.type === 'checkbox'">
                                             <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                                <template x-for="option in field.options" :key="option.value">
                                                    <label class="flex items-center" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                                        <input
                                                            type="checkbox"
                                                            :name="field.name + '[]'"
                                                            :id="'field_' + field.id + '_' + option.value"
                                                            :value="option.value"
                                                            style="accent-color: var(--color-primary);"
                                                        >
                                                        <span x-text="option.label"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <!-- Actions -->
                            <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 2rem;">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">پرداخت نهایی</button>
                                <button @click="isModalOpen = false" type="button" class="btn btn-ghost" style="width: 100%;">انصراف</button>
                            </div>
                        </form>
                    </div>
                </template>
             </div>
        </div>
    </div>
</main>

<!-- Alpine.js Store Logic -->
<script>
function store(data) {
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

            // Simple Skeleton / Loading state could be added here, but keeping it simple for now
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

            // Revised logic for data collection:
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

            // Get CSRF token safely from form data (fallback to meta if needed, but form data is primary for <form>)
            // Since we are using JSON fetch, we need to manually pass it in header.
            // The form has <?php echo csrf_field(); ?> which creates <input type="hidden" name="csrf_token" ...>
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

<!-- JSON-LD Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "فروشگاه مدرن",
    "url": "<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>

<?php include 'footer.tpl'; ?>
