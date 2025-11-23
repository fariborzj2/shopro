<?php
// Ensure $user is defined (as an object) for create mode
if (!isset($user)) {
    $user = new stdClass();
    $user->name = '';
    $user->mobile = '';
    $user->status = 'active';
}
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Name -->
    <div class="col-span-1">
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام و نام خانوادگی <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
               value="<?= htmlspecialchars($user->name) ?>" required placeholder="مثال: علی محمدی">
    </div>

    <!-- Mobile -->
    <div class="col-span-1">
        <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">شماره موبایل <span class="text-red-500">*</span></label>
        <input type="text" id="mobile" name="mobile" dir="ltr"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm font-mono"
               value="<?= htmlspecialchars($user->mobile) ?>" required placeholder="09123456789">
    </div>

    <!-- Status -->
    <div class="col-span-1 md:col-span-2">
        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت حساب</label>
        <div class="relative">
            <select id="status" name="status" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm">
                <option value="active" <?= $user->status === 'active' ? 'selected' : '' ?>>فعال</option>
                <option value="inactive" <?= $user->status === 'inactive' ? 'selected' : '' ?>>غیرفعال</option>
                <option value="banned" <?= $user->status === 'banned' ? 'selected' : '' ?>>مسدود</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
            </div>
        </div>
    </div>
</div>
