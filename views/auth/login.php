<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4 text-primary-600 dark:text-primary-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">ورود به پنل مدیریت</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">لطفاً برای ادامه وارد حساب کاربری خود شوید</p>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <strong class="block text-sm font-bold text-red-800 dark:text-red-400">خطا در ورود</strong>
                <span class="text-sm text-red-700 dark:text-red-300">نام کاربری یا رمز عبور اشتباه است.</span>
            </div>
        </div>
    <?php endif; ?>

    <form action="<?php echo url('/login') ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>

        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">نام کاربری</label>
            <input type="text" id="username" name="username" required autofocus
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all shadow-sm placeholder-gray-400"
                   placeholder="نام کاربری خود را وارد کنید">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">رمز عبور</label>
            <input type="password" id="password" name="password" required
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all shadow-sm placeholder-gray-400"
                   placeholder="••••••••">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <span class="mr-2 text-sm text-gray-600 dark:text-gray-400">مرا به خاطر بسپار</span>
            </label>
            <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">فراموشی رمز عبور؟</a>
        </div>

        <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform active:scale-[0.98]">
            ورود به حساب کاربری
        </button>
    </form>
</div>
<div class="mt-8 text-center text-xs text-gray-400 dark:text-gray-500">
    &copy; <?php echo date('Y'); ?> تمامی حقوق محفوظ است.
</div>
