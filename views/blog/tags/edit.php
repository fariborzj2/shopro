<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold mb-4">ویرایش برچسب</h1>

    <form action="/blog/tags/update/<?= $tag['id'] ?>" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">نام:</label>
            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($tag['name']) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">اسلاگ:</label>
            <input type="text" id="slug" name="slug" required value="<?= htmlspecialchars($tag['slug']) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت:</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="active" <?= $tag['status'] === 'active' ? 'selected' : '' ?>>فعال</option>
                <option value="inactive" <?= $tag['status'] === 'inactive' ? 'selected' : '' ?>>غیرفعال</option>
            </select>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                به‌روزرسانی
            </button>
        </div>
    </form>
</div>
