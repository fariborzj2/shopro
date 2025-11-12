<?php partial('header'); ?>

<h1 class="text-3xl font-bold mb-6">مدیریت صفحات</h1>

<div class="mb-4">
    <a href="<?= url('/pages/create') ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        ایجاد صفحه جدید
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">عنوان</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">اسلاگ</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($page['title']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($page['slug']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?= $page['status'] === 'published' ? 'text-green-900' : 'text-yellow-900' ?>">
                            <span aria-hidden class="absolute inset-0 <?= $page['status'] === 'published' ? 'bg-green-200' : 'bg-yellow-200' ?> opacity-50 rounded-full"></span>
                            <span class="relative"><?= $page['status'] === 'published' ? 'منتشر شده' : 'پیش‌نویس' ?></span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a href="<?= url('/pages/edit/' . $page['id']) ?>" class="text-indigo-600 hover:text-indigo-900">ویرایش</a>
                        <form action="<?= url('/pages/delete/' . $page['id']) ?>" method="POST" class="inline-block ml-4" onsubmit="return confirm('آیا از حذف این صفحه مطمئن هستید؟');">
                            <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php partial('footer'); ?>
