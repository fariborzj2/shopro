<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">مدیریت دسته‌بندی‌های وبلاگ</h1>
    <a href="/blog/categories/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        افزودن دسته‌بندی جدید
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    نام فارسی
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    اسلاگ (Slug)
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    والد
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    وضعیت
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($category['name_fa']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-mono"><?= htmlspecialchars($category['slug']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($category['parent_name'] ?? '—') ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?= $category['status'] === 'active' ? 'text-green-900' : 'text-red-900' ?>">
                            <span aria-hidden class="absolute inset-0 <?= $category['status'] === 'active' ? 'bg-green-200' : 'bg-red-200' ?> opacity-50 rounded-full"></span>
                            <span class="relative"><?= $category['status'] === 'active' ? 'فعال' : 'غیرفعال' ?></span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            <a href="/blog/categories/edit/<?= $category['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">ویرایش</a>
                            <form action="/blog/categories/delete/<?= $category['id'] ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این دسته‌بندی مطمئن هستید؟');">
                                <?php partial('csrf_field'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                            </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
