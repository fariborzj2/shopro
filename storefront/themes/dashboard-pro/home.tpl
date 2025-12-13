<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Stat Card: Last Order -->
    <div class="card p-6 border-r-4 border-blue-500">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500">آخرین سفارش</h3>
            <div class="p-2 bg-blue-50 rounded-lg text-blue-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>
        <?php if ($lastOrder): ?>
            <div class="flex flex-col">
                <span class="text-2xl font-bold text-gray-800"><?php echo number_format($lastOrder['amount']); ?> <span class="text-sm font-normal text-gray-500">تومان</span></span>
                <span class="text-xs text-gray-500 mt-1"><?php echo \jdate('j F Y', strtotime($lastOrder['order_time'])); ?></span>
                <a href="/dashboard/orders/<?php echo $lastOrder['id']; ?>" class="text-xs text-blue-500 mt-2 hover:underline">مشاهده جزئیات &larr;</a>
            </div>
        <?php else: ?>
            <span class="text-gray-400 text-sm">بدون سفارش</span>
        <?php endif; ?>
    </div>

    <!-- Stat Card: Unread Messages -->
    <div class="card p-6 border-r-4 border-indigo-500">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500">پیام‌های جدید</h3>
            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
        </div>
        <div class="flex flex-col">
            <span class="text-2xl font-bold text-gray-800"><?php echo $unreadMessages; ?></span>
            <span class="text-xs text-gray-500 mt-1">پیام خوانده نشده</span>
            <a href="/dashboard/messages" class="text-xs text-indigo-500 mt-2 hover:underline">مشاهده صندوق پیام &larr;</a>
        </div>
    </div>

    <!-- Stat Card: Active Orders -->
    <div class="card p-6 border-r-4 border-yellow-500">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500">سفارش‌های جاری</h3>
            <div class="p-2 bg-yellow-50 rounded-lg text-yellow-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
        </div>
        <div class="flex flex-col">
            <span class="text-2xl font-bold text-gray-800"><?php echo $activeOrdersCount; ?></span>
            <span class="text-xs text-gray-500 mt-1">در حال پردازش</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Orders Table -->
    <div class="lg:col-span-2 card">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">سفارش‌های اخیر</h3>
            <a href="/dashboard/orders" class="text-sm text-blue-500 hover:text-blue-600">مشاهده همه</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">کد سفارش</th>
                        <th class="px-6 py-3 font-medium">تاریخ</th>
                        <th class="px-6 py-3 font-medium">مبلغ</th>
                        <th class="px-6 py-3 font-medium">وضعیت</th>
                        <th class="px-6 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(!empty($recentOrders)): ?>
                        <?php foreach($recentOrders as $order): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-gray-600">#<?php echo $order['order_code']; ?></td>
                            <td class="px-6 py-4 text-gray-600"><?php echo \jdate('Y/m/d', strtotime($order['order_time'])); ?></td>
                            <td class="px-6 py-4 font-bold text-gray-800"><?php echo number_format($order['amount']); ?></td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusColor = 'gray';
                                    $statusText = 'نامشخص';
                                    switch($order['order_status']) {
                                        case 'pending': $statusColor = 'yellow'; $statusText = 'در انتظار'; break;
                                        case 'completed': $statusColor = 'green'; $statusText = 'تکمیل شده'; break;
                                        case 'cancelled': $statusColor = 'red'; $statusText = 'لغو شده'; break;
                                    }
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?php echo $statusColor; ?>-100 text-<?php echo $statusColor; ?>-800">
                                    <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="/dashboard/orders/<?php echo $order['id']; ?>" class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">سفارشی یافت نشد.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Login Activity -->
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">آخرین ورودها</h3>
        </div>
        <div class="p-6">
            <ul class="space-y-4">
                <?php if(!empty($recentLogs)): ?>
                    <?php foreach($recentLogs as $log): ?>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 p-1.5 bg-gray-100 rounded text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800" dir="ltr"><?php echo $log['ip_address']; ?></p>
                            <p class="text-xs text-gray-500 mt-0.5"><?php echo \jdate('j F Y - H:i', strtotime($log['login_time'])); ?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="text-sm text-gray-500">اطلاعاتی ثبت نشده است.</li>
                <?php endif; ?>
            </ul>
            <a href="/dashboard/logs" class="block mt-6 text-center text-sm text-blue-500 hover:underline">مشاهده تاریخچه کامل</a>
        </div>
    </div>
</div>
