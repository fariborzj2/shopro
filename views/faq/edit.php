<?php partial('header'); ?>

<h1 class="text-3xl font-bold mb-6">ویرایش سوال</h1>

<div class="bg-white shadow-md rounded-lg p-8">
    <form action="<?= url('/faq/update/' . $item['id']) ?>" method="POST">
        <div class="mb-4">
            <label for="question" class="block text-gray-700 text-sm font-bold mb-2">سوال</label>
            <input type="text" id="question" name="question" value="<?= htmlspecialchars($item['question']) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label for="answer" class="block text-gray-700 text-sm font-bold mb-2">پاسخ</label>
            <textarea id="answer" name="answer" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"><?= htmlspecialchars($item['answer']) ?></textarea>
        </div>

        <div class="mb-4">
            <label for="position" class="block text-gray-700 text-sm font-bold mb-2">ترتیب نمایش</label>
            <input type="number" id="position" name="position" value="<?= htmlspecialchars($item['position']) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <option value="active" <?= $item['status'] === 'active' ? 'selected' : '' ?>>فعال</option>
                <option value="inactive" <?= $item['status'] === 'inactive' ? 'selected' : '' ?>>غیرفعال</option>
            </select>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">به‌روزرسانی</button>
            <a href="<?= url('/faq') ?>" class="text-gray-600">انصراف</a>
        </div>
    </form>
</div>

<?php partial('footer'); ?>
