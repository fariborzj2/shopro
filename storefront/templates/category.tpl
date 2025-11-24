<?php partial('storefront/header', ['title' => $pageTitle]) ?>

<style>
    /* Category Header */
    .category-header {
        padding-block: 4rem 3rem;
        text-align: center;
        background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 100%);
        border-bottom: 1px solid var(--color-border);
        margin-bottom: 3rem;
    }

    .category-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1rem;
        color: var(--color-text-main);
    }

    .category-desc {
        font-size: 1.125rem;
        color: var(--color-text-muted);
        max-width: 700px;
        margin-inline: auto;
    }

    /* Product Grid (Reuse from Index but ensure scope) */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    /* Cards */
    .product-card {
        background: white;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--color-border);
        transition: var(--transition-smooth);
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-glass);
    }

    .product-img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        background: #f8fafc;
    }

    .product-info {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: var(--color-text-main);
    }

    .product-price {
        color: var(--color-primary);
        font-weight: 600;
        margin-top: auto;
        font-size: 1.05rem;
    }

    /* Reviews Modal Specifics */
    .review-item {
        padding: 1rem;
        border-bottom: 1px solid #eee;
        margin-bottom: 1rem;
    }
    .review-item:last-child { border-bottom: none; }

    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .star-rating { color: #fbbf24; } /* Amber-400 */
    .star-rating-input { cursor: pointer; font-size: 1.5rem; color: #d1d5db; transition: color 0.2s; }
    .star-rating-input.active { color: #fbbf24; }

    .admin-reply-box {
        background: #f0f9ff;
        padding: 0.75rem;
        border-radius: var(--radius-md);
        margin-top: 0.75rem;
        font-size: 0.9rem;
        border-right: 3px solid var(--color-primary);
    }
</style>

<div class="container">

    <!-- Category Header -->
    <header class="category-header">
        <h1 class="category-title"><?= htmlspecialchars($category->name_fa) ?></h1>
        <p class="category-desc"><?= htmlspecialchars($category->description) ?></p>
    </header>

    <!-- Products Grid -->
    <div class="product-grid">
        <?php foreach (json_decode($store_data)->products as $product) : ?>
            <article class="product-card glass-panel">
                <img src="<?= $product->imageUrl ?>" class="product-img" alt="<?= $product->name ?>" loading="lazy">
                <div class="product-info">
                    <h2 class="product-name"><?= $product->name ?></h2>
                    <p class="product-price"><?= number_format($product->price) ?> تومان</p>
                    <button class="btn btn-ghost" style="width: 100%; margin-top: 1rem;" @click="showReviews(<?= $product->id ?>)">
                        مشاهده نظرات
                    </button>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<!-- Reviews Modal (Refactored to Alpine + Glassmorphism) -->
<div
    x-data="reviews"
    x-show="showModal"
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
    <div class="absolute inset-0" @click="showModal = false"></div>

    <div
        class="modal-content glass-panel"
        style="max-width: 800px; max-height: 85vh; overflow-y: auto; padding: 0;"
        x-transition:enter="slide-up-enter-active"
        x-transition:enter-start="slide-up-enter-from"
        x-transition:enter-end="slide-up-enter-to"
        x-transition:leave="slide-up-leave-active"
        x-transition:leave-start="slide-up-leave-from"
        x-transition:leave-end="slide-up-leave-to"
    >
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: white; z-index: 10;">
            <h5 style="font-size: 1.25rem; font-weight: 700;">نظرات محصول: <span x-text="product.name" style="color: var(--color-primary);"></span></h5>
            <button @click="showModal = false" style="font-size: 1.5rem; line-height: 1;">&times;</button>
        </div>

        <div style="padding: 2rem;">
            <!-- Reviews List -->
            <div class="reviews-list">
                <template x-for="review in product.reviews" :key="review.id">
                    <div class="review-item">
                        <div class="review-header">
                            <strong><span x-text="review.user_name"></span></strong>
                            <span class="star-rating">
                                <span x-text="review.rating"></span>/5 ★
                            </span>
                        </div>
                        <p x-text="review.comment" style="color: var(--color-text-muted);"></p>

                        <template x-if="review.admin_reply">
                            <div class="admin-reply-box">
                                <p style="font-weight: 600; margin-bottom: 0.25rem;">پاسخ مدیر:</p>
                                <p x-text="review.admin_reply"></p>
                            </div>
                        </template>
                    </div>
                </template>
                <div x-show="!product.reviews || product.reviews.length === 0" style="text-align: center; color: var(--color-text-muted); padding: 2rem;">
                    هنوز نظری برای این محصول ثبت نشده است.
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--color-border); margin-block: 2rem;">

            <!-- Review Form -->
            <div class="review-form">
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <h4 style="font-weight: 700; margin-bottom: 1.5rem;">ثبت نظر جدید</h4>
                    <form action="/reviews/store" method="POST">
                        <input type="hidden" name="product_id" :value="product.id">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div class="form-group">
                                <label for="name" class="form-label">نام</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?= $_SESSION['user_name'] ?? '' ?>" readonly style="background: #f3f4f6;">
                            </div>
                            <div class="form-group">
                                <label for="mobile" class="form-label">موبایل</label>
                                <input type="text" name="mobile" id="mobile" class="form-control" value="<?= $_SESSION['user_mobile'] ?? '' ?>" readonly style="background: #f3f4f6;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">امتیاز</label>
                            <div class="rating" style="display: flex; gap: 0.5rem; direction: ltr; justify-content: flex-end;">
                                <template x-for="i in 5">
                                    <span
                                        @click="rating = i"
                                        class="star-rating-input"
                                        :class="{ 'active': i <= rating }"
                                        style="font-size: 2rem;"
                                    >★</span>
                                </template>
                            </div>
                            <input type="hidden" name="rating" x-model="rating" required>
                        </div>

                        <div class="form-group">
                            <label for="comment" class="form-label">دیدگاه شما</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">ثبت نظر</button>
                    </form>
                <?php else : ?>
                    <div style="text-align: center; background: #f8fafc; padding: 1.5rem; border-radius: var(--radius-md);">
                        <p>برای ثبت نظر ابتدا <a href="#" @click.prevent="$dispatch('open-auth-modal')" style="color: var(--color-primary); font-weight: bold;">وارد حساب کاربری شوید</a>.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reviews', () => ({
            showModal: false,
            product: {},
            rating: 5,
            showReviews(productId) {
                // Parse the store_data passed from PHP (available globally or here)
                // Note: In category.tpl, $store_data is used. We need to access it properly.
                // The PHP block below injects the data.
                const storeData = <?= $store_data ?>;
                this.product = storeData.products.find(p => p.id === productId);
                this.rating = 5;
                this.showModal = true;
                // Removed jQuery modal call: $('#reviewsModal').modal('show');
            }
        }))
    })
</script>

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
                "name": "<?= $product->name ?>",
                "image": "<?= $product->imageUrl ?>",
                "offers": {
                    "@type": "Offer",
                    "price": "<?= $product->price ?>",
                    "priceCurrency": "IRR"
                },
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "<?= count($reviews[$product->id]) > 0 ? array_sum(array_column($reviews[$product->id], 'rating')) / count($reviews[$product->id]) : 0 ?>",
                    "reviewCount": "<?= count($reviews[$product->id]) ?>"
                }
            }<?= $index < count(json_decode($store_data)->products) - 1 ? ',' : '' ?>
            <?php endforeach; ?>
        ]
    }
}
</script>

<?php partial('storefront/footer') ?>
