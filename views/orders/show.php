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
                    <dt class="text-sm font-medium text-gray-500">وضعیت</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $order->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= translate_status_fa($order->status) ?>
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">تاریخ سفارش</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= jdate('Y/m/d H:i', strtotime($order->order_time)) ?></dd>
                </div>
            </dl>

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
        <a href="/admin/orders" class="text-indigo-600 hover:text-indigo-800">بازگشت به لیست سفارشات</a>
    </div>
</div>

<?php partial('footer'); ?>
