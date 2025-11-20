<?php partial('header', ['title' => $title]); ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6"><?= htmlspecialchars($title) ?></h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-8">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">کد سفارش</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($order->order_code) ?></dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">نام کاربر</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($order->user_name) ?></dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">موبایل کاربر</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($order->user_mobile) ?></dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">نام محصول</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($order->product_name) ?></dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">مبلغ</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= number_format($order->amount) ?> تومان</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">وضعیت پرداخت</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                            <?= translate_payment_status_fa($order->payment_status) ?>
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">وضعیت سفارش</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                         <?php
                            $o_class = 'bg-gray-100 text-gray-800';
                            switch ($order->order_status) {
                                case 'completed': $o_class = 'bg-green-100 text-green-800'; break;
                                case 'pending': $o_class = 'bg-blue-100 text-blue-800'; break;
                                case 'cancelled': $o_class = 'bg-red-100 text-red-800'; break;
                                case 'phishing': $o_class = 'bg-red-100 text-red-800'; break;
                            }
                        ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $o_class ?>">
                            <?= translate_order_status_fa($order->order_status) ?>
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">تاریخ سفارش</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= jdate('Y/m/d H:i', strtotime($order->order_time)) ?></dd>
                </div>
            </dl>

            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">تغییر وضعیت سفارش</h3>
                <form action="<?= url('orders/update_status/' . $order->id) ?>" method="POST" class="flex flex-wrap items-center gap-4">
                    <?php csrf_field(); ?>

                    <div class="w-64">
                        <label for="order_status" class="block text-sm font-medium text-gray-700 mb-1">وضعیت سفارش</label>
                        <select name="order_status" id="order_status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
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

                    <div class="w-64">
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">وضعیت پرداخت</label>
                        <select name="payment_status" id="payment_status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
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

                    <div class="w-full sm:w-auto mt-6">
                         <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            ذخیره تغییرات
                        </button>
                    </div>
                </form>
            </div>

            <?php
            if (!empty($order->custom_fields_data)) {
                $custom_fields = json_decode($order->custom_fields_data);
                if (is_array($custom_fields) && !empty($custom_fields)) {
            ?>
                <div class="mt-6 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">اطلاعات تکمیلی</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <?php foreach ($custom_fields as $field): ?>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500"><?= htmlspecialchars($field->label ?? $field->name) ?></dt>
                                <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($field->value) ?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            <?php
                }
            }
            ?>
        </div>
    </div>

    <div class="mt-8">
        <a href="<?php echo url('/orders') ?>" class="text-indigo-600 hover:text-indigo-800">بازگشت به لیست سفارشات</a>
    </div>
</div>

<?php partial('footer'); ?>
