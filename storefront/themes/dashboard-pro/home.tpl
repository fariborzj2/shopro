<!-- Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Active Orders Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-card hover:shadow-lg transition-all duration-200 border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
        <div class="absolute top-0 left-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-24 h-24 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-primary-50 dark:bg-primary-900/30 rounded-xl text-primary-600 dark:text-primary-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">سفارش‌های فعال</span>
            </div>
            <div>
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white"><?php echo number_format($activeOrdersCount ?? 0); ?></span>
                <span class="text-sm text-gray-500 dark:text-gray-400 mr-2">سفارش</span>
            </div>
            <a href="/dashboard/orders" class="mt-4 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                مشاهده لیست
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
        </div>
    </div>

    <!-- Total Spend Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-card hover:shadow-lg transition-all duration-200 border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
        <div class="absolute top-0 left-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-24 h-24 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-xl text-green-600 dark:text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">مجموع خرید</span>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-gray-900 dark:text-white"><?php echo number_format($totalSpend ?? 0); ?></span>
                <span class="text-xs text-gray-500 dark:text-gray-400 mr-2">تومان</span>
            </div>
             <div class="mt-4 text-sm text-green-600 dark:text-green-400 flex items-center">
                 <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                 پرداخت موفق
             </div>
        </div>
    </div>

    <!-- Tickets Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-card hover:shadow-lg transition-all duration-200 border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
        <div class="absolute top-0 left-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-24 h-24 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-xl text-purple-600 dark:text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">پیام‌های پشتیبانی</span>
            </div>
            <div>
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white"><?php echo number_format($unreadMessages ?? 0); ?></span>
                <span class="text-sm text-gray-500 dark:text-gray-400 mr-2">پیام جدید</span>
            </div>
            <a href="/dashboard/messages" class="mt-4 text-sm font-medium text-purple-600 dark:text-purple-400 hover:text-purple-700 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                ارسال تیکت جدید
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
        </div>
    </div>
</div>

<!-- Recent Orders Section -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white">سفارش‌های اخیر</h2>
        <a href="/dashboard/orders" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">مشاهده همه</a>
    </div>

    <!-- Desktop Table (md+) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-4 text-right">کد سفارش</th>
                    <th scope="col" class="px-6 py-4 text-right">تاریخ</th>
                    <th scope="col" class="px-6 py-4 text-right">مبلغ (تومان)</th>
                    <th scope="col" class="px-6 py-4 text-center">وضعیت پرداخت</th>
                    <th scope="col" class="px-6 py-4 text-center">وضعیت سفارش</th>
                    <th scope="col" class="px-6 py-4 text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(!empty($recentOrders)): ?>
                    <?php foreach($recentOrders as $order): ?>
                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white text-right">
                            #<?php echo $order['order_code']; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php echo \jdate('d F Y', strtotime($order['order_time'])); ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-right">
                            <?php echo number_format($order['amount']); ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php
                                $statusClass = match($order['payment_status']) {
                                    'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                    default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                };
                                $statusLabel = \translate_payment_status_fa($order['payment_status']);
                            ?>
                            <span class="<?php echo $statusClass; ?> text-xs font-medium px-2.5 py-0.5 rounded-full border border-current opacity-90">
                                <?php echo $statusLabel; ?>
                            </span>
                        </td>
                         <td class="px-6 py-4 text-center">
                            <?php
                                $orderStatusClass = match($order['order_status']) {
                                    'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                    'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    default => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                };
                                $orderStatusLabel = \translate_order_status_fa($order['order_status']);
                            ?>
                            <span class="<?php echo $orderStatusClass; ?> text-xs font-medium px-2.5 py-0.5 rounded-full border border-current opacity-90">
                                <?php echo $orderStatusLabel; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="/dashboard/orders/<?php echo $order['id']; ?>" class="font-medium text-primary-600 dark:text-primary-400 hover:underline">جزئیات</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p>هنوز سفارشی ثبت نکرده‌اید.</p>
                                <a href="/" class="mt-4 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm transition-colors">شروع خرید</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards (md:hidden) -->
    <div class="grid grid-cols-1 gap-4 md:hidden p-4">
        <?php if(!empty($recentOrders)): ?>
            <?php foreach($recentOrders as $order): ?>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 dark:text-gray-400">کد سفارش</span>
                            <span class="font-bold text-gray-900 dark:text-white">#<?php echo $order['order_code']; ?></span>
                        </div>
                        <span class="text-xs text-gray-400"><?php echo \jdate('d F Y', strtotime($order['order_time'])); ?></span>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-bold text-gray-800 dark:text-white"><?php echo number_format($order['amount']); ?> <span class="text-xs font-normal text-gray-500">تومان</span></span>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php
                            $statusClass = match($order['payment_status']) {
                                'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            };
                            $statusLabel = \translate_payment_status_fa($order['payment_status']);

                            $orderStatusClass = match($order['order_status']) {
                                'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                default => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                            };
                            $orderStatusLabel = \translate_order_status_fa($order['order_status']);
                        ?>
                        <span class="<?php echo $statusClass; ?> text-xs px-2 py-1 rounded-lg">
                            <?php echo $statusLabel; ?>
                        </span>
                        <span class="<?php echo $orderStatusClass; ?> text-xs px-2 py-1 rounded-lg">
                            <?php echo $orderStatusLabel; ?>
                        </span>
                    </div>

                    <a href="/dashboard/orders/<?php echo $order['id']; ?>" class="block w-full text-center py-2 border border-primary-200 dark:border-primary-800 text-primary-600 dark:text-primary-400 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors text-sm font-medium">
                        مشاهده جزئیات
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <p>سفارشی یافت نشد.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
