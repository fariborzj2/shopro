<!-- Error Modal -->
<div
    x-data
    x-show="$store.errorModal.isOpen"
    x-cloak
    @keydown.escape.window="$store.errorModal.closeModal()"
    class="relative z-[9999]"
    aria-labelledby="error-modal-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Background backdrop -->
    <div
        x-show="$store.errorModal.isOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
    ></div>

    <!-- Modal panel -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                x-show="$store.errorModal.isOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.outside="$store.errorModal.closeModal()"
                class="relative transform overflow-hidden rounded-2xl text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg
                    <?php echo ($style ?? 'admin') === 'storefront'
                        ? 'bg-white dark:bg-gray-800/70 border border-white/20 backdrop-blur-lg'
                        : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700'; ?>"
            >
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-5 <?php echo ($style ?? 'admin') === 'storefront' ? '' : 'bg-white dark:bg-gray-800'; ?>">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 lg:ml-5 sm:mx-0 sm:h-10 sm:w-10">
                            <?php
                                $iconPath = ($style ?? 'admin') === 'storefront'
                                    ? '<svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>'
                                    : partial('icon', ['name' => 'alert-triangle', 'class' => 'h-6 w-6 text-red-600 dark:text-red-400'], true);
                                echo $iconPath;
                            ?>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-right flex-1">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100" id="error-modal-title" x-text="$store.errorModal.title"></h3>
                            <div class="mt-2">
                                <p class="text-sm <?php echo ($style ?? 'admin') === 'storefront' ? 'text-gray-700 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400'; ?>" x-text="$store.errorModal.message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-4 sm:flex sm:flex-row-reverse <?php echo ($style ?? 'admin') === 'storefront' ? 'bg-black/5 dark:bg-white/5' : 'bg-gray-50 dark:bg-gray-800/50'; ?>">
                    <button
                        @click="$store.errorModal.closeModal()"
                        type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md px-6 py-3 text-sm font-semibold shadow-sm sm:mt-0 sm:w-auto
                            <?php echo ($style ?? 'admin') === 'storefront'
                                ? 'inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors'
                                : 'inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors'; ?>"
                    >
                        باشه
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
