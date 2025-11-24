<?php include __DIR__ . '/../header.tpl'; ?>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900">دسته‌بندی: <?= htmlspecialchars($category->name_fa) ?></h1>
        <?php if (!empty($category->notes)): ?>
            <p class="mt-2 text-lg text-gray-600"><?= htmlspecialchars($category->notes) ?></p>
        <?php endif; ?>
    </div>

    <!-- Blog Posts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($posts as $post): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <a href="/blog/<?= $post->slug ?>">
                    <img src="<?= htmlspecialchars($post->image_url ?? 'https://placehold.co/600x400/EEE/31343C?text=No+Image') ?>" alt="<?= htmlspecialchars($post->title) ?>" class="w-full h-48 object-cover">
                </a>
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-2">
                        <a href="/blog/<?= $post->slug ?>" class="hover:text-blue-600"><?= htmlspecialchars($post->title) ?></a>
                    </h2>
                    <p class="text-gray-700 mb-4"><?= htmlspecialchars($post->excerpt) ?></p>
                    <div class="text-sm text-gray-500">
                        <span><?= htmlspecialchars($post->author_name) ?></span>
                        <span class="mx-2">&bull;</span>
                        <span><?= date('Y/m/d', strtotime($post->published_at)) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        <?php if ($paginator->total_pages > 1) : ?>
            <nav>
                <ul class="pagination">
                    <?php if ($paginator->hasPrev()) : ?>
                        <li class="page-item"><a class="page-link" href="<?= $paginator->getPrevUrl() ?>">قبلی</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $paginator->total_pages; $i++) : ?>
                        <li class="page-item <?= ($i == $paginator->current_page) ? 'active' : '' ?>"><a class="page-link" href="<?= $paginator->buildUrl($i) ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($paginator->hasNext()) : ?>
                        <li class="page-item"><a class="page-link" href="<?= $paginator->getNextUrl() ?>">بعدی</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../footer.tpl'; ?>
