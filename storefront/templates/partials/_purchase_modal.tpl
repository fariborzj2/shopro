<!-- Purchase Modal -->
<div
    x-show="isModalOpen"
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-cloak
>
    <div
        x-show="isModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"
    ></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                x-show="isModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.outside="isModalOpen = false"
                class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200"
            >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <template x-if="selectedProduct">
                        <div>
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                                <h3 class="text-xl font-bold text-gray-900" id="modal-title" x-text="selectedProduct.name"></h3>
                                <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <form @submit.prevent="submitOrder" id="purchaseForm" method="POST" action="/api/payment/start">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" :value="selectedProduct.id">

                                <div class="space-y-5 max-h-[60vh] overflow-y-auto px-1 -mx-1 custom-scrollbar">
                                    <template x-for="field in customFields" :key="field.id">
                                        <div>
                                            <label :for="'field_' + field.id" class="block text-sm font-bold text-gray-700 mb-2">
                                                <span x-text="field.label"></span>
                                                <span x-show="field.is_required" class="text-red-500">*</span>
                                            </label>

                                            <!-- Text/Number/Date -->
                                            <template x-if="['text', 'number', 'date', 'color'].includes(field.type)">
                                                <input
                                                    :type="field.type"
                                                    :name="field.name"
                                                    :id="'field_' + field.id"
                                                    :placeholder="field.placeholder"
                                                    :required="field.is_required"
                                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50"
                                                >
                                            </template>

                                            <!-- Textarea -->
                                            <template x-if="field.type === 'textarea'">
                                                <textarea
                                                    :name="field.name"
                                                    :id="'field_' + field.id"
                                                    :placeholder="field.placeholder"
                                                    :required="field.is_required"
                                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50"
                                                    rows="3"
                                                ></textarea>
                                            </template>

                                            <!-- Select -->
                                            <template x-if="field.type === 'select'">
                                                <select
                                                    :name="field.name"
                                                    :id="'field_' + field.id"
                                                    :required="field.is_required"
                                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50"
                                                >
                                                    <option value="" disabled selected>انتخاب کنید...</option>
                                                    <template x-for="option in field.options" :key="option.value">
                                                        <option :value="option.value" x-text="option.label"></option>
                                                    </template>
                                                </select>
                                            </template>

                                            <!-- Radio -->
                                            <template x-if="field.type === 'radio'">
                                                <div class="space-y-2">
                                                    <template x-for="option in field.options" :key="option.value">
                                                        <label class="flex items-center space-x-3 space-x-reverse cursor-pointer">
                                                            <input
                                                                type="radio"
                                                                :name="field.name"
                                                                :id="'field_' + field.id + '_' + option.value"
                                                                :value="option.value"
                                                                :required="field.is_required"
                                                                class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500"
                                                            >
                                                            <span class="text-sm text-gray-700" x-text="option.label"></span>
                                                        </label>
                                                    </template>
                                                </div>
                                            </template>

                                            <!-- Checkbox -->
                                            <template x-if="field.type === 'checkbox'">
                                                 <div class="space-y-2">
                                                    <template x-for="option in field.options" :key="option.value">
                                                        <label class="flex items-center space-x-3 space-x-reverse cursor-pointer">
                                                            <input
                                                                type="checkbox"
                                                                :name="field.name + '[]'"
                                                                :id="'field_' + field.id + '_' + option.value"
                                                                :value="option.value"
                                                                class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                                            >
                                                            <span class="text-sm text-gray-700" x-text="option.label"></span>
                                                        </label>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                <div class="mt-8 flex flex-col gap-3">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent bg-primary-600 px-4 py-3 text-base font-bold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:text-sm transition-colors">
                                        پرداخت نهایی
                                    </button>
                                    <button @click="isModalOpen = false" type="button" class="w-full inline-flex justify-center rounded-xl border border-gray-300 bg-white px-4 py-3 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:text-sm transition-colors">
                                        انصراف
                                    </button>
                                </div>
                            </form>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
