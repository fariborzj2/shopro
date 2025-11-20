<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">مدیریت مدیران</h1>
    <a href="/admin/admins/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        افزودن مدیر جدید
    </a>
</div>

<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-right">نام و نام کاربری</th>
                <th class="py-3 px-6 text-right">ایمیل</th>
                <th class="py-3 px-6 text-center">سمت (Role)</th>
                <th class="py-3 px-6 text-center">وضعیت</th>
                <th class="py-3 px-6 text-center">نوع دسترسی</th>
                <th class="py-3 px-6 text-center">عملیات</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            <?php foreach ($admins as $admin): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-right whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="mr-2 font-medium"><?php echo htmlspecialchars($admin['name']); ?></div>
                            <span class="text-xs text-gray-500 mr-2">(<?php echo htmlspecialchars($admin['username']); ?>)</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-right">
                        <?php echo htmlspecialchars($admin['email']); ?>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <span class="bg-gray-100 text-gray-600 py-1 px-3 rounded-full text-xs">
                            <?php echo htmlspecialchars($admin['role'] ?? '-'); ?>
                        </span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <?php if ($admin['status'] === 'active'): ?>
                            <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">فعال</span>
                        <?php else: ?>
                            <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">غیرفعال</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <?php if ($admin['is_super_admin']): ?>
                            <span class="text-purple-600 font-bold text-xs">مدیر کل</span>
                        <?php else: ?>
                            <span class="text-gray-500 text-xs">محدود</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="/admin/admins/edit/<?php echo $admin['id']; ?>" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>

                            <?php if (!$admin['is_super_admin'] && $admin['id'] != $_SESSION['admin_id']): ?>
                                <form action="/admin/admins/delete/<?php echo $admin['id']; ?>" method="POST" onsubmit="return confirm('آیا از حذف این مدیر مطمئن هستید؟');" class="inline">
                                    <?php csrf_field(); ?>
                                    <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
