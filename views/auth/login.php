<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold text-center mb-6">ورود به پنل مدیریت</h1>

    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">خطا!</strong>
            <span class="block sm:inline">نام کاربری یا رمز عبور اشتباه است.</span>
        </div>
    <?php endif; ?>

    <form action="<?= url('/login') ?>" method="POST">
        <?php partial('csrf_field'); ?>
        <div class="mb-4">
            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">نام کاربری:</label>
            <input type="text" id="username" name="username" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">رمز عبور:</label>
            <input type="password" id="password" name="password" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                ورود
            </button>
        </div>
    </form>
</div>
