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
                    وضعیت
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
                        <p class="text-gray-900 whitespace-no-wrap"><?= date('Y-m-d H:i', strtotime($order['order_time'])) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
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
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?= explode(' ', $class)[0] ?>">
                            <span aria-hidden class="absolute inset-0 opacity-50 rounded-full <?= explode(' ', $class)[1] ?>"></span>
                            <span class="relative"><?= htmlspecialchars($text) ?></span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a href="/orders/show/<?= $order['id'] ?>" class="text-indigo-600 hover:text-indigo-900">مشاهده</a>
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
