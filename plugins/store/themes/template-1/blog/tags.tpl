<?php include __DIR__ . '/../header.tpl'; ?>

<main class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-center mb-12 text-gray-900 dark:text-white">
            <?= htmlspecialchars($pageTitle) ?>
        </h1>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 p-8">
            <div class="flex flex-wrap gap-4 justify-center">
                <?php foreach ($tags as $tag) : ?>
                    <a href="/blog/tags/<?= $tag['slug'] ?>" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 px-6 py-3 rounded-full text-base font-bold transition-all shadow-sm hover:shadow-md border border-transparent hover:border-primary-200 dark:hover:border-primary-800">
                        # <?= htmlspecialchars($tag['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../footer.tpl'; ?>
