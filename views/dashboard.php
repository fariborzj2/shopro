<!-- Dashboard Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">داشبورد</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">خلاصه وضعیت فروشگاه و عملکرد اخیر</p>
    </div>
    <div class="flex gap-2">
        <!-- Date Filter Placeholder -->
        <div class="bg-white dark:bg-gray-800 text-sm text-gray-500 dark:text-gray-300 px-4 py-2 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex items-center">
            <?php partial('icon', ['name' => 'dashboard', 'class' => 'w-4 h-4 ml-2 text-gray-400']); ?>
            <span><?= jdate('l، j F Y') ?></span>
        </div>
    </div>
</div>

<!-- KPIs Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Card 1: Sales Today -->
    <div class="group relative overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
        <div class="absolute top-0 right-0 w-24 h-24 bg-primary-50 dark:bg-primary-900/10 rounded-bl-full -mr-4 -mt-4 opacity-50 transition-transform group-hover:scale-110"></div>

        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="p-3 bg-primary-50 dark:bg-primary-900/20 rounded-xl text-primary-600 dark:text-primary-400 shadow-sm group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400 rounded-full border border-green-100 dark:border-green-800 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                <span><?= rand(1, 10) ?>%</span>
            </span>
        </div>
        <div class="relative z-10">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">فروش امروز</h3>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                <?= number_format((float)str_replace(',', '', $kpis['sales_today'])) ?>
                <span class="text-sm font-normal text-gray-400 mr-1">تومان</span>
            </p>
        </div>
    </div>

    <!-- Card 2: Orders Today -->
    <div class="group relative overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
         <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 dark:bg-blue-900/10 rounded-bl-full -mr-4 -mt-4 opacity-50 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-blue-600 dark:text-blue-400 shadow-sm group-hover:scale-105 transition-transform">
                <?php partial('icon', ['name' => 'orders', 'class' => 'w-6 h-6']); ?>
            </div>
        </div>
        <div class="relative z-10">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">سفارشات امروز</h3>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white tracking-tight"><?= $kpis['orders_today'] ?></p>
        </div>
    </div>

    <!-- Card 3: New Users -->
    <div class="group relative overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
        <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 dark:bg-purple-900/10 rounded-bl-full -mr-4 -mt-4 opacity-50 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
             <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl text-purple-600 dark:text-purple-400 shadow-sm group-hover:scale-105 transition-transform">
                <?php partial('icon', ['name' => 'users', 'class' => 'w-6 h-6']); ?>
            </div>
        </div>
        <div class="relative z-10">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">کاربران جدید امروز</h3>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white tracking-tight"><?= $kpis['new_users_today'] ?></p>
        </div>
    </div>

    <!-- Card 4: Monthly Orders -->
    <div class="group relative overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
         <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 dark:bg-orange-900/10 rounded-bl-full -mr-4 -mt-4 opacity-50 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
             <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-xl text-orange-600 dark:text-orange-400 shadow-sm group-hover:scale-105 transition-transform">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <div class="relative z-10">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">سفارشات این ماه</h3>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white tracking-tight"><?= $kpis['orders_this_month'] ?></p>
        </div>
    </div>
</div>

<!-- Aggregate Reports Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-primary-500 transition-colors cursor-default">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">مجموع کل کاربران</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($reports['total_users']) ?></p>
        </div>
        <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-primary-500 group-hover:bg-primary-50 dark:group-hover:bg-primary-900/20 transition-colors">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-indigo-500 transition-colors cursor-default">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">مجموع کل فروش</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                <?= number_format($reports['total_sales']) ?>
                <span class="text-sm font-normal text-gray-400">تومان</span>
            </p>
        </div>
        <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-indigo-500 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/20 transition-colors">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-emerald-500 transition-colors cursor-default">
         <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">سفارشات تکمیل‌شده</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($reports['total_completed_orders']) ?></p>
        </div>
        <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-emerald-500 group-hover:bg-emerald-50 dark:group-hover:bg-emerald-900/20 transition-colors">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-red-500 transition-colors cursor-default">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">سفارشات ناموفق امروز</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($reports['failed_orders_today']) ?></p>
        </div>
        <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-red-500 group-hover:bg-red-50 dark:group-hover:bg-red-900/20 transition-colors">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Sales Chart -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700">
        <div class="flex flex-wrap gap-4 justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">روند فروش</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">تحلیل فروش و تعداد سفارشات</p>
            </div>
            <div class="flex bg-gray-50 dark:bg-gray-700/50 rounded-xl p-1">
                <button onclick="updateChart('sales', 'week')" class="px-4 py-1.5 text-xs font-medium rounded-lg transition-all focus:outline-none active-chart-filter" id="sales-week-btn">هفته</button>
                <button onclick="updateChart('sales', 'month')" class="px-4 py-1.5 text-xs font-medium rounded-lg transition-all focus:outline-none text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" id="sales-month-btn">ماه</button>
                <button onclick="updateChart('sales', 'year')" class="px-4 py-1.5 text-xs font-medium rounded-lg transition-all focus:outline-none text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" id="sales-year-btn">سال</button>
            </div>
        </div>
        <div class="relative h-60 w-full">
             <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Users Chart -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700">
        <div class="flex flex-wrap gap-4 justify-between items-center mb-6">
             <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">کاربران جدید</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                    مجموع ماه جاری: <span id="totalUsersThisMonth" class="font-bold text-primary-600 dark:text-primary-400"><?= $usersChartData['total_this_month'] ?? 0 ?></span>
                </p>
            </div>
             <div class="flex bg-gray-50 dark:bg-gray-700/50 rounded-xl p-1">
                <button onclick="updateChart('users', 'week')" class="px-4 py-1.5 text-xs font-medium rounded-lg transition-all focus:outline-none active-chart-filter" id="users-week-btn">هفته</button>
                <button onclick="updateChart('users', 'month')" class="px-4 py-1.5 text-xs font-medium rounded-lg transition-all focus:outline-none text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" id="users-month-btn">ماه</button>
                <button onclick="updateChart('users', 'year')" class="px-4 py-1.5 text-xs font-medium rounded-lg transition-all focus:outline-none text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" id="users-year-btn">سال</button>
            </div>
        </div>
        <div class="relative h-60 w-full">
             <canvas id="usersChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex flex-wrap gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-800">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">آخرین سفارشات</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">لیست ۱۰ تراکنش اخیر فروشگاه</p>
        </div>
        <a href="<?php echo url('/orders'); ?>" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium flex items-center gap-1 transition-colors">
            مشاهده همه
            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4 rotate-90']); ?>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/30">
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">کد سفارش</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">کاربر</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">مبلغ</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت پرداخت</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت سفارش</th>
                    <th class="px-6 py-3 border-b border-gray-100 dark:border-gray-700"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                <?php if(empty($recent_orders)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            هنوز سفارشی ثبت نشده است.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recent_orders as $order): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-mono whitespace-nowrap">
                                <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md text-xs text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($order['order_code']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold ml-3 shadow-sm">
                                        <?= mb_substr($order['user_name'] ?? 'م', 0, 1) ?>
                                    </div>
                                    <span class="font-medium"><?= htmlspecialchars($order['user_name'] ?? 'مهمان') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-bold whitespace-nowrap">
                                <?= number_format($order['amount']) ?> <span class="text-xs font-normal text-gray-400">تومان</span>
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <?php
                                    $p_status = strtolower($order['payment_status']);
                                    $p_class = 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';
                                    $p_dot = 'bg-gray-400';

                                    if ($p_status === 'paid') {
                                        $p_class = 'bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800';
                                        $p_dot = 'bg-emerald-500';
                                    }
                                    elseif ($p_status === 'failed') {
                                        $p_class = 'bg-red-50 text-red-700 border border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800';
                                        $p_dot = 'bg-red-500';
                                    }
                                    elseif ($p_status === 'unpaid') {
                                        $p_class = 'bg-amber-50 text-amber-700 border border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800';
                                        $p_dot = 'bg-amber-500';
                                    }
                                ?>
                                <span class="px-2.5 py-0.5 inline-flex items-center gap-1.5 text-xs font-medium rounded-full <?= $p_class ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= $p_dot ?>"></span>
                                    <?= translate_payment_status_fa($order['payment_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <?php
                                    $o_status = strtolower($order['order_status']);
                                    $o_class = 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';

                                    if ($o_status === 'completed') $o_class = 'text-emerald-600 dark:text-emerald-400 font-medium';
                                    elseif ($o_status === 'pending') $o_class = 'text-blue-600 dark:text-blue-400 font-medium';
                                    elseif ($o_status === 'cancelled' || $o_status === 'phishing') $o_class = 'text-red-600 dark:text-red-400';
                                ?>
                                <span class="<?= $o_class ?>">
                                    <?= translate_order_status_fa($order['order_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <a href="<?= url('orders/show/' . $order['id']) ?>" class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 inline-flex">
                                    <?php partial('icon', ['name' => 'eye', 'class' => 'w-5 h-5']); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .active-chart-filter {
        background-color: #fff;
        color: #4f46e5; /* primary-600 */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .dark .active-chart-filter {
        background-color: #4b5563; /* gray-600 */
        color: #fff;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize Charts with Dark Mode Support
    let salesChart, usersChart;

    // Detect Dark Mode for Chart Config
    const isDark = () => document.documentElement.classList.contains('dark');
    const textColor = () => isDark() ? '#9ca3af' : '#64748b'; // gray-400 / slate-500
    const gridColor = () => isDark() ? '#374151' : '#f1f5f9'; // gray-700 / slate-100

    function initCharts() {
        // Sales Chart configuration
        const salesCtx = document.getElementById('salesChart').getContext('2d');

        // Gradient for Line
        let gradientLine = salesCtx.createLinearGradient(0, 0, 0, 400);
        gradientLine.addColorStop(0, 'rgba(236, 72, 153, 0.5)'); // Pink
        gradientLine.addColorStop(1, 'rgba(236, 72, 153, 0.0)');

        salesChart = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($salesChartData['labels']) ?>,
                datasets: [
                    {
                        label: 'مبلغ فروش (تومان)',
                        data: <?= json_encode($salesChartData['amounts']) ?>,
                        backgroundColor: '#6366f1', // Indigo 500
                        hoverBackgroundColor: '#4f46e5',
                        borderRadius: 6,
                        barThickness: 'flex',
                        maxBarThickness: 32,
                        yAxisID: 'y',
                        order: 2
                    },
                    {
                        label: 'تعداد فروش',
                        data: <?= json_encode($salesChartData['counts']) ?>,
                        type: 'line',
                        borderColor: '#ec4899', // Pink 500
                        backgroundColor: gradientLine,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#ec4899',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        yAxisID: 'y1',
                        order: 1,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        labels: {
                            color: textColor(),
                            font: { family: 'Estedad', size: 12 },
                            usePointStyle: true,
                            boxWidth: 8
                        },
                        position: 'top',
                        align: 'end'
                    },
                    tooltip: {
                        titleFont: { family: 'Estedad', size: 13 },
                        bodyFont: { family: 'Estedad', size: 12 },
                        backgroundColor: isDark() ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.95)',
                        titleColor: isDark() ? '#fff' : '#0f172a',
                        bodyColor: isDark() ? '#cbd5e1' : '#475569',
                        borderColor: isDark() ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('fa-IR').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor(), font: { family: 'Estedad' } },
                        border: { display: false }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { color: gridColor(), borderDash: [5, 5], drawBorder: false },
                        ticks: {
                            color: textColor(),
                            font: { family: 'Estedad' },
                            callback: function(value) { return value >= 1000 ? (value/1000) + 'k' : value; }
                        },
                        border: { display: false }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { display: false },
                        ticks: { display: false }, // Hide counts axis labels for cleaner look
                        border: { display: false }
                    }
                }
            }
        });

        // Users Chart
        const usersCtx = document.getElementById('usersChart').getContext('2d');

        let gradientUsers = usersCtx.createLinearGradient(0, 0, 0, 400);
        gradientUsers.addColorStop(0, 'rgba(16, 185, 129, 0.2)'); // Emerald
        gradientUsers.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        usersChart = new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($usersChartData['labels']) ?>,
                datasets: [{
                    label: 'کاربران جدید',
                    data: <?= json_encode($usersChartData['data']) ?>,
                    backgroundColor: gradientUsers,
                    borderColor: '#10b981', // Emerald 500
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        titleFont: { family: 'Estedad' },
                        bodyFont: { family: 'Estedad' },
                        backgroundColor: isDark() ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.95)',
                        titleColor: isDark() ? '#fff' : '#0f172a',
                        bodyColor: isDark() ? '#cbd5e1' : '#475569',
                        borderColor: isDark() ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                             label: function(context) {
                                return 'کاربران: ' + new Intl.NumberFormat('fa-IR').format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor(), font: { family: 'Estedad' } },
                        border: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor(), borderDash: [5, 5], drawBorder: false },
                        ticks: { color: textColor(), font: { family: 'Estedad' }, stepSize: 1 },
                        border: { display: false }
                    }
                }
            }
        });
    }

    function updateChart(type, period) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Update button states
        document.querySelectorAll(`[id^="${type}-"]`).forEach(btn => {
             btn.classList.remove('active-chart-filter');
             btn.classList.add('text-gray-500', 'dark:text-gray-400', 'hover:text-gray-900', 'dark:hover:text-white');
        });
        const activeBtn = document.getElementById(`${type}-${period}-btn`);
        if(activeBtn) {
            activeBtn.classList.add('active-chart-filter');
            activeBtn.classList.remove('text-gray-500', 'dark:text-gray-400', 'hover:text-gray-900', 'dark:hover:text-white');
        }

        fetch(`/admin/dashboard/chart-data?type=${type}&period=${period}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
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
                         const el = document.getElementById('totalUsersThisMonth');
                         if(el) el.innerText = data.total_this_month;
                    }
                }
            })
            .catch(error => console.error('Error fetching chart data:', error));
    }

    // Watch for dark mode changes to update charts
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === "class") {
                 if(salesChart) {
                     salesChart.options.scales.x.ticks.color = textColor();
                     salesChart.options.scales.y.ticks.color = textColor();
                     salesChart.options.scales.y.grid.color = gridColor();
                     salesChart.options.plugins.legend.labels.color = textColor();
                     salesChart.update();
                 }
                 if(usersChart) {
                     usersChart.options.scales.x.ticks.color = textColor();
                     usersChart.options.scales.y.ticks.color = textColor();
                     usersChart.options.scales.y.grid.color = gridColor();
                     usersChart.update();
                 }
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        initCharts();
        observer.observe(document.documentElement, { attributes: true });
    });
</script>
