<?php include __DIR__ . '/../header.tpl'; ?>
<main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">وبلاگ</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($posts as $post): ?>
            <div class="border rounded-lg p-4">
                <h2 class="text-xl font-bold"><a href="/blog/<?php echo $post['slug']; ?>"><?php echo $post['title']; ?></a></h2>
                <p class="text-gray-600 mt-2"><?php echo $post['excerpt']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?php include __DIR__ . '/../footer.tpl'; ?>
