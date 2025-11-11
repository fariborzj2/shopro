<h1 class="text-3xl font-bold mb-6">افزودن نوشته جدید</h1>

<div class="bg-white shadow-md rounded-lg p-8">
    <form action="/blog/posts/store" method="POST">
        <?php partial('csrf_field'); ?>
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">عنوان</label>
            <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">اسلاگ (Slug)</label>
            <input type="text" id="slug" name="slug" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">دسته‌بندی</label>
                <select id="category_id" name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">— انتخاب دسته‌بندی —</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name_fa']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="author_id" class="block text-gray-700 text-sm font-bold mb-2">نویسنده</label>
                <select id="author_id" name="author_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">— انتخاب نویسنده —</option>
                    <?php foreach ($authors as $author): ?>
                        <option value="<?= $author['id'] ?>"><?= htmlspecialchars($author['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label for="excerpt" class="block text-gray-700 text-sm font-bold mb-2">چکیده</label>
            <textarea id="excerpt" name="excerpt" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
        </div>

        <div class="mb-4">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">محتوای اصلی</label>
            <textarea id="content" name="content" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="draft" selected>پیش‌نویس</option>
                <option value="published">منتشر شده</option>
                <option value="scheduled">زمان‌بندی شده</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="published_at_jalali" class="block text-gray-700 text-sm font-bold mb-2">تاریخ انتشار (اختیاری):</label>
            <input type="text" id="published_at_jalali" name="published_at_jalali" autocomplete="off"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <input type="hidden" id="published_at" name="published_at">
        </div>

        <div class="mb-4">
            <label for="tags" class="block text-gray-700 text-sm font-bold mb-2">برچسب‌ها:</label>
            <select id="tags" name="tags[]" multiple class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32">
                <?php foreach ($tags as $tag): ?>
                    <option value="<?= $tag['id'] ?>"><?= htmlspecialchars($tag['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                ذخیره
            </button>
            <a href="/blog/posts" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                انصراف
            </a>
        </div>
    </form>
</div>

<script>
    // Initialize KamaDatepicker
    kamaDatepicker('published_at_jalali', {
        twelveHour: false,
        gotoToday: true,
        buttonsColor: "blue",
    });

    // Sync the Jalali date to the hidden field before form submission
    document.querySelector('form').addEventListener('submit', function() {
        const jalaliDate = document.getElementById('published_at_jalali').value;
        document.getElementById('published_at').value = jalaliDate;
    });
</script>
