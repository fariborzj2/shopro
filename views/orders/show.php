<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">جزئیات سفارش <span class="font-mono text-2xl text-gray-600"><?= htmlspecialchars($order['order_code']) ?></span></h1>
    <a href="/orders" class="text-blue-500 hover:text-blue-800">&larr; بازگشت به لیست سفارشات</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Order Details -->
    <div class="lg:col-span-2 bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4 border-b pb-2">اطلاعات کلی سفارش</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">کد سفارش</p>
                <p class="font-semibold font-mono"><?= htmlspecialchars($order['order_code']) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">زمان ثبت</p>
                <p class="font-semibold"><?= date('Y-m-d H:i', strtotime($order['order_time'])) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">روش پرداخت</p>
                <p class="font-semibold"><?= htmlspecialchars($order['payment_method'] ?? '—') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">وضعیت فعلی</p>
                 <?php
                    $status_classes = [
                        'pending' => 'text-yellow-900 bg-yellow-200',
                        'processing' => 'text-blue-900 bg-blue-200',
                        'shipped' => 'text-purple-900 bg-purple-200',
                        'delivered' => 'text-green-900 bg-green-200',
                        'cancelled' => 'text-red-900 bg-red-200',
                    ];
                    $status_text = [
                        'pending' => 'در انتظار پرداخت',
                        'processing' => 'در حال پردازش',
                        'shipped' => 'ارسال شده',
                        'delivered' => 'تحویل شده',
                        'cancelled' => 'لغو شده',
                    ];
                    $class = $status_classes[$order['status']] ?? 'text-gray-900 bg-gray-200';
                    $text = $status_text[$order['status']] ?? 'نامشخص';
                ?>
                <span class="font-semibold px-2 py-1 rounded-full text-sm <?= $class ?>"><?= htmlspecialchars($text) ?></span>
            </div>
        </div>

        <h2 class="text-xl font-bold mt-8 mb-4 border-b pb-2">محصولات</h2>
        <table class="w-full">
            <thead>
                <tr class="text-right">
                    <th class="py-2">محصول</th>
                    <th class="py-2">تعداد</th>
                    <th class="py-2">قیمت واحد</th>
                    <th class="py-2">قیمت کل</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2"><?= htmlspecialchars($order['product_name'] ?? '—') ?></td>
                    <td class="py-2"><?= htmlspecialchars($order['quantity']) ?></td>
                    <td class="py-2"><?= number_format($order['amount'] / $order['quantity']) ?> تومان</td>
                    <td class="py-2"><?= number_format($order['amount']) ?> تومان</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6 border-t pt-4 text-right">
            <p><span class="text-gray-600">جمع کل: </span><strong class="text-lg"><?= number_format($order['amount']) ?> تومان</strong></p>
            <p><span class="text-gray-600">تخفیف: </span><strong><?= number_format($order['discount_used']) ?> تومان</strong></p>
            <p class="text-xl"><span class="text-gray-800">مبلغ نهایی: </span><strong class="text-green-600"><?= number_format($order['amount'] - $order['discount_used']) ?> تومان</strong></p>
        </div>
    </div>

    <!-- Sidebar Details -->
    <div class="space-y-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">تغییر وضعیت سفارش</h2>
            <form action="/orders/update_status/<?= $order['id'] ?>" method="POST">
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">وضعیت جدید</label>
                    <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <?php foreach($status_text as $key => $value): ?>
                            <option value="<?= $key ?>" <?= ($order['status'] === $key) ? 'selected' : '' ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    به‌روزرسانی وضعیت
                </button>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">اطلاعات مشتری</h2>
            <div>
                <p class="text-sm text-gray-500">نام</p>
                <p class="font-semibold"><?= htmlspecialchars($order['user_name'] ?? 'مهمان') ?></p>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">موبایل</p>
                <p class="font-semibold"><?= htmlspecialchars($order['user_mobile'] ?? $order['mobile']) ?></p>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">آدرس تحویل</p>
                <p class="font-semibold"><?= nl2br(htmlspecialchars($order['delivery_address'] ?? '—')) ?></p>
            </div>
        </div>
    </div>

    <?php if (!empty($customFieldsData)): ?>
    <div class="mt-8 bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-bold mb-4 border-b pb-2">اطلاعات فیلدهای سفارشی</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <?php foreach ($customFieldsData as $fieldId => $value): ?>
                <?php
                    // Look up the field label from the pre-fetched array
                    $fieldLabel = $allCustomFields[$fieldId]['label_fa'] ?? "فیلد نامشخص (ID: {$fieldId})";
                ?>
                <div>
                    <p class="text-gray-500 font-semibold"><?= htmlspecialchars($fieldLabel) ?>:</p>
                    <p class="text-gray-800 text-lg"><?= htmlspecialchars($value) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
