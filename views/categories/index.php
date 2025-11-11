<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">مدیریت دسته‌بندی‌ها</h1>
        <a href="/categories/create" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            افزودن دسته‌بندی جدید
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نام دسته‌بندی</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">والد</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">وضعیت</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                </tr>
            </thead>
            <tbody id="sortable-table">
                <?php foreach ($categories as $category): ?>
                    <tr data-id="<?= $category['id'] ?>">
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm cursor-move"><?= htmlspecialchars($category['id']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($category['name_fa']) ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($category['parent_name'] ?? '—') ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full <?= $category['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                                <?= $category['status'] === 'active' ? 'فعال' : 'غیرفعال' ?>
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            <a href="/categories/edit/<?= $category['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">ویرایش</a>
                            <form action="/categories/delete/<?= $category['id'] ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این دسته‌بندی مطمئن هستید؟ با حذف این دسته‌بندی، ممکن است محصولات مرتبط دچار مشکل شوند.');">
                                <?php partial('csrf_field'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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

            fetch('/categories/reorder', {
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
