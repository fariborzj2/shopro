<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <div class="p-6 flex flex-col md:flex-row md:justify-between md:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">مدیریت محصولات</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">لیست محصولات، قیمت‌ها و موجودی انبار</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <form method="GET" action="/admin/products" class="flex flex-col sm:flex-row gap-2 relative">
                <select name="category_id" onchange="this.form.submit()" class="w-full sm:w-48 px-3 py-2 text-sm text-gray-700 bg-gray-50 dark:bg-gray-700/50 dark:text-gray-200 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    <option value="">همه دسته‌بندی‌ها</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id ?>" <?= (isset($selected_category) && $selected_category == $category->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category->name_fa) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="جستجو در نام محصول..." class="w-full pl-10 pr-4 py-2 text-sm text-gray-700 bg-gray-50 dark:bg-gray-700/50 dark:text-gray-200 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    <button type="submit" class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 p-1">
                        <?php partial('icon', ['name' => 'search', 'class' => 'w-4 h-4']); ?>
                    </button>
                </div>
            </form>
            <a href="<?php echo url('products/create'); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors whitespace-nowrap">
                <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5 ml-2']); ?>
                افزودن محصول جدید
            </a>
        </div>
    </div>

    <!-- Mobile Cards View (Visible only on small screens) -->
    <div class="block md:hidden divide-y divide-gray-200 dark:divide-gray-700">
        <?php foreach ($products as $product): ?>
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-mono text-gray-400">#<?= $product['id'] ?></span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white mt-1"><?= htmlspecialchars($product['name_fa']) ?></h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($product['category_name']) ?></p>
                    </div>
                     <?php
                        $status_map = [
                            'available' => ['text' => 'موجود', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'],
                            'unavailable' => ['text' => 'ناموجود', 'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
                            'draft' => ['text' => 'پیش‌نویس', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
                        ];
                        $status_info = $status_map[$product['status']] ?? ['text' => 'نامشخص', 'class' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'];
                    ?>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $status_info['class'] ?>">
                        <?= htmlspecialchars($status_info['text']) ?>
                    </span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400"><?= number_format($product['price']) ?> <span class="text-xs font-normal text-gray-500">تومان</span></span>
                    <div class="flex items-center space-x-2 space-x-reverse">
                         <a href="<?php echo url('products/edit/' . $product['id']); ?>" class="p-1 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400">
                            <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                        </a>
                         <form action="<?php echo url('products/delete/' . $product['id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این محصول مطمئن هستید؟');">
                            <?php partial('csrf_field'); ?>
                            <button type="submit" class="p-1 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400">
                                <?php partial('icon', ['name' => 'trash', 'class' => 'w-5 h-5']); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-right">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام محصول</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">دسته‌بندی</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">قیمت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">تعداد / مبلغ فروش</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">عملیات</th>
                </tr>
            </thead>
            <tbody id="sortable-table" class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <?php foreach ($products as $product): ?>
                    <tr data-id="<?= $product['id'] ?>" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-gray-400 font-mono cursor-move group-hover:text-gray-600 dark:group-hover:text-gray-300">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-300 cursor-move" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                <?= htmlspecialchars($product['id']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            <?= htmlspecialchars($product['name_fa']) ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="px-2 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-xs">
                                <?= htmlspecialchars($product['category_name']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-bold">
                            <?= number_format($product['price']) ?> <span class="font-normal text-gray-500 text-xs">تومان</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    <?= number_format($product['sales_count'] ?? 0) ?> عدد
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    <?= number_format($product['total_revenue'] ?? 0) ?> تومان
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <?php
                                // Reusing status map logic
                                $status_info = $status_map[$product['status']] ?? ['text' => 'نامشخص', 'class' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'];
                            ?>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $status_info['class'] ?>">
                                <?= htmlspecialchars($status_info['text']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-3 space-x-reverse opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?php echo url('products/edit/' . $product['id']); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="ویرایش">
                                    <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                                </a>
                                <form action="<?php echo url('products/delete/' . $product['id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این محصول مطمئن هستید؟');">
                                    <?php partial('csrf_field'); ?>
                                    <button type="submit" class="text-gray-400 hover:text-red-600 dark:text-gray-500 dark:hover:text-red-400 transition-colors" title="حذف">
                                        <?php partial('icon', ['name' => 'trash', 'class' => 'w-5 h-5']); ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-b-xl">
        <?php partial('pagination', ['paginator' => $paginator]); ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    const sortableTable = document.getElementById('sortable-table');
    if (sortableTable) {
        new Sortable(sortableTable, {
            animation: 150,
            handle: '.cursor-move',
            ghostClass: 'bg-indigo-50',
            onEnd: function (evt) {
                const rows = Array.from(evt.target.children);
                const ids = rows.map(row => row.getAttribute('data-id'));

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/admin/products/reorder', { // Adjusted path to include /admin prefix if needed, but relying on relative logic mostly
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // Show error toast (using simple alert for now as fallback)
                        alert('خطا در ذخیره ترتیب جدید.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
</script>
