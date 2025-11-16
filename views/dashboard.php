<h1 class="text-3xl font-bold mb-4">داشبورد</h1>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-medium text-gray-500">فروش امروز</h3>
        <p class="mt-2 text-3xl font-bold text-gray-800"><?= $kpis['sales_today'] ?> تومان</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-medium text-gray-500">سفارشات امروز</h3>
        <p class="mt-2 text-3xl font-bold text-gray-800"><?= $kpis['orders_today'] ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-medium text-gray-500">کاربران جدید امروز</h3>
        <p class="mt-2 text-3xl font-bold text-gray-800"><?= $kpis['new_users_today'] ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-medium text-gray-500">سفارشات این ماه</h3>
        <p class="mt-2 text-3xl font-bold text-gray-800"><?= $kpis['orders_this_month'] ?></p>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">روند فروش (۷ روز گذشته)</h3>
        <canvas id="salesChart"></canvas>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">کاربران جدید (این ماه)</h3>
        <canvas id="usersChart"></canvas>
    </div>
</div>

<!-- Recent Orders -->
<div class="mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">آخرین سفارشات</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">کد سفارش</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">کاربر</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">مبلغ</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_orders as $order): ?>
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
                             <?php
                                $status_text = [
                                    'pending' => 'در انتظار پرداخت',
                                    'processing' => 'در حال پردازش',
                                    'shipped' => 'ارسال شده',
                                    'delivered' => 'تحویل شده',
                                    'cancelled' => 'لغو شده',
                                ];
                                echo htmlspecialchars($status_text[$order['status']] ?? 'نامشخص');
                            ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            <a href="/orders/show/<?= $order['id'] ?>" class="text-indigo-600 hover:text-indigo-900">مشاهده</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($salesChartData['labels']) ?>,
            datasets: [{
                label: 'فروش',
                data: <?= json_encode($salesChartData['data']) ?>,
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Users Chart
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($usersChartData['labels']) ?>,
            datasets: [{
                label: 'کاربران جدید',
                data: <?= json_encode($usersChartData['data']) ?>,
                backgroundColor: 'rgba(219, 39, 119, 0.8)',
                borderColor: 'rgba(219, 39, 119, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
