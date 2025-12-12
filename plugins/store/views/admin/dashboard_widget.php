<!-- Recent Orders Widget -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex flex-wrap gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-800">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">آخرین سفارشات</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">لیست ۱۰ تراکنش اخیر فروشگاه</p>
        </div>
        <a href="<?php echo url('/orders'); ?>" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium flex items-center gap-1 transition-colors">
            مشاهده همه
            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4 rotate-90']); ?>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/30">
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">شماره سفارش</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">کاربر</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">مبلغ</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت پرداخت</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت سفارش</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                <?php if(empty($recent_orders)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            هنوز سفارشی ثبت نشده است.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recent_orders as $order): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-mono whitespace-nowrap">
                                <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md text-xs text-gray-600 dark:text-gray-300">
                                    #<?= htmlspecialchars($order['id']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold ml-3 shadow-sm">
                                        <?= mb_substr($order['user_name'] ?? 'م', 0, 1) ?>
                                    </div>
                                    <span class="font-medium"><?= htmlspecialchars($order['user_name'] ?? 'مهمان') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-bold whitespace-nowrap">
                                <?= number_format($order['amount']) ?> <span class="text-xs font-normal text-gray-400">تومان</span>
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <?php
                                    $p_status = strtolower($order['payment_status']);
                                    $p_class = 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';

                                    if ($p_status === 'paid') {
                                        $p_class = 'bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800';
                                    }
                                    elseif ($p_status === 'failed') {
                                        $p_class = 'bg-red-50 text-red-700 border border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800';
                                    }
                                    elseif ($p_status === 'unpaid') {
                                        $p_class = 'bg-amber-50 text-amber-700 border border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800';
                                    }
                                ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-mediuml <?= $p_class ?>">
                                    <?= translate_payment_status_fa($order['payment_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <?php
                                    $o_status = strtolower($order['order_status']);
                                    $o_class = 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';

                                    if ($o_status === 'completed') $o_class = 'text-emerald-600 dark:text-emerald-400 font-medium';
                                    elseif ($o_status === 'pending') $o_class = 'text-blue-600 dark:text-blue-400 font-medium';
                                    elseif ($o_status === 'cancelled' || $o_status === 'phishing') $o_class = 'text-red-600 dark:text-red-400';
                                ?>
                                <span class="<?= $o_class ?>">
                                    <?= translate_order_status_fa($order['order_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <a href="<?= url('orders/show/' . $order['id']) ?>" class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 inline-flex">
                                    <?php partial('icon', ['name' => 'eye', 'class' => 'w-5 h-5']); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
