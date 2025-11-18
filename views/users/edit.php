<h1 class="text-3xl font-bold mb-6">ویرایش کاربر: <?= htmlspecialchars($user['name']) ?></h1>

<div class="bg-white shadow-md rounded-lg p-8">
        <form action="<?php echo url('/users/update') . $user['id']?>" method="POST"  enctype="multipart/form-data" accept-charset="UTF-8">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">نام</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="mb-4">
            <label for="mobile" class="block text-gray-700 text-sm font-bold mb-2">موبایل</label>
            <input type="text" id="mobile" name="mobile" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= htmlspecialchars($user['mobile']) ?>" required>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>فعال</option>
                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>غیرفعال</option>
                <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>مسدود</option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                به‌روزرسانی
            </button>
            <a href="/users" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                انصراف
            </a>
        </div>
    </form>
</div>
