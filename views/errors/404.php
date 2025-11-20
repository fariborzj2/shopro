<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 max-w-md w-full text-center">
    <div class="mb-6">
        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto text-red-500 dark:text-red-400">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">404</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">صفحه مورد نظر یافت نشد</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        متاسفانه صفحه‌ای که به دنبال آن هستید وجود ندارد یا حذف شده است.
        <?php if (!empty($message)): ?>
            <br>
            <span class="text-xs text-gray-400 mt-2 block font-mono ltr"><?= htmlspecialchars($message) ?></span>
        <?php endif; ?>
    </p>

    <a href="/admin" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-lg shadow-primary-500/30 transition-all w-full">
        بازگشت به داشبورد
        <svg class="w-4 h-4 mr-2 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
    </a>
</div>
