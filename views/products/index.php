<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">مدیریت محصولات</h1>
        <a href="/products/create" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            افزودن محصول جدید
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نام محصول</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">دسته‌بندی</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">قیمت</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($product['id']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($product['name_fa']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($product['category_name']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= number_format($product['price']) ?> تومان</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <?php
                                $status_map = [
                                    'available' => ['text' => 'موجود', 'class' => 'bg-green-100 text-green-700'],
                                    'unavailable' => ['text' => 'ناموجود', 'class' => 'bg-red-100 text-red-700'],
                                    'draft' => ['text' => 'پیش‌نویس', 'class' => 'bg-yellow-100 text-yellow-700'],
                                ];
                                $status_info = $status_map[$product['status']] ?? ['text' => 'نامشخص', 'class' => 'bg-gray-100 text-gray-700'];
                            ?>
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full <?= $status_info['class'] ?>">
                                <?= htmlspecialchars($status_info['text']) ?>
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            <a href="/products/edit/<?= $product['id'] ?>" class="text-indigo-600 hover:text-indigo-900">ویرایش</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php partial('pagination', ['paginator' => $paginator]); ?>
</div>
