<div class="max-w-5xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <a href="<?= url('orders') ?>" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <?php partial('icon', ['name' => 'orders', 'class' => 'w-5 h-5']); ?>
                </a>
                <span class="text-gray-300 dark:text-gray-600">/</span>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">جزئیات سفارش <span class="font-mono text-primary-600 dark:text-primary-400">#<?= htmlspecialchars($order->order_code) ?></span></h1>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ثبت شده در تاریخ <?= jdate('l j F Y ساعت H:i', strtotime($order->order_time)) ?></p>
        </div>

        <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-lg text-sm font-medium border <?= $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800' : 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800' ?>">
                <?= translate_payment_status_fa($order->payment_status) ?>
            </span>
            <span class="px-3 py-1 rounded-lg text-sm font-medium border <?= $order->order_status === 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800' : 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600' ?>">
                <?= translate_order_status_fa($order->order_status) ?>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Order Items -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Order Items Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        اقلام سفارش
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Single Item (Since current schema seems to link 1 product per order directly, or simplistic view) -->
                    <!-- If there were multiple items, this would be a loop. Based on 'product_name' in $order, it seems 1-to-1 or flattened. -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-lg"><?= htmlspecialchars($order->product_name) ?></h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">تعداد: <?= $order->quantity ?? 1 ?> عدد</p>
                            </div>
                        </div>
                        <div class="text-left pl-2">
                            <p class="text-lg font-bold text-gray-900 dark:text-white"><?= number_format($order->amount) ?> <span class="text-sm font-normal text-gray-500">تومان</span></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <span class="font-bold text-gray-700 dark:text-gray-300">مجمع کل پرداختی</span>
                    <span class="text-xl font-bold text-primary-600 dark:text-primary-400"><?= number_format($order->amount) ?> <span class="text-sm text-gray-500">تومان</span></span>
                </div>
            </div>

            <!-- Custom Fields Card -->
            <?php if (!empty($order->custom_fields_data)):
                $custom_fields = json_decode($order->custom_fields_data);
                if (is_array($custom_fields) && !empty($custom_fields)):
            ?>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
                        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            اطلاعات تکمیلی سفارش
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($custom_fields as $field): ?>
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1"><?= htmlspecialchars($field->label ?? $field->name) ?></span>
                                <span class="block font-medium text-gray-900 dark:text-gray-100"><?= htmlspecialchars($field->value) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; endif; ?>

        </div>

        <!-- Right Column: Status & User -->
        <div class="space-y-6">

            <!-- Status Update Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
                    <h3 class="font-bold text-gray-800 dark:text-white">مدیریت وضعیت</h3>
                </div>
                <div class="p-6">
                    <form action="<?= url('orders/update_status/' . $order->id) ?>" method="POST" class="space-y-5">
                        <?php csrf_field(); ?>

                        <div>
                            <label for="order_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">وضعیت سفارش</label>
                            <select name="order_status" id="order_status" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                <?php
                                $order_statuses = [
                                    'pending' => 'درحال بررسی',
                                    'completed' => 'تکمیل شده',
                                    'cancelled' => 'لغو شده',
                                    'phishing' => 'فیشینگ',
                                ];
                                foreach ($order_statuses as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $order->order_status === $key ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">وضعیت پرداخت</label>
                            <select name="payment_status" id="payment_status" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                <?php
                                $payment_statuses = [
                                    'unpaid' => 'عدم پرداخت',
                                    'paid' => 'پرداخت موفق',
                                    'failed' => 'پرداخت ناموفق',
                                ];
                                foreach ($payment_statuses as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $order->payment_status === $key ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            بروزرسانی وضعیت
                        </button>
                    </form>
                </div>
            </div>

            <!-- User Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                 <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
                    <h3 class="font-bold text-gray-800 dark:text-white">اطلاعات مشتری</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                         <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                            <?= mb_substr($order->user_name ?? 'M', 0, 1) ?>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($order->user_name ?? 'مهمان') ?></h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">مشتری</p>
                        </div>
                    </div>

                    <div class="space-y-3 mt-4">
                        <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="font-mono"><?= htmlspecialchars($order->user_mobile) ?></span>
                        </div>
                        <!-- Placeholder for Email if available -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
