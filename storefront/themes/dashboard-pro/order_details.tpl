<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Order Summary & Progress (Left/Top) -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Order Progress Tracker -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                پیگیری وضعیت سفارش
            </h2>

            <?php
                // Define steps logic based on order status
                $steps = [
                    ['status' => 'pending', 'label' => 'در انتظار پرداخت'],
                    ['status' => 'processing', 'label' => 'در حال پردازش'],
                    ['status' => 'completed', 'label' => 'تکمیل شده'],
                ];

                // Determine current step index
                $currentIndex = 0;
                if ($order['payment_status'] === 'paid') $currentIndex = 1;
                if ($order['order_status'] === 'completed') $currentIndex = 2;
                if ($order['order_status'] === 'cancelled') $currentIndex = -1;
            ?>

            <?php if($currentIndex === -1): ?>
                <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 text-center">
                    این سفارش لغو شده است.
                </div>
            <?php else: ?>
                <div class="relative flex items-center justify-between w-full mt-4">
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 dark:bg-gray-700 -z-0"></div>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-green-500 transition-all duration-500 -z-0" style="width: <?php echo ($currentIndex / (count($steps)-1)) * 100; ?>%"></div>

                    <?php foreach($steps as $index => $step): ?>
                        <?php
                            $isCompleted = $index <= $currentIndex;
                            $isCurrent = $index === $currentIndex;
                        ?>
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 <?php echo $isCompleted ? 'bg-green-500 border-green-500 text-white' : 'bg-white border-gray-300 text-gray-300 dark:bg-gray-800 dark:border-gray-600'; ?> transition-colors duration-300">
                                <?php if($isCompleted): ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <?php else: ?>
                                    <span class="text-xs"><?php echo $index + 1; ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="mt-2 text-xs font-medium <?php echo $isCompleted ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'; ?>">
                                <?php echo $step['label']; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Order Items -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-gray-800 dark:text-white">اقلام سفارش</h3>
            </div>
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-xl flex-shrink-0 overflow-hidden">
                         <!-- Placeholder image if no product image -->
                        <svg class="w-full h-full text-gray-400 p-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-2"><?php echo htmlspecialchars($order['product_name']); ?></h4>

                        <!-- Custom Fields Display -->
                         <?php if (!empty($customFields)): ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 mb-3">
                                <?php foreach ($customFields as $field): ?>
                                    <div class="text-sm">
                                        <span class="text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($field['label']); ?>:</span>
                                        <span class="text-gray-800 dark:text-gray-200 font-medium"><?php echo htmlspecialchars($field['value']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                             <div class="text-sm text-gray-500">تعداد: <span class="text-gray-900 dark:text-white font-bold"><?php echo $order['quantity']; ?></span></div>
                             <div class="text-lg font-bold text-primary-600 dark:text-primary-400"><?php echo number_format($order['amount']); ?> <span class="text-sm text-gray-500">تومان</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Sidebar Info (Right/Bottom) -->
    <div class="space-y-6">

        <!-- Order Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">اطلاعات کلی</h3>
            <ul class="space-y-3 text-sm">
                <li class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">شماره سفارش</span>
                    <span class="font-mono font-medium text-gray-900 dark:text-white"><?php echo $order['order_code']; ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">تاریخ ثبت</span>
                    <span class="font-medium text-gray-900 dark:text-white"><?php echo \jdate('d F Y - H:i', strtotime($order['order_time'])); ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">روش پرداخت</span>
                    <span class="font-medium text-gray-900 dark:text-white">درگاه بانکی</span>
                </li>
            </ul>
        </div>

        <!-- Payment Details -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">جزئیات پرداخت</h3>

            <div class="space-y-3 mb-6">
                <?php if(isset($order['discount_used']) && $order['discount_used'] > 0): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">مبلغ اولیه</span>
                    <span class="text-gray-900 dark:text-white decoration-slice"><?php echo number_format($order['amount'] + $order['discount_used']); ?> تومان</span>
                </div>
                <div class="flex justify-between text-sm text-green-600">
                    <span>تخفیف</span>
                    <span><?php echo number_format($order['discount_used']); ?>- تومان</span>
                </div>
                <?php endif; ?>

                <div class="flex justify-between text-base font-bold pt-2 border-t border-dashed border-gray-200 dark:border-gray-700">
                    <span class="text-gray-800 dark:text-white">مبلغ پرداختی</span>
                    <span class="text-primary-600 dark:text-primary-400"><?php echo number_format($order['amount']); ?> تومان</span>
                </div>
            </div>

            <?php if($order['payment_status'] === 'paid'): ?>
                <div class="bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300 rounded-xl p-3 text-center text-sm font-medium">
                    <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    پرداخت موفق
                </div>
            <?php else: ?>
                <button class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-primary-500/30 transition-all flex items-center justify-center gap-2">
                    پرداخت آنلاین
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </button>
            <?php endif; ?>
        </div>

        <!-- Help -->
         <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 border border-blue-100 dark:border-blue-800">
            <h4 class="font-bold text-blue-800 dark:text-blue-300 mb-2">نیاز به راهنمایی دارید؟</h4>
            <p class="text-xs text-blue-600 dark:text-blue-400 mb-4 leading-relaxed">
                اگر مشکلی در سفارش خود مشاهده می‌کنید، می‌توانید با پشتیبانی تماس بگیرید.
            </p>
            <a href="/dashboard/messages" class="text-sm font-medium text-blue-700 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-200 flex items-center gap-1">
                ارسال تیکت پشتیبانی
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
         </div>

    </div>
</div>
