<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div>
            <h2 class="text-2xl font-semibold leading-tight"><?php echo $title; ?></h2>
        </div>
        <div class="my-2 flex sm:flex-row flex-col">
            <a href="/custom-fields/create" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                ایجاد فیلد جدید
            </a>
        </div>
        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                برچسب (فارسی)
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                نام (متغیر)
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                نوع
                            </th>
                             <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                اجباری
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                وضعیت
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fields as $field): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($field['label_fa']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($field['name']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($field['type']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?php echo $field['is_required'] ? 'text-green-900' : 'text-red-900'; ?>">
                                    <span aria-hidden class="absolute inset-0 <?php echo $field['is_required'] ? 'bg-green-200' : 'bg-red-200'; ?> opacity-50 rounded-full"></span>
                                    <span class="relative"><?php echo $field['is_required'] ? 'بله' : 'خیر'; ?></span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?php echo $field['status'] === 'active' ? 'text-green-900' : 'text-red-900'; ?>">
                                    <span aria-hidden class="absolute inset-0 <?php echo $field['status'] === 'active' ? 'bg-green-200' : 'bg-red-200'; ?> opacity-50 rounded-full"></span>
                                    <span class="relative"><?php echo $field['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?></span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <a href="/custom-fields/edit/<?php echo $field['id']; ?>" class="text-indigo-600 hover:text-indigo-900">ویرایش</a>
                                <form action="/custom-fields/delete/<?php echo $field['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این فیلد اطمینان دارید؟');">
                                     <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                     <button type="submit" class="text-red-600 hover:text-red-900 ml-4">حذف</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
