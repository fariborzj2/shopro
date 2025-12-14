<?php
use App\Core\Hook;
?>

<?php include 'header.tpl'; ?>

<main class="flex-grow">
    <?php Hook::trigger('home_products'); ?>
</main>

<?php include 'partials/_purchase_modal.tpl'; ?>

<script>
function store(data) {
    if (!Alpine.store('appStore')) {
        Alpine.store('appStore', {
            reviews: data.reviews || [],
            brands: data.brands || [],
            blogPosts: data.blogPosts || []
        });
    }
    return {
        init() {},
        get filteredProducts() { return data.products || []; },
        selectProduct(product) {}
    }
}
</script>

<?php include 'footer.tpl'; ?>
