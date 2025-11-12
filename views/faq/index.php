<?php partial('header'); ?>

<h1 class="text-3xl font-bold mb-6">مدیریت سوالات متداول</h1>

<div class="mb-4">
    <a href="<?= url('/faq/create') ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        افزودن سوال جدید
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">سوال</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">ترتیب</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($item['question']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($item['position']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?= $item['status'] === 'active' ? 'text-green-900' : 'text-gray-900' ?>">
                            <span aria-hidden class="absolute inset-0 <?= $item['status'] === 'active' ? 'bg-green-200' : 'bg-gray-200' ?> opacity-50 rounded-full"></span>
                            <span class="relative"><?= $item['status'] === 'active' ? 'فعال' : 'غیرفعال' ?></span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a href="<?= url('/faq/edit/' . $item['id']) ?>" class="text-indigo-600 hover:text-indigo-900">ویرایش</a>
                        <form action="<?= url('/faq/delete/' . $item['id']) ?>" method="POST" class="inline-block ml-4" onsubmit="return confirm('آیا از حذف این سوال مطمئن هستید؟');">
                            <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php partial('footer'); ?>
