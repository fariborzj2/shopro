<?php include __DIR__ . '/../header.tpl'; ?>

<main class="flex-grow bg-gray-50 dark:bg-gray-900 py-16 transition-colors duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">

        <?php if ($transaction->status === 'successful'): ?>
            <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-bold">پرداخت شما با موفقیت انجام شد</span>
            </div>
        <?php elseif ($transaction->status === 'failed'): ?>
            <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-bold">پرداخت ناموفق بود</span>
            </div>
        <?php endif; ?>

        <?php
        $payment_details = null;
        if (!empty($order->payment_gateway_response)) {
            $payment_details = json_decode($order->payment_gateway_response);
        }
        ?>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
                <h1 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    جزئیات سفارش #<?= htmlspecialchars($order->order_code) ?>
                </h1>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                <!-- Payment Status -->
                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">وضعیت پرداخت</dt>
                    <dd class="sm:col-span-2 text-base font-bold text-gray-900 dark:text-white flex items-center">
                        <?php
                        $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                        if ($transaction->status === 'successful') $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                        elseif ($transaction->status === 'failed') $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                        ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs <?= $statusClass ?>">
                            <?= translate_status_fa($transaction->status) ?>
                        </span>
                    </dd>
                </div>

                <!-- Track ID -->
                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">کد رهگیری داخلی</dt>
                    <dd class="sm:col-span-2 text-base font-semibold text-gray-900 dark:text-white font-mono dir-ltr text-right sm:text-left">
                        <?= htmlspecialchars($transaction->track_id) ?>
                    </dd>
                </div>

                <!-- Gateway Ref Number -->
                <?php if ($payment_details && isset($payment_details->refNumber)): ?>
                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">شماره پیگیری درگاه</dt>
                    <dd class="sm:col-span-2 text-base font-semibold text-gray-900 dark:text-white font-mono dir-ltr text-right sm:text-left">
                        <?= htmlspecialchars($payment_details->refNumber) ?>
                    </dd>
                </div>
                <?php endif; ?>

                <!-- Card Number -->
                <?php if ($payment_details && isset($payment_details->cardNumber)): ?>
                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">شماره کارت</dt>
                    <dd class="sm:col-span-2 text-base font-semibold text-gray-900 dark:text-white font-mono dir-ltr text-right sm:text-left">
                        <?= htmlspecialchars($payment_details->cardNumber) ?>
                    </dd>
                </div>
                <?php endif; ?>

                <!-- Amount -->
                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">مبلغ</dt>
                    <dd class="sm:col-span-2 text-lg font-bold text-green-600 dark:text-green-400">
                        <?= htmlspecialchars(number_format($order->amount)) ?> <span class="text-sm font-normal text-gray-500 dark:text-gray-400">تومان</span>
                    </dd>
                </div>

                <!-- Date -->
                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">تاریخ ثبت سفارش</dt>
                    <dd class="sm:col-span-2 text-base font-semibold text-gray-900 dark:text-white">
                        <?= \jdate('Y/m/d H:i', strtotime($order->order_time)) ?>
                    </dd>
                </div>

                <!-- Product Name -->
                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">نام محصول</dt>
                    <dd class="sm:col-span-2 text-base font-bold text-primary-600 dark:text-primary-400">
                        <?= htmlspecialchars($order->product_name) ?>
                    </dd>
                </div>

                <!-- Custom Fields -->
                <?php
                if (!empty($order->custom_fields_data)) {
                    $custom_fields = json_decode($order->custom_fields_data);
                    if (is_array($custom_fields) && !empty($custom_fields)) {
                ?>
                    <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-800/50">
                         <span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">اطلاعات تکمیلی</span>
                    </div>

                <?php
                        foreach ($custom_fields as $index => $field_data) {
                ?>
                            <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400"><?= htmlspecialchars($field_data->label ?? $field_data->name) ?></dt>
                                <dd class="sm:col-span-2 text-base font-semibold text-gray-900 dark:text-white break-words">
                                    <?= htmlspecialchars($field_data->value) ?>
                                </dd>
                            </div>
                <?php
                        }
                    }
                }
                ?>

            </div>
        </div>

        <div class="mt-8">
            <a href="/dashboard/orders" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-bold transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" transform="scale(-1, 1) translate(-24, 0)" /></svg>
                بازگشت به لیست سفارشات
            </a>
        </div>

    </div>
</main>

<?php include __DIR__ . '/../footer.tpl'; ?>
