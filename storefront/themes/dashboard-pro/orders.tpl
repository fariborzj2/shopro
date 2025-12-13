<div class="card overflow-hidden">
    <!-- Filter Bar -->
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="relative max-w-xs w-full">
            <input type="text" placeholder="جستجو در سفارش‌ها..." class="w-full pl-4 pr-10 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex items-center gap-2">
            <select class="form-select text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option>همه وضعیت‌ها</option>
                <option>تکمیل شده</option>
                <option>در انتظار</option>
                <option>لغو شده</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-right">
            <thead class="bg-white text-gray-500 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 font-medium">کد سفارش</th>
                    <th class="px-6 py-4 font-medium">محصول</th>
                    <th class="px-6 py-4 font-medium">تاریخ ثبت</th>
                    <th class="px-6 py-4 font-medium">مبلغ کل</th>
                    <th class="px-6 py-4 font-medium">وضعیت پرداخت</th>
                    <th class="px-6 py-4 font-medium">وضعیت سفارش</th>
                    <th class="px-6 py-4 font-medium">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                <?php if(!empty($orders)): ?>
                    <?php foreach($orders as $order): ?>
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 font-mono text-gray-600">#<?php echo $order['order_code']; ?></td>
                        <td class="px-6 py-4 text-gray-800 font-medium max-w-xs truncate" title="<?php echo $order['product_name'] ?? '-'; ?>">
                            <?php echo $order['product_name'] ?? 'محصول حذف شده'; ?>
                        </td>
                        <td class="px-6 py-4 text-gray-600"><?php echo \jdate('Y/m/d H:i', strtotime($order['order_time'])); ?></td>
                        <td class="px-6 py-4 font-bold text-gray-800"><?php echo number_format($order['amount']); ?> <span class="text-xs font-normal text-gray-500">تومان</span></td>
                        <td class="px-6 py-4">
                             <?php
                                $payColor = ($order['payment_status'] == 'paid') ? 'green' : (($order['payment_status'] == 'failed') ? 'red' : 'yellow');
                                $payText = ($order['payment_status'] == 'paid') ? 'پرداخت شده' : (($order['payment_status'] == 'failed') ? 'ناموفق' : 'پرداخت نشده');
                            ?>
                            <span class="inline-flex items-center gap-1.5 text-<?php echo $payColor; ?>-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-<?php echo $payColor; ?>-500"></span>
                                <?php echo $payText; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                             <?php
                                $statusColor = 'gray';
                                $statusText = 'نامشخص';
                                switch($order['order_status']) {
                                    case 'pending': $statusColor = 'yellow'; $statusText = 'در حال بررسی'; break;
                                    case 'completed': $statusColor = 'green'; $statusText = 'تکمیل شده'; break;
                                    case 'cancelled': $statusColor = 'red'; $statusText = 'لغو شده'; break;
                                    case 'phishing': $statusColor = 'red'; $statusText = 'مشکوک'; break;
                                }
                            ?>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?php echo $statusColor; ?>-100 text-<?php echo $statusColor; ?>-800">
                                <?php echo $statusText; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="/dashboard/orders/<?php echo $order['id']; ?>" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors">
                                جزئیات
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-lg font-medium">هنوز سفارشی ثبت نکرده‌اید</p>
                                <a href="/" class="mt-4 text-blue-500 hover:underline">بازگشت به فروشگاه</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination (Simple) -->
    <?php if(isset($totalPages) && $totalPages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-center gap-2">
        <!-- Add pagination logic here if needed -->
    </div>
    <?php endif; ?>
</div>
