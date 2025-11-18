<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">مدیریت مدیران</h1>
        <!-- <a href="/admins/create" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            افزودن مدیر جدید
        </a> -->
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نام</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نام کاربری</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">ایمیل</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نقش</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($admin['name']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($admin['username']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($admin['email']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($admin['role']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                             <span class="px-2 py-1 font-semibold leading-tight rounded-full <?= $admin['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                                <?= $admin['status'] === 'active' ? 'فعال' : 'غیرفعال' ?>
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            <!-- Edit/Delete links can be added here -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
