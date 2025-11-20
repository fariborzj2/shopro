<div class="w-full max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">ویرایش مدیر: <?php echo htmlspecialchars($admin['name']); ?></h1>
        <a href="/admin/admins" class="text-blue-600 hover:underline">بازگشت به لیست</a>
    </div>

    <form action="/admin/admins/update/<?php echo $admin['id']; ?>" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <?php csrf_field(); ?>

        <?php include '_form.php'; ?>

        <div class="flex items-center justify-end mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                بروزرسانی اطلاعات
            </button>
        </div>
    </form>
</div>
