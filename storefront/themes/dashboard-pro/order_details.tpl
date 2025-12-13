<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Order Summary -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Timeline -->
        <div class="card p-6">
            <h3 class="font-bold text-gray-800 mb-6">وضعیت سفارش</h3>
            <div class="relative">
                <div class="absolute top-0 bottom-0 right-4 w-0.5 bg-gray-200"></div>
                <ul class="space-y-8 relative pr-10">
                    <!-- Step 1: Created -->
                    <li class="relative">
                        <span class="absolute -right-12 top-1 w-4 h-4 rounded-full bg-blue-500 border-2 border-white ring-2 ring-blue-100"></span>
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800">ثبت سفارش</span>
                            <span class="text-xs text-gray-500 mt-1"><?php echo \jdate('j F Y - H:i', strtotime($order['order_time'])); ?></span>
                            <p class="text-sm text-gray-600 mt-2">سفارش با موفقیت ثبت شد و در انتظار پرداخت است.</p>
                        </div>
                    </li>

                    <!-- Step 2: Payment -->
                    <?php if($order['payment_status'] == 'paid'): ?>
                    <li class="relative">
                        <span class="absolute -right-12 top-1 w-4 h-4 rounded-full bg-green-500 border-2 border-white ring-2 ring-green-100"></span>
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800">پرداخت موفق</span>
                            <span class="text-xs text-gray-500 mt-1">تراکنش تایید شد</span>
                        </div>
                    </li>
                    <?php elseif($order['payment_status'] == 'failed'): ?>
                    <li class="relative">
                        <span class="absolute -right-12 top-1 w-4 h-4 rounded-full bg-red-500 border-2 border-white ring-2 ring-red-100"></span>
                        <div class="flex flex-col">
                            <span class="font-bold text-red-600">پرداخت ناموفق</span>
                            <p class="text-sm text-red-500 mt-1">عملیات پرداخت با خطا مواجه شد.</p>
                        </div>
                    </li>
                    <?php endif; ?>

                    <!-- Step 3: Status -->
                    <?php if($order['order_status'] == 'completed'): ?>
                    <li class="relative">
                        <span class="absolute -right-12 top-1 w-4 h-4 rounded-full bg-green-500 border-2 border-white ring-2 ring-green-100"></span>
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800">تکمیل شده</span>
                            <p class="text-sm text-gray-600 mt-1">سفارش شما تکمیل و تحویل داده شد.</p>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Items -->
        <div class="card overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">اقلام سفارش</h3>
            </div>
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3">محصول</th>
                        <th class="px-6 py-3">تعداد</th>
                        <th class="px-6 py-3">قیمت واحد</th>
                        <th class="px-6 py-3">مجموع</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <!-- Placeholder Image if product image not available -->
                                <div class="w-12 h-12 rounded bg-gray-100 flex-shrink-0"></div>
                                <span class="font-medium text-gray-800"><?php echo $order['product_name'] ?? 'محصول'; ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600"><?php echo $order['quantity']; ?></td>
                        <td class="px-6 py-4 text-gray-600"><?php echo number_format($order['amount'] / max(1, $order['quantity'])); ?></td>
                        <td class="px-6 py-4 font-bold text-gray-800"><?php echo number_format($order['amount']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Custom Fields Data -->
        <?php if(!empty($order['custom_fields_data'])): ?>
        <div class="card p-6">
            <h3 class="font-bold text-gray-800 mb-4">اطلاعات تکمیلی</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php
                    $customData = json_decode($order['custom_fields_data'], true);
                    if ($customData) {
                        foreach($customData as $field) {
                            echo '<div class="flex flex-col">';
                            echo '<span class="text-sm text-gray-500 mb-1">' . htmlspecialchars($field['label']) . '</span>';
                            echo '<span class="text-gray-800">' . htmlspecialchars(is_array($field['value']) ? implode(', ', $field['value']) : $field['value']) . '</span>';
                            echo '</div>';
                        }
                    }
                ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <div class="card p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">خلاصه صورتحساب</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>مبلغ کل</span>
                    <span><?php echo number_format($order['amount']); ?> تومان</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>تخفیف</span>
                    <span class="text-red-500">0 تومان</span>
                </div>
                <div class="border-t border-gray-100 pt-3 flex justify-between font-bold text-lg text-gray-900">
                    <span>قابل پرداخت</span>
                    <span><?php echo number_format($order['amount']); ?> تومان</span>
                </div>
            </div>
        </div>

        <?php if($transaction): ?>
        <div class="card p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">اطلاعات پرداخت</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>درگاه</span>
                    <span><?php echo $transaction['payment_gateway']; ?></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>شماره پیگیری</span>
                    <span class="font-mono"><?php echo $transaction['track_id'] ?? '-'; ?></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>تاریخ</span>
                    <span><?php echo \jdate('Y/m/d H:i', strtotime($transaction['created_at'])); ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <a href="/dashboard/orders" class="block w-full py-3 text-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors">بازگشت به لیست</a>
    </div>

</div>
