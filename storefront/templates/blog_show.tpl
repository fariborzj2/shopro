<?php include 'header.tpl'; ?>

<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <article>
        <!-- Post Header -->
        <header class="mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2"><?= htmlspecialchars($post->title) ?></h1>
            <div class="text-sm text-gray-500">
                <span>نویسنده: <?= htmlspecialchars($post->author_name) ?></span>
                <span class="mx-2">&bull;</span>
                <span>تاریخ انتشار: <?= date('Y/m/d', strtotime($post->published_at)) ?></span>
                <span class="mx-2">&bull;</span>
                <a href="/blog/category/<?= $post->category_slug ?>" class="text-indigo-600 hover:underline"><?= htmlspecialchars($post->category_name) ?></a>
            </div>
        </header>

        <!-- Featured Image -->
        <?php if ($post->featured_image): ?>
            <img src="<?= htmlspecialchars($post->featured_image) ?>" alt="<?= htmlspecialchars($post->title) ?>" class="w-full rounded-lg shadow-md mb-8">
        <?php endif; ?>

        <!-- Post Content -->
        <div class="prose max-w-none">
            <?= $post->content ?>
        </div>

        <!-- Tags -->
        <?php if (!empty($post->tags)): // Assuming tags are passed to the view ?>
            <div class="mt-8">
                <?php foreach ($post->tags as $tag): ?>
                    <a href="/blog/tag/<?= $tag->slug ?>" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                        #<?= htmlspecialchars($tag->name) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </article>

    <!-- FAQ Section -->
    <?php if (!empty($faq_items)): ?>
        <section class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">پرسش‌های متداول</h2>
            <div class="space-y-4">
                <?php foreach ($faq_items as $faq): ?>
                    <details class="bg-gray-50 p-4 rounded-lg">
                        <summary class="font-semibold cursor-pointer"><?= htmlspecialchars($faq->question) ?></summary>
                        <p class="mt-2 text-gray-700"><?= htmlspecialchars($faq->answer) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

</div>

<?php include 'footer.tpl'; ?>
