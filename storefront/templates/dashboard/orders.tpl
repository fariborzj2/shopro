<?php include 'header.tpl'; ?>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">تاریخچه سفارشات</h1>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <ul class="divide-y divide-gray-200">
            <?php foreach ($orders as $order): ?>
                <li>
                    <a href="/dashboard/orders/<?= $order->id ?>" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    <?= htmlspecialchars($order->product_name) ?>
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php
                                            switch ($order->status) {
                                                case 'paid':
                                                case 'completed':
                                                case 'delivered':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'cancelled':
                                                case 'failed':
                                                case 'phishing':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                default:
                                                    echo 'bg-yellow-100 text-yellow-800';
                                            }
                                        ?>">
                                        <?= translate_status_fa($order->status) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        مبلغ: <?= htmlspecialchars(number_format($order->amount)) ?> تومان
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <p>
                                        تاریخ: <?= \jdate('Y/m/d', strtotime($order->order_time)) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php include 'footer.tpl'; ?>
