<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">مدیریت سفارشات</h1>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    کد سفارش
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    کاربر
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    مبلغ کل
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    زمان ثبت
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    وضعیت پرداخت
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    وضعیت سفارش
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-mono"><?= htmlspecialchars($order['order_code']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($order['user_name'] ?? 'مهمان') ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= number_format($order['amount']) ?> تومان</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= \jdate('Y/m/d H:i', strtotime($order['order_time'])) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <?php
                            $p_class = $order['payment_status'] === 'paid' ? 'bg-green-100 text-green-800' : ($order['payment_status'] === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                        ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $p_class ?>">
                            <?= translate_payment_status_fa($order['payment_status']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <?php
                            $o_class = 'bg-gray-100 text-gray-800';
                            switch ($order['order_status']) {
                                case 'completed': $o_class = 'bg-green-100 text-green-800'; break;
                                case 'pending': $o_class = 'bg-blue-100 text-blue-800'; break;
                                case 'cancelled': $o_class = 'bg-red-100 text-red-800'; break;
                                case 'phishing': $o_class = 'bg-red-100 text-red-800'; break;
                            }
                        ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $o_class ?>">
                            <?= translate_order_status_fa($order['order_status']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a href="/admin/orders/show/<?= $order['id'] ?>" class="text-indigo-600 hover:text-indigo-900">مشاهده</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="p-4">
    <?php partial('pagination', ['paginator' => $paginator]); ?>
</div>