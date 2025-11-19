<?php include 'header.tpl'; ?>

<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <?php if ($transaction->status === 'successful'): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-green-700">پرداخت شما با موفقیت انجام شد.</p>
                </div>
            </div>
        </div>
    <?php elseif ($transaction->status === 'failed'): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-red-700">پرداخت ناموفق بود. <?= htmlspecialchars($transaction->gateway_response) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $payment_details = null;
    if (!empty($order->payment_gateway_response)) {
        $payment_details = json_decode($order->payment_gateway_response);
    }
    ?>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                جزئیات سفارش #<?= htmlspecialchars($order->order_code) ?>
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">وضعیت پرداخت</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= translate_status_fa($transaction->status) ?></dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">کد رهگیری داخلی</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($transaction->track_id) ?></dd>
                </div>

                <?php if ($payment_details && isset($payment_details->refNumber)): ?>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">شماره پیگیری درگاه</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($payment_details->refNumber) ?></dd>
                </div>
                <?php endif; ?>

                <?php if ($payment_details && isset($payment_details->cardNumber)): ?>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">شماره کارت</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($payment_details->cardNumber) ?></dd>
                </div>
                <?php endif; ?>

                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">مبلغ</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($order->amount) ?> تومان</dd>
                </div>

                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">تاریخ ثبت سفارش</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= \jdate('Y/m/d H:i', strtotime($order->order_time)) ?></dd>
                </div>

                <?php if ($payment_details && isset($payment_details->paidAt)): ?>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">تاریخ پرداخت</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= \jdate('Y/m/d H:i', strtotime($payment_details->paidAt)) ?></dd>
                </div>
                <?php endif; ?>

                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">نام محصول</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($order->product_name) ?></dd>
                </div>

                <?php
                if (!empty($order->custom_fields_data)) {
                    // Decode as an array of objects
                    $custom_fields = json_decode($order->custom_fields_data);
                    if (is_array($custom_fields) && !empty($custom_fields)) {
                ?>
                    <div class="bg-white px-4 py-5 sm:px-6 border-t border-gray-200">
                         <dt class="text-sm font-medium text-gray-500">اطلاعات تکمیلی</dt>
                    </div>

                <?php
                        foreach ($custom_fields as $index => $field_data) {
                            $bg_class = ($index % 2 == 0) ? 'bg-white' : 'bg-gray-50'; // Swapped for better alternating
                ?>
                            <div class="<?= $bg_class ?> px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500"><?= htmlspecialchars($field_data->label ?? $field_data->name) ?></dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($field_data->value) ?></dd>
                            </div>
                <?php
                        }
                    }
                }
                ?>

            </dl>
        </div>
    </div>
    <div class="mt-6">
        <a href="/dashboard/orders" class="text-indigo-600 hover:text-indigo-900">بازگشت به لیست سفارشات</a>
    </div>
</div>

<?php include 'footer.tpl'; ?>
