<?php
// app/Plugins/AiModels/Views/index.php
?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight">مدیریت مدل‌های هوش مصنوعی</h1>
        <a href="/admin/ai-models/create" class="flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all">
            <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5 ml-2']); ?>
            افزودن مدل جدید
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">نام مدل</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">شناسه (EN)</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">وضعیت</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">تاریخ ایجاد</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if (empty($models)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                هیچ مدلی یافت نشد.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($models as $model): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($model['name_fa']); ?></div>
                                    <?php if($model['description']): ?>
                                        <div class="text-xs text-gray-500 mt-1 truncate max-w-xs"><?php echo htmlspecialchars($model['description']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-mono" dir="ltr">
                                    <?php echo htmlspecialchars($model['name_en']); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($model['is_active']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            فعال
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            غیرفعال
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <?php echo \jdate('Y/m/d H:i', strtotime($model['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="/admin/ai-models/edit/<?php echo $model['id']; ?>" 
                                           class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="ویرایش">
                                            <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                                        </a>
                                        
                                        <form action="/admin/ai-models/delete/<?php echo $model['id']; ?>" method="POST" 
                                              onsubmit="return confirm('آیا از حذف این مدل اطمینان دارید؟');" class="inline-block">
                                            <?php csrf_field(); ?>
                                            <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="حذف">
                                                <?php partial('icon', ['name' => 'trash', 'class' => 'w-5 h-5']); ?>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
