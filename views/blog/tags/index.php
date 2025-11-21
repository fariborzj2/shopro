<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">مدیریت برچسب‌ها</h1>
        <a href="/blog/tags/create" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            افزودن برچسب جدید
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نام</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">اسلاگ</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tags as $tag): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($tag['id']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($tag['name']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($tag['slug']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full <?= $tag['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                                <?= $tag['status'] === 'active' ? 'فعال' : 'غیرفعال' ?>
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            <a href="/blog/tags/edit/<?= $tag['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">ویرایش</a>
                            <form action="/blog/tags/delete/<?= $tag['id'] ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این برچسب مطمئن هستید؟');">
                                <?php partial('csrf_field'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
