<?php include 'header.tpl'; ?>
<main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6"><?php echo $category->name; ?></h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($products as $product): ?>
            <div class="border rounded-lg p-4 hover:shadow-lg transition">
                <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-48 object-cover mb-4">
                <h2 class="text-xl font-semibold"><?php echo $product['name']; ?></h2>
                <p class="text-gray-600"><?php echo number_format($product['price']); ?> تومان</p>
                <a href="/product/<?php echo $product['id']; ?>" class="block mt-4 text-center bg-primary-600 text-white py-2 rounded">مشاهده</a>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?php include 'footer.tpl'; ?>
