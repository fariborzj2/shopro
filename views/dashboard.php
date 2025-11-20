<h1 class="text-3xl font-bold mb-4">داشبورد</h1>

<!-- KPIs -->
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

<!-- New Reports Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500">
        <h3 class="text-sm font-medium text-gray-500">مجموع کل کاربران</h3>
        <p class="mt-2 text-3xl font-bold text-gray-800"><?= number_format($reports['total_users']) ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
        <h3 class="text-sm font-medium text-gray-500">مجموع سفارشات تکمیل‌شده</h3>
        <p class="mt-2 text-3xl font-bold text-gray-800"><?= number_format($reports['total_completed_orders']) ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-red-500">
        <h3 class="text-sm font-medium text-gray-500">سفارشات ناموفق امروز</h3>
        <p class="mt-2 text-3xl font-bold text-gray-800"><?= number_format($reports['failed_orders_today']) ?></p>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
    <!-- Sales Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">روند فروش</h3>
            <div class="flex space-x-2 space-x-reverse">
                <button onclick="updateChart('sales', 'week')" class="px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded-md transition">هفته</button>
                <button onclick="updateChart('sales', 'month')" class="px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded-md transition">ماه</button>
                <button onclick="updateChart('sales', 'year')" class="px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded-md transition">سال</button>
            </div>
        </div>
        <canvas id="salesChart"></canvas>
    </div>

    <!-- Users Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">
                کاربران جدید
                <span class="text-sm font-normal text-gray-500 mr-2">(مجموع ماه جاری: <span id="totalUsersThisMonth"><?= $usersChartData['total_this_month'] ?? 0 ?></span>)</span>
            </h3>
            <div class="flex space-x-2 space-x-reverse">
                <button onclick="updateChart('users', 'week')" class="px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded-md transition">هفته</button>
                <button onclick="updateChart('users', 'month')" class="px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded-md transition">ماه</button>
                <button onclick="updateChart('users', 'year')" class="px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded-md transition">سال</button>
            </div>
        </div>
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
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت پرداخت</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت سفارش</th>
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
                            <a href="<?= url('orders/show/' . $order['id']) ?>" class="text-indigo-600 hover:text-indigo-900">مشاهده</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize Charts
    let salesChart, usersChart;

    function initCharts() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        salesChart = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($salesChartData['labels']) ?>,
                datasets: [
                    {
                        label: 'مبلغ فروش (تومان)',
                        data: <?= json_encode($salesChartData['amounts']) ?>,
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2,
                        yAxisID: 'y',
                        order: 2
                    },
                    {
                        label: 'تعداد فروش',
                        data: <?= json_encode($salesChartData['counts']) ?>,
                        type: 'line',
                        borderColor: 'rgba(236, 72, 153, 1)',
                        backgroundColor: 'rgba(236, 72, 153, 0.2)',
                        borderWidth: 2,
                        pointRadius: 3,
                        yAxisID: 'y1',
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { display: false },
                        title: { display: true, text: 'تومان' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { display: false },
                        title: { display: true, text: 'تعداد' },
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // Users Chart
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        usersChart = new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($usersChartData['labels']) ?>,
                datasets: [{
                    label: 'کاربران جدید',
                    data: <?= json_encode($usersChartData['data']) ?>,
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { display: false },
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    function updateChart(type, period) {
        fetch(`/admin/dashboard/chart-data?type=${type}&period=${period}`)
            .then(response => response.json())
            .then(data => {
                if (type === 'sales') {
                    salesChart.data.labels = data.labels;
                    salesChart.data.datasets[0].data = data.amounts;
                    salesChart.data.datasets[1].data = data.counts;
                    salesChart.update();
                } else if (type === 'users') {
                    usersChart.data.labels = data.labels;
                    usersChart.data.datasets[0].data = data.data;
                    usersChart.update();

                    if (data.total_this_month !== undefined) {
                         document.getElementById('totalUsersThisMonth').innerText = data.total_this_month;
                    }
                }
            })
            .catch(error => console.error('Error fetching chart data:', error));
    }

    document.addEventListener('DOMContentLoaded', initCharts);
</script>
