<?php echo csrf_field(); ?>

<div class="space-y-8">
    <!-- Section: Basic Info -->
    <section>
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <span class="p-1.5 bg-indigo-100 dark:bg-indigo-900 rounded-lg text-indigo-600 dark:text-indigo-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            اطلاعات پایه
        </h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Persian Name -->
            <div class="col-span-1">
                <label for="name_fa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام فارسی <span class="text-red-500">*</span></label>
                <input type="text" id="name_fa" name="name_fa"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       value="<?php echo htmlspecialchars($product['name_fa'] ?? ''); ?>" required placeholder="مثال: گوشی موبایل آیفون ۱۳">
            </div>

            <!-- English Name -->
            <div class="col-span-1">
                <label for="name_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام انگلیسی</label>
                <input type="text" id="name_en" name="name_en" dir="ltr"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       value="<?php echo htmlspecialchars($product['name_en'] ?? ''); ?>" placeholder="Example: iPhone 13 Mobile">
            </div>

            <!-- Category -->
            <div class="col-span-1">
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">دسته‌بندی <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="category_id" name="category_id"
                            class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm" required>
                        <option value="">انتخاب کنید...</option>
                        <?php $currentCategoryId = $product['category_id'] ?? null; ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->id; ?>" <?php echo ($category->id == $currentCategoryId) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category->name_fa); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                     <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                    </div>
                </div>
            </div>

            <!-- Position -->
            <div class="col-span-1">
                <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الویت نمایش</label>
                <input type="number" id="position" name="position"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       value="<?php echo htmlspecialchars($product['position'] ?? '0'); ?>">
            </div>
        </div>
    </section>

    <hr class="border-gray-200 dark:border-gray-700">

    <!-- Section: Pricing -->
    <section>
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
             <span class="p-1.5 bg-emerald-100 dark:bg-emerald-900 rounded-lg text-emerald-600 dark:text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            اطلاعات مالی و وضعیت
        </h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Dollar Price -->
            <div class="col-span-1">
                <label for="dollar_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">قیمت دلاری ($)</label>
                <div class="relative">
                    <input type="number" step="0.01" id="dollar_price" name="dollar_price" dir="ltr"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white pl-8 pr-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                           value="<?php echo htmlspecialchars($product['dollar_price'] ?? ''); ?>" placeholder="0.00">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        $
                    </div>
                </div>
            </div>

            <!-- Toman Price -->
            <div class="col-span-1">
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">قیمت (تومان) <span class="text-red-500">*</span></label>
                 <div class="relative">
                    <input type="number" id="price" name="price" dir="ltr"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                           value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" required placeholder="0">
                </div>
            </div>

            <!-- Old Price -->
            <div class="col-span-1">
                <label for="old_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">قیمت خط‌خورده (تومان)</label>
                <input type="number" id="old_price" name="old_price" dir="ltr"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       value="<?php echo htmlspecialchars($product['old_price'] ?? ''); ?>" placeholder="نمایش تخفیف">
            </div>

            <!-- Status -->
            <div class="col-span-1 md:col-span-3">
                 <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">وضعیت انتشار</label>
                 <div class="grid grid-cols-3 gap-4">
                     <!-- Available -->
                     <label class="cursor-pointer">
                         <input type="radio" name="status" value="available" class="peer sr-only" <?php echo (!isset($product['status']) || $product['status'] === 'available') ? 'checked' : ''; ?>>
                         <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 transition-all flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400 peer-checked:text-emerald-600 dark:peer-checked:text-emerald-400">
                             <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                             <span class="font-medium text-sm">موجود</span>
                         </div>
                     </label>

                     <!-- Unavailable -->
                     <label class="cursor-pointer">
                         <input type="radio" name="status" value="unavailable" class="peer sr-only" <?php echo (isset($product['status']) && $product['status'] === 'unavailable') ? 'checked' : ''; ?>>
                         <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 transition-all flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400 peer-checked:text-red-600 dark:peer-checked:text-red-400">
                             <span class="w-3 h-3 rounded-full bg-red-500"></span>
                             <span class="font-medium text-sm">ناموجود</span>
                         </div>
                     </label>

                     <!-- Draft -->
                     <label class="cursor-pointer">
                         <input type="radio" name="status" value="draft" class="peer sr-only" <?php echo (isset($product['status']) && $product['status'] === 'draft') ? 'checked' : ''; ?>>
                         <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-900/20 transition-all flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400 peer-checked:text-amber-600 dark:peer-checked:text-amber-400">
                             <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                             <span class="font-medium text-sm">پیش‌نویس</span>
                         </div>
                     </label>
                 </div>
            </div>
        </div>
    </section>
</div>

<?php $tinyMceContext = 'products'; ?>
<?php require_once PROJECT_ROOT . '/views/partials/tinymce_config.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dollarPriceInput = document.getElementById('dollar_price');
    const tomanPriceInput = document.getElementById('price');
    const autoUpdateEnabled = <?php echo json_encode($auto_update_prices ?? false); ?>;
    const exchangeRate = <?php echo json_encode($dollar_exchange_rate ?? 50000); ?>;

    function calculatePrice() {
        if (autoUpdateEnabled && dollarPriceInput.value) {
            const dollarPrice = parseFloat(dollarPriceInput.value);
            if (!isNaN(dollarPrice)) {
                tomanPriceInput.value = Math.round(dollarPrice * exchangeRate);
            }
        }
    }

    if (autoUpdateEnabled) {
        if (dollarPriceInput.value) {
            tomanPriceInput.readOnly = true;
            tomanPriceInput.classList.add('bg-gray-100', 'dark:bg-gray-700', 'cursor-not-allowed');
        }
        dollarPriceInput.addEventListener('input', function() {
             const hasValue = !!this.value;
             tomanPriceInput.readOnly = hasValue;
             if(hasValue) {
                 tomanPriceInput.classList.add('bg-gray-100', 'dark:bg-gray-700', 'cursor-not-allowed');
             } else {
                 tomanPriceInput.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'cursor-not-allowed');
             }
             calculatePrice();
        });
    }
});
</script>
