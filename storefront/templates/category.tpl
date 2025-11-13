<?php partial('storefront/header', ['title' => $pageTitle]) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?= $category->name_fa ?></h1>
            <p><?= $category->description ?></p>
        </div>
    </div>
    <div class="row">
        <?php foreach (json_decode($store_data)->products as $product) : ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?= $product->imageUrl ?>" class="card-img-top" alt="<?= $product->name ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product->name ?></h5>
                        <p class="card-text"><?= $product->price ?> تومان</p>
                        <button class="btn btn-primary" @click="showReviews(<?= $product->id ?>)">View Reviews</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div x-data="reviews" x-show="showModal" class="modal fade" id="reviewsModal" tabindex="-1" role="dialog" aria-labelledby="reviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewsModalLabel">Reviews for <span x-text="product.name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="showModal = false">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="reviews-list">
                    <template x-for="review in product.reviews" :key="review.id">
                        <div class="review">
                            <p><strong><span x-text="review.user_name"></span></strong> - <span x-text="review.rating"></span>/5</p>
                            <p x-text="review.comment"></p>
                            <template x-if="review.admin_reply">
                                <div class="admin-reply">
                                    <p><strong>Admin Reply:</strong></p>
                                    <p x-text="review.admin_reply"></p>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
                <hr>
                <div class="review-form">
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <form action="/reviews/store" method="POST">
                            <input type="hidden" name="product_id" :value="product.id">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?= $_SESSION['user_name'] ?? '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input type="text" name="mobile" id="mobile" class="form-control" value="<?= $_SESSION['user_mobile'] ?? '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="rating">Rating</label>
                                <div class="rating">
                                    <template x-for="i in 5">
                                        <span @click="rating = i" :class="{ 'fas fa-star': i <= rating, 'far fa-star': i > rating }"></span>
                                    </template>
                                </div>
                                <input type="hidden" name="rating" x-model="rating">
                            </div>
                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    <?php else : ?>
                        <p>برای ثبت نظر ابتدا وارد حساب کاربری شوید.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reviews', () => ({
            showModal: false,
            product: {},
            rating: 0,
            showReviews(productId) {
                this.product = <?= $store_data ?>.products.find(p => p.id === productId);
                this.showModal = true;
                $('#reviewsModal').modal('show');
            }
        }))
    })
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
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
            },
            "review": [
                <?php foreach ($reviews[$product->id] as $reviewIndex => $review) : ?>
                {
                    "@type": "Review",
                    "author": {
                        "@type": "Person",
                        "name": "<?= $review['user_name'] ?>"
                    },
                    "reviewRating": {
                        "@type": "Rating",
                        "ratingValue": "<?= $review['rating'] ?>"
                    },
                    "reviewBody": "<?= $review['comment'] ?>"
                }<?= $reviewIndex < count($reviews[$product->id]) - 1 ? ',' : '' ?>
                <?php endforeach; ?>
            ]
        }<?= $index < count(json_decode($store_data)->products) - 1 ? ',' : '' ?>
        <?php endforeach; ?>
    ]
}
</script>

<?php partial('storefront/footer') ?>
