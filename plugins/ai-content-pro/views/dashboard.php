<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">کل کارها</span>
                <span class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg">
                    <?php partial('icon', ['name' => 'dashboard', 'class' => 'w-5 h-5']); ?>
                </span>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white"><?php echo array_sum($stats); ?></div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">تکمیل شده</span>
                <span class="p-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-lg">
                    <?php partial('icon', ['name' => 'check', 'class' => 'w-5 h-5']); ?>
                </span>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white"><?php echo $stats['completed'] ?? 0; ?></div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">در حال پردازش / در صف</span>
                <span class="p-2 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-lg">
                    <?php partial('icon', ['name' => 'sun', 'class' => 'w-5 h-5']); ?>
                </span>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white"><?php echo ($stats['processing'] ?? 0) + ($stats['pending'] ?? 0); ?></div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">خطا خورده</span>
                <span class="p-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg">
                    <?php partial('icon', ['name' => 'close', 'class' => 'w-5 h-5']); ?>
                </span>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white"><?php echo $stats['failed'] ?? 0; ?></div>
        </div>
    </div>

    <!-- Job List -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">وضعیت کارهای هوش مصنوعی</h2>
            <button onclick="triggerWorker()" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-all flex items-center gap-2">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                پردازش دستی صف
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">نوع کار</th>
                        <th class="px-6 py-4 font-medium">وضعیت</th>
                        <th class="px-6 py-4 font-medium">تلاش‌ها</th>
                        <th class="px-6 py-4 font-medium">تاریخ ایجاد</th>
                        <th class="px-6 py-4 font-medium text-center">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($jobs as $job): ?>
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200"><?php echo str_replace('_', ' ', $job['type']); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $status_classes = [
                                    'pending' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                    'processing' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 animate-pulse',
                                    'completed' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                                    'failed' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                                ];
                                $status_labels = [
                                    'pending' => 'در صف',
                                    'processing' => 'در حال پردازش',
                                    'completed' => 'تکمیل شده',
                                    'failed' => 'خطا',
                                ];
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?php echo $status_classes[$job['status']] ?? ''; ?>">
                                    <?php echo $status_labels[$job['status']] ?? $job['status']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <?php echo $job['attempts']; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <?php echo \jdate('Y/m/d H:i', strtotime($job['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="showJobResult(<?php echo $job['id']; ?>)" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 p-1">
                                    <?php partial('icon', ['name' => 'eye', 'class' => 'w-5 h-5']); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($paginator->total_pages > 1): ?>
        <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
             <div class="flex items-center justify-center gap-2">
                <?php for ($i = 1; $i <= $paginator->total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm <?php echo $i == $paginator->current_page ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Result Modal (Simple for now) -->
<div id="jobModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeJobModal()"></div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl relative z-10 max-h-[80vh] flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white" id="modalTitle">جزئیات کار</h3>
                <button onclick="closeJobModal()" class="text-gray-400 hover:text-gray-600">
                    <?php partial('icon', ['name' => 'close', 'class' => 'w-6 h-6']); ?>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1" id="modalContent">
                <!-- Content injected here -->
            </div>
        </div>
    </div>
</div>

<script>
function showJobResult(id) {
    fetch(`/admin/api/ai/jobs/status/${id}`)
        .then(res => res.json())
        .then(job => {
            const modal = document.getElementById('jobModal');
            const content = document.getElementById('modalContent');
            const title = document.getElementById('modalTitle');

            title.innerText = `جزئیات کار #${job.id} (${job.type})`;

            let html = `<div class="space-y-4">`;
            if (job.status === 'completed') {
                html += `<div class="bg-green-50 dark:bg-green-900/10 p-4 rounded-xl border border-green-100 dark:border-green-800/30 text-green-800 dark:text-green-300">نتیجه با موفقیت ثبت شده است.</div>`;
                html += `<div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700 overflow-x-auto text-sm dir-ltr text-left">`;

                try {
                    const resData = JSON.parse(job.result);
                    if (typeof resData === 'string') {
                         html += `<div class="prose dark:prose-invert max-w-none dir-rtl text-right">${resData}</div>`;
                    } else {
                         html += `<pre class="text-gray-700 dark:text-gray-300">${JSON.stringify(resData, null, 2)}</pre>`;
                    }
                } catch(e) {
                    html += `<div class="prose dark:prose-invert max-w-none dir-rtl text-right">${job.result}</div>`;
                }

                html += `</div>`;
            } else if (job.status === 'failed') {
                html += `<div class="bg-red-50 dark:bg-red-900/10 p-4 rounded-xl border border-red-100 dark:border-red-800/30 text-red-800 dark:text-red-300 font-bold">خطا:</div>`;
                html += `<div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700 text-sm text-red-600 dark:text-red-400">${job.error_message}</div>`;
            } else {
                html += `<div class="text-gray-500 text-center py-8">در حال انتظار یا پردازش...</div>`;
            }
            html += `</div>`;

            content.innerHTML = html;
            modal.classList.remove('hidden');
        });
}

function closeJobModal() {
    document.getElementById('jobModal').classList.add('hidden');
}

function triggerWorker() {
    fetch('/admin/api/ai/process', { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            alert(`تعداد ${data.processed} مورد پردازش شد.`);
            location.reload();
        });
}
</script>
