<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">مدیریت نوشته‌ها</h1>
    <a href="/blog/posts/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        افزودن نوشته جدید
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    عنوان
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    نویسنده
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    دسته‌بندی
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    تاریخ انتشار
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    وضعیت
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($post['title']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($post['author_name'] ?? '—') ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($post['category_name'] ?? '—') ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            <?= $post['published_at'] ? date('Y-m-d', strtotime($post['published_at'])) : '—' ?>
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <?php
                            $status_classes = [
                                'published' => 'text-green-900 bg-green-200',
                                'draft' => 'text-yellow-900 bg-yellow-200',
                                'scheduled' => 'text-blue-900 bg-blue-200',
                            ];
                            $status_text = [
                                'published' => 'منتشر شده',
                                'draft' => 'پیش‌نویس',
                                'scheduled' => 'زمان‌بندی شده',
                            ];
                            $class = $status_classes[$post['status']] ?? 'text-gray-900 bg-gray-200';
                            $text = $status_text[$post['status']] ?? 'نامشخص';
                        ?>
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?= explode(' ', $class)[0] ?>">
                            <span aria-hidden class="absolute inset-0 opacity-50 rounded-full <?= explode(' ', $class)[1] ?>"></span>
                            <span class="relative"><?= htmlspecialchars($text) ?></span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a href="/blog/posts/edit/<?= $post['id'] ?>" class="text-indigo-600 hover:text-indigo-900">ویرایش</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
