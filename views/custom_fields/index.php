<div class="flex justify-between items-center mt-8">
    <h2 class="text-2xl font-semibold text-gray-700">لیست پارامترها</h2>
    <a href="<?php echo url('/custom-fields/create'); ?>" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">
        ایجاد پارامتر جدید
    </a>
</div>

<div class="mt-4">
    <div class="p-6 bg-white rounded-md shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نام</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">برچسب</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نوع</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($fields)): ?>
                        <tr>
                            <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">هیچ پارامتری یافت نشد.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($fields as $field): ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($field->name); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($field->label_fa); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($field->type); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?php echo $field->status === 'active' ? 'text-green-900' : 'text-red-900'; ?>">
                                        <span aria-hidden="true" class="absolute inset-0 <?php echo $field->status === 'active' ? 'bg-green-200' : 'bg-red-200'; ?> opacity-50 rounded-full"></span>
                                        <span class="relative"><?php echo $field->status === 'active' ? 'فعال' : 'غیرفعال'; ?></span>
                                    </span>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                    <a href="<?php echo url('/custom-fields/edit/' . $field->id); ?>" class="text-indigo-600 hover:text-indigo-900">ویرایش</a>
                                    <form action="<?php echo url('/custom-fields/delete/' . $field->id); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این پارامتر اطمینان دارید؟');">
                                        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-4">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
