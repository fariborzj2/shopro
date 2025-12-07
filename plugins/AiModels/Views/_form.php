<?php
// app/Plugins/AiModels/Views/_form.php
$isEdit = isset($model) && $model->id;
$action = $isEdit ? "/admin/ai-models/update/{$model->id}" : "/admin/ai-models/store";
?>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6" x-data="aiModelForm()">
    <form action="<?php echo $action; ?>" method="POST" class="space-y-6" @submit="onSubmit">
        <?php csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Persian Name -->
            <div>
                <label for="name_fa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    نام فارسی <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name_fa" name="name_fa" required
                       value="<?php echo $isEdit ? htmlspecialchars($model->name_fa) : ''; ?>"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors">
            </div>

            <!-- English Name -->
            <div>
                <label for="name_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    نام انگلیسی (شناسه) <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name_en" name="name_en" required dir="ltr" x-model="nameEn"
                       value="<?php echo $isEdit ? htmlspecialchars($model->name_en) : ''; ?>"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors">
                <p class="mt-1 text-xs text-gray-500">یک نام یکتا به انگلیسی وارد کنید (مثال: gpt-4)</p>
            </div>
        </div>

        <!-- API Key -->
        <div>
            <label for="api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                کلید API <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-2">
                <input type="password" id="api_key" name="api_key" <?php echo $isEdit ? '' : 'required'; ?> dir="ltr" x-model="apiKey"
                       placeholder="<?php echo $isEdit ? 'برای تغییر وارد کنید...' : 'sk-...'; ?>"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors">
                
                <button type="button" @click="testConnection" :disabled="testing"
                        class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 transition-colors flex items-center gap-2 whitespace-nowrap">
                    <span x-show="!testing">تست اتصال</span>
                    <span x-show="testing">...</span>
                </button>
            </div>
            <p class="mt-2 text-sm text-green-600" x-show="testResult.status === 'success'" x-text="testResult.message"></p>
            <p class="mt-2 text-sm text-red-600" x-show="testResult.status === 'error'" x-text="testResult.message"></p>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">توضیحات</label>
            <textarea id="description" name="description" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors"><?php echo $isEdit ? htmlspecialchars($model->description) : ''; ?></textarea>
        </div>

        <!-- Status -->
        <div class="flex items-center">
            <input type="checkbox" id="is_active" name="is_active" value="1"
                   <?php echo (!$isEdit || $model->is_active) ? 'checked' : ''; ?>
                   class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
            <label for="is_active" class="mr-2 text-sm text-gray-700 dark:text-gray-300">فعال</label>
        </div>

        <!-- Actions -->
        <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
            <a href="/admin/ai-models" class="ml-3 px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">انصراف</a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all">
                <?php echo $isEdit ? 'بروزرسانی مدل' : 'ایجاد مدل'; ?>
            </button>
        </div>
    </form>
</div>

<script>
function aiModelForm() {
    return {
        apiKey: '',
        nameEn: '<?php echo $isEdit ? htmlspecialchars($model->name_en) : ''; ?>',
        testing: false,
        testResult: { status: null, message: '' },
        
        testConnection() {
            if (!this.apiKey) {
                this.testResult = { status: 'error', message: 'لطفا ابتدا کلید API را وارد کنید.' };
                return;
            }

            this.testing = true;
            this.testResult = { status: null, message: '' };

            fetch('/admin/ai-models/test-connection', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    api_key: this.apiKey,
                    name_en: this.nameEn
                })
            })
            .then(response => response.json())
            .then(data => {
                this.testResult = data;
            })
            .catch(error => {
                this.testResult = { status: 'error', message: 'خطای غیرمنتظره رخ داد.' };
            })
            .finally(() => {
                this.testing = false;
            });
        },
        
        onSubmit(e) {
            // Optional: Client side validation
        }
    }
}
</script>
