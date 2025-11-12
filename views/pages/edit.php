<?php partial('header'); ?>

<h1 class="text-3xl font-bold mb-6">ویرایش صفحه: <?= htmlspecialchars($page['title']) ?></h1>

<div class="bg-white shadow-md rounded-lg p-8">
    <form action="/pages/update/<?= $page['id'] ?>" method="POST">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">عنوان</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title']) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">اسلاگ (Slug)</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($page['slug']) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">محتوا</label>
            <textarea id="content" name="content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"><?= htmlspecialchars($page['content']) ?></textarea>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <option value="draft" <?= $page['status'] === 'draft' ? 'selected' : '' ?>>پیش‌نویس</option>
                <option value="published" <?= $page['status'] === 'published' ? 'selected' : '' ?>>منتشر شده</option>
            </select>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">به‌روزرسانی</button>
            <a href="/pages" class="text-gray-600">انصراف</a>
        </div>
    </form>
</div>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/zhm469md3gofl79o8eo7l5581mn5292pu4o6zawa7p8nmna8/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/js/tinymce-config.js"></script>

<?php partial('footer'); ?>
