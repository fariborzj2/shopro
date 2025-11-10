<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">مدیریت کاربران</h1>
    <a href="/users/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        افزودن کاربر جدید
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    نام
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    موبایل
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    تاریخ عضویت
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    وضعیت
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($user['name']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($user['mobile']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= date('Y-m-d', strtotime($user['created_at'])) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <?php
                            $status_classes = [
                                'active' => 'text-green-900 bg-green-200',
                                'inactive' => 'text-yellow-900 bg-yellow-200',
                                'banned' => 'text-red-900 bg-red-200',
                            ];
                            $status_text = [
                                'active' => 'فعال',
                                'inactive' => 'غیرفعال',
                                'banned' => 'مسدود',
                            ];
                            $class = $status_classes[$user['status']] ?? 'text-gray-900 bg-gray-200';
                            $text = $status_text[$user['status']] ?? 'نامشخص';
                        ?>
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?= $class ?>">
                            <span aria-hidden class="absolute inset-0 opacity-50 rounded-full <?= explode(' ', $class)[1] ?>"></span>
                            <span class="relative"><?= htmlspecialchars($text) ?></span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a href="/users/edit/<?= $user['id'] ?>" class="text-indigo-600 hover:text-indigo-900">ویرایش</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
