<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">مدیریت سفارشات</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">لیست تمامی سفارشات ثبت شده در سیستم</p>
        </div>
        <div class="flex gap-2">
            <!-- Filters could go here -->
        </div>
    </div>

    <!-- Mobile List View -->
    <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-700">
        <?php foreach ($orders as $order): ?>
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="text-xs font-mono text-gray-500 dark:text-gray-400 block">#<?= htmlspecialchars($order['order_code']) ?></span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white mt-1"><?= htmlspecialchars($order['user_name'] ?? 'مهمان') ?></h3>
                    </div>
                    <?php
                        $status_style = match($order['order_status']) {
                            'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                            'pending' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            'cancelled', 'phishing' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'
                        };
                    ?>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $status_style ?>">
                        <?= translate_order_status_fa($order['order_status']) ?>
                    </span>
                </div>

                <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 mb-3">
                    <span><?= \jdate('Y/m/d H:i', strtotime($order['order_time'])) ?></span>
                    <span class="font-bold text-gray-900 dark:text-white text-sm">
                        <?= number_format($order['amount']) ?> <span class="font-normal text-xs text-gray-500">تومان</span>
                    </span>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-gray-100 dark:border-gray-700">
                     <?php
                        $pay_style = match($order['payment_status']) {
                            'paid' => 'text-emerald-600 dark:text-emerald-400',
                            'failed' => 'text-red-600 dark:text-red-400',
                            default => 'text-amber-600 dark:text-amber-400'
                        };
                    ?>
                    <span class="text-xs font-medium <?= $pay_style ?>">
                        <?= translate_payment_status_fa($order['payment_status']) ?>
                    </span>
                    <a href="<?= url('orders/show/' . $order['id']) ?>" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm font-medium flex items-center">
                        جزئیات
                        <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4 mr-1 rotate-90']); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-right">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">کد سفارش</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">کاربر</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">مبلغ کل</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">زمان ثبت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت پرداخت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت سفارش</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <?php foreach ($orders as $order): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">
                            <?= htmlspecialchars($order['order_code']) ?>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            <?= htmlspecialchars($order['user_name'] ?? 'مهمان') ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-bold">
                            <?= number_format($order['amount']) ?> <span class="font-normal text-xs text-gray-500">تومان</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            <?= \jdate('Y/m/d H:i', strtotime($order['order_time'])) ?>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <?php
                                $p_style = match($order['payment_status']) {
                                    'paid' => 'bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800',
                                    'failed' => 'bg-red-50 text-red-700 border border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800',
                                    default => 'bg-amber-50 text-amber-700 border border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800'
                                };
                            ?>
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center gap-1 <?= $p_style ?>">
                                <?= translate_payment_status_fa($order['payment_status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <?php
                                $o_style = match($order['order_status']) {
                                    'completed' => 'text-emerald-600 dark:text-emerald-400 font-medium',
                                    'pending' => 'text-blue-600 dark:text-blue-400 font-medium',
                                    'cancelled', 'phishing' => 'text-red-600 dark:text-red-400',
                                    default => 'text-gray-600 dark:text-gray-400'
                                };
                            ?>
                            <span class="<?= $o_style ?>">
                                <?= translate_order_status_fa($order['order_status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <a href="<?= url('orders/show/' . $order['id']) ?>" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 transition-colors p-2 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/30 inline-flex" title="مشاهده جزئیات">
                                <?php partial('icon', ['name' => 'eye', 'class' => 'w-5 h-5']); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-b-xl">
        <?php partial('pagination', ['paginator' => $paginator]); ?>
    </div>
</div>
