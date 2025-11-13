<?php include 'header.tpl'; ?>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">بلاگ</h1>

    <!-- Search and Filter Form -->
    <form method="GET" action="/blog" class="mb-8 bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="sr-only">جستجو</label>
                <input type="text" name="search" id="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="جستجو در مطالب..." class="w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label for="category" class="sr-only">دسته‌بندی</label>
                <select name="category" id="category" class="w-full border-gray-300 rounded-md">
                    <option value="">همه دسته‌بندی‌ها</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id ?>" <?= ($selected_category ?? '') == $category->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category->name_fa) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md">فیلتر</button>
            </div>
        </div>
    </form>

    <!-- Blog Posts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($posts as $post): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <a href="/blog/<?= $post->slug ?>">
                    <img src="<?= htmlspecialchars($post->featured_image ?? 'https://placehold.co/600x400/EEE/31343C?text=No+Image') ?>" alt="<?= htmlspecialchars($post->title) ?>" class="w-full h-48 object-cover">
                </a>
                <div class="p-6">
                    <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($post->category_name) ?></p>
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
        <?= $paginator->render() ?>
    </div>
</div>

<?php include 'footer.tpl'; ?>
