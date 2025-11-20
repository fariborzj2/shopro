<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Basic Info -->
    <div class="bg-gray-50 p-4 rounded shadow-inner">
        <h3 class="text-lg font-medium mb-4 text-gray-700 border-b pb-2">اطلاعات حساب کاربری</h3>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">نام و نام خانوادگی</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($admin['name'] ?? ''); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">نام کاربری <span class="text-red-500">*</span></label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username'] ?? ''); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">ایمیل <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">سمت (Role) <span class="text-red-500">*</span></label>
            <input type="text" id="role" name="role" required value="<?php echo htmlspecialchars($admin['role'] ?? ''); ?>" placeholder="مثلاً: پشتیبانی، مدیر فروش" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="active" <?php echo (isset($admin['status']) && $admin['status'] === 'active') ? 'selected' : ''; ?>>فعال</option>
                <option value="inactive" <?php echo (isset($admin['status']) && $admin['status'] === 'inactive') ? 'selected' : ''; ?>>غیرفعال</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                <?php echo isset($admin) ? 'رمز عبور جدید (خالی بگذارید تا تغییر نکند)' : 'رمز عبور *'; ?>
            </label>
            <input type="password" id="password" name="password" <?php echo isset($admin) ? '' : 'required'; ?> class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    </div>

    <!-- Permissions -->
    <div class="bg-gray-50 p-4 rounded shadow-inner">
        <h3 class="text-lg font-medium mb-4 text-gray-700 border-b pb-2">سطح دسترسی (مجوزها)</h3>
        <p class="text-sm text-gray-500 mb-4">بخش‌هایی که این مدیر مجاز به دسترسی به آن‌ها است را انتخاب کنید. (انتخاب حداقل یک مورد الزامی است)</p>

        <?php
            $current_permissions = [];
            if (isset($admin) && !empty($admin['permissions'])) {
                $current_permissions = json_decode($admin['permissions'], true) ?? [];
            }
        ?>

        <div class="space-y-3">
            <?php foreach ($permissions_list as $key => $label): ?>
                <label class="flex items-center space-x-3 space-x-reverse">
                    <input type="checkbox" name="permissions[]" value="<?php echo $key; ?>"
                        class="form-checkbox h-5 w-5 text-blue-600"
                        <?php echo in_array($key, $current_permissions) ? 'checked' : ''; ?>>
                    <span class="text-gray-700"><?php echo $label; ?></span>
                </label>
            <?php endforeach; ?>
        </div>

        <?php if (isset($admin) && ($admin['is_super_admin'] ?? false)): ?>
            <div class="mt-6 p-3 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 text-sm">
                <p class="font-bold">توجه:</p>
                <p>این کاربر «مدیر کل» است و به تمام بخش‌ها دسترسی کامل دارد. تنظیمات دسترسی بالا برای او اعمال نمی‌شود.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
