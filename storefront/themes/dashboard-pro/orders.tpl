<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden">
    <!-- Filters / Header -->
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            تاریخچه سفارشات
        </h2>

        <!-- Search/Filter Placeholder -->
        <div class="flex gap-2">
            <div class="relative">
                <input type="text" placeholder="جستجو در سفارشات..." class="w-full sm:w-64 pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-4 text-right">کد سفارش</th>
                    <th scope="col" class="px-6 py-4 text-right">محصولات</th>
                    <th scope="col" class="px-6 py-4 text-right">تاریخ ثبت</th>
                    <th scope="col" class="px-6 py-4 text-right">مبلغ کل</th>
                    <th scope="col" class="px-6 py-4 text-center">وضعیت</th>
                    <th scope="col" class="px-6 py-4 text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(!empty($orders)): ?>
                    <?php foreach($orders as $order): ?>
                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white text-right">
                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs text-gray-600 dark:text-gray-300">#<?php echo $order->order_code; ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="truncate block max-w-xs" title="<?php echo htmlspecialchars($order->product_name); ?>">
                                <?php echo htmlspecialchars($order->product_name); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-xs">
                            <?php echo \jdate('d F Y', strtotime($order->order_time)); ?>
                            <span class="block text-gray-400 text-[10px] mt-0.5"><?php echo \jdate('H:i', strtotime($order->order_time)); ?></span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-right">
                            <?php echo number_format($order->amount); ?> <span class="text-xs font-normal text-gray-500">تومان</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                             <div class="flex flex-col gap-1 items-center">
                                <?php
                                    $payClass = match($order->payment_status) {
                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    };
                                    $orderClass = match($order->order_status) {
                                        'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        default => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                    };
                                ?>
                                <span class="<?php echo $payClass; ?> text-[10px] font-medium px-2 py-0.5 rounded-md w-full max-w-[80px]">
                                    <?php echo \translate_payment_status_fa($order->payment_status); ?>
                                </span>
                                <span class="<?php echo $orderClass; ?> text-[10px] font-medium px-2 py-0.5 rounded-md w-full max-w-[80px]">
                                    <?php echo \translate_order_status_fa($order->order_status); ?>
                                </span>
                             </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="/dashboard/orders/<?php echo $order->id; ?>" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-primary-600 hover:bg-primary-50 dark:text-gray-400 dark:hover:text-primary-400 dark:hover:bg-primary-900/20 transition-all" title="جزئیات سفارش">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">هیچ سفارشی یافت نشد</h3>
                                <p class="mt-1 max-w-sm mx-auto">شما هنوز هیچ سفارشی ثبت نکرده‌اید یا با فیلترهای فعلی همخوانی ندارد.</p>
                                <a href="/" class="mt-6 px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl shadow-lg shadow-primary-500/30 transition-all text-sm font-bold">مشاهده محصولات</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination (Placeholder) -->
    <?php if(isset($totalPages) && $totalPages > 1): ?>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex justify-center">
        <!-- Pagination UI logic here -->
    </div>
    <?php endif; ?>
</div>
