<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">مدیریت محصولات</h1>
        <a href="<?php echo url('products/create'); ?>" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
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
            <tbody id="sortable-table">
                <?php foreach ($products as $product): ?>
                    <tr data-id="<?= $product['id'] ?>">
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm cursor-move"><?= htmlspecialchars($product['id']) ?></td>
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
                            <a href="<?php echo url('products/edit/' . $product['id']); ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">ویرایش</a>
                            <form action="<?php echo url('products/delete/' . $product['id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این محصول مطمئن هستید؟');">
                                <?php partial('csrf_field'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php partial('pagination', ['paginator' => $paginator]); ?>
</div>

<script>
    const sortableTable = document.getElementById('sortable-table');
    new Sortable(sortableTable, {
        animation: 150,
        handle: '.cursor-move',
        onEnd: function (evt) {
            const rows = Array.from(evt.target.children);
            const ids = rows.map(row => row.getAttribute('data-id'));

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/products/reorder', {
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
                    alert('خطا در ذخیره ترتیب جدید.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('یک خطای پیش‌بینی نشده رخ داد.');
            });
        }
    });
</script>
