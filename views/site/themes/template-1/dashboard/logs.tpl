<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50">
        <h3 class="font-bold text-gray-800">تاریخچه ورود به حساب</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-right">
            <thead class="bg-white text-gray-500 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 font-medium">تاریخ و زمان</th>
                    <th class="px-6 py-4 font-medium">آدرس IP</th>
                    <th class="px-6 py-4 font-medium">دستگاه / مرورگر</th>
                    <th class="px-6 py-4 font-medium">وضعیت</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                <?php if(!empty($logs)): ?>
                    <?php foreach($logs as $log): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-600"><?php echo \jdate('Y/m/d H:i:s', strtotime($log['login_time'])); ?></td>
                        <td class="px-6 py-4 font-mono text-gray-600" dir="ltr"><?php echo $log['ip_address']; ?></td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="<?php echo htmlspecialchars($log['user_agent']); ?>">
                            <?php echo htmlspecialchars($log['user_agent']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">موفق</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">سابقه‌ای یافت نشد.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
