<?php include __DIR__ . '/../header.tpl'; ?>

<main class="flex-grow bg-gray-50 dark:bg-gray-900 py-16 transition-colors duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-8">تاریخچه سفارشات</h1>

        <div class="space-y-6">
            <?php foreach ($orders as $order): ?>
                <?php
                    // Determine status style
                    $borderClass = 'border-l-4 border-l-yellow-500'; // Default pending
                    $statusBadgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';

                    if ($order->payment_status == 'paid') {
                        $borderClass = 'border-l-4 border-l-green-500';
                        $statusBadgeClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                    } elseif ($order->payment_status == 'failed' || $order->payment_status == 'phishing') {
                        $borderClass = 'border-l-4 border-l-red-500';
                        $statusBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                    }

                    $orderStatusBadge = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                    if ($order->order_status == 'completed') $orderStatusBadge = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                    if ($order->order_status == 'cancelled' || $order->order_status == 'phishing') $orderStatusBadge = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                ?>

                <a href="/dashboard/orders/<?= $order->id ?>" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-card hover:shadow-lg transition-all duration-300 overflow-hidden <?= $borderClass ?>">
                    <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-primary-600 dark:text-primary-400 mb-2">
                                <?= htmlspecialchars($order->product_name) ?>
                            </h3>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <?= htmlspecialchars(number_format($order->amount)) ?> تومان
                                </span>
                                <span class="hidden md:inline text-gray-300 dark:text-gray-600">•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <?= \jdate('Y/m/d', strtotime($order->order_time)) ?>
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $statusBadgeClass ?>">
                                <?= translate_payment_status_fa($order->payment_status) ?>
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $orderStatusBadge ?>">
                                <?= translate_order_status_fa($order->order_status) ?>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>

            <?php if(empty($orders)): ?>
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-dashed border-gray-200 dark:border-gray-700">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">هنوز سفارشی ثبت نکرده‌اید.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../footer.tpl'; ?>
