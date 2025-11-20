<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 max-w-md w-full text-center">
    <div class="mb-6">
        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto text-red-500 dark:text-red-400 animate-pulse">
             <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">500</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">خطای سرور</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        متاسفانه مشکلی در سمت سرور رخ داده است. لطفاً دوباره تلاش کنید یا با پشتیبانی تماس بگیرید.
        <?php if (!empty($message)): ?>
            <div class="mt-4 p-3 bg-gray-100 dark:bg-gray-900/50 rounded-lg text-left">
                <code class="text-xs text-red-600 dark:text-red-400 font-mono break-all block"><?= htmlspecialchars($message) ?></code>
            </div>
        <?php endif; ?>
    </p>

    <div class="flex flex-col gap-3">
        <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors w-full dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
            تلاش مجدد
        </button>

        <a href="/admin" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-lg shadow-primary-500/30 transition-all w-full">
            بازگشت به داشبورد
        </a>
    </div>
</div>
