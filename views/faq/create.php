<?php partial('header'); ?>

<h1 class="text-3xl font-bold mb-6">افزودن سوال جدید</h1>

<div class="bg-white shadow-md rounded-lg p-8">
    <form action="<?= url('/faq/store') ?>" method="POST">
        <div class="mb-4">
            <label for="question" class="block text-gray-700 text-sm font-bold mb-2">سوال</label>
            <input type="text" id="question" name="question" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label for="answer" class="block text-gray-700 text-sm font-bold mb-2">پاسخ</label>
            <textarea id="answer" name="answer" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"></textarea>
        </div>

        <div class="mb-4">
            <label for="position" class="block text-gray-700 text-sm font-bold mb-2">ترتیب نمایش</label>
            <input type="number" id="position" name="position" value="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <option value="active">فعال</option>
                <option value="inactive">غیرفعال</option>
            </select>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">ذخیره</button>
            <a href="<?= url('/faq') ?>" class="text-gray-600">انصراف</a>
        </div>
    </form>
</div>

<?php partial('footer'); ?>
