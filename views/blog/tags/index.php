<div x-data="tagManager()" x-init="init()" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    
    <!-- Header -->
    <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">مدیریت برچسب‌ها</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">لیست و مدیریت برچسب‌های بلاگ</p>
        </div>
        <button @click="openModal('create')" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors">
            <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5 ml-2']); ?>
            افزودن برچسب جدید
        </button>
    </div>
    
    <!-- Mobile List View -->
    <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-700">
        <template x-for="tag in tags" :key="tag.id">
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1" x-text="tag.name"></h3>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                    :class="tag.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'"
                    x-text="tag.status === 'active' ? 'فعال' : 'غیرفعال'">
                        
                    </span>
                </div>
                <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        تعداد استفاده: <span x-text="tag.posts_count || 0"></span>
                    </span>
                    <div class="flex items-center gap-3">
                        <button @click="openModal('edit', tag)" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">ویرایش</button>
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <button @click="deleteTag(tag.id)" class="text-red-600 dark:text-red-400 text-sm font-medium">حذف</button>
                    </div>
                </div>
            </div>
        </template>
        <template x-if="tags.length === 0">
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                هیچ برچسبی یافت نشد.
            </div>
        </template>
    </div>

    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">تعداد استفاده</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">عملیات</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <template x-for="tag in tags" :key="tag.id">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white" x-text="tag.name"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="tag.posts_count || 0"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="tag.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'"
                                  x-text="tag.status === 'active' ? 'فعال' : 'غیرفعال'">
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <button @click="openModal('edit', tag)" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 ml-4 transition-colors">ویرایش</button>
                            <button @click="deleteTag(tag.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">حذف</button>
                        </td>
                    </tr>
                </template>
                <template x-if="tags.length === 0">
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            هیچ برچسبی یافت نشد.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Modal - Moved outside to ensure correct z-index stacking if needed (Alpine logic remains same scope) -->
    <template x-teleport="body">
        <div x-show="isModalOpen" style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <!-- Backdrop -->
            <div x-show="isModalOpen"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/75 transition-opacity backdrop-blur-sm" @click="closeModal"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="isModalOpen"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 dark:border-gray-700">

                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white mb-4" id="modal-title" x-text="modalMode === 'create' ? 'افزودن برچسب جدید' : 'ویرایش برچسب'"></h3>

                        <form @submit.prevent="saveTag" class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام برچسب</label>
                                <input type="text" id="name" x-model="form.name" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors" required>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت</label>
                                <select id="status" x-model="form.status" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors">
                                    <option value="active">فعال</option>
                                    <option value="inactive">غیرفعال</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                        <button type="button" @click="saveTag" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">ذخیره</button>
                        <button type="button" @click="closeModal" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">انصراف</button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function tagManager() {
    return {
        tags: <?php echo json_encode($tags); ?>,
        isModalOpen: false,
        modalMode: 'create',
        form: {
            id: null,
            name: '',
            slug: '',
            status: 'active'
        },

        init() {
            // Initial load if needed
        },

        openModal(mode, tag = null) {
            this.modalMode = mode;
            this.isModalOpen = true;

            if (mode === 'edit' && tag) {
                this.form = { ...tag };
            } else {
                this.form = {
                    id: null,
                    name: '',
                    slug: '',
                    status: 'active'
                };
            }
        },

        closeModal() {
            this.isModalOpen = false;
        },

        saveTag() {
            const isEdit = this.modalMode === 'edit';
            const url = isEdit ? `/admin/blog/tags/update/${this.form.id}` : '/admin/blog/tags/store';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(this.form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.new_csrf_token) {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.new_csrf_token);
                }
                if (data.success) {
                    this.closeModal();
                    this.refreshTags(); // Reload list
                } else {
                    alert(data.message || 'خطا در ذخیره سازی');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('خطای سیستم رخ داد.');
            });
        },

        deleteTag(id) {
            if (!confirm('آیا از حذف این برچسب مطمئن هستید؟')) return;

            fetch(`/admin/blog/tags/delete/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.new_csrf_token) {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.new_csrf_token);
                }
                if (data.success) {
                    this.refreshTags();
                } else {
                    alert(data.message || 'خطا در حذف');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message.includes('403')) {
                    alert('شما اجازه حذف این آیتم را ندارید (403).');
                } else {
                    alert('خطای سیستم رخ داد: ' + error.message);
                }
            });
        },

        refreshTags() {
            // Fetch the updated list
            fetch('/admin/blog/tags', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                this.tags = data;
            });
        }
    }
}
</script>
