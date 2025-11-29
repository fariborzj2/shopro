    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 border-t border-gray-800 mt-auto relative overflow-hidden">
        <!-- Abstract Decoration -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary-900 rounded-full mix-blend-overlay filter blur-3xl opacity-20 pointer-events-none"></div>

        <div class="container py-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">

                <!-- Section 1: About Us -->
                <div class="md:col-span-1 lg:col-span-1">
                     <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <span class="text-xl font-black text-white tracking-tight">فروشگاه مدرن</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed text-justify mb-6">
                        ما در فروشگاه مدرن، با تکیه بر تجربه و تخصص، تلاش می‌کنیم تا جدیدترین محصولات دیجیتال را با بهترین قیمت و کیفیت در اختیار شما قرار دهیم. هدف ما جلب رضایت و اعتماد شماست.
                    </p>
                    <div class="flex gap-4">
                         <?php if (!empty($settings['social_instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['social_instagram']); ?>" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-pink-600 hover:text-white transition-all duration-300">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.407-.06 4.123-.06h.08c2.594 0 2.971.011 4.022.059.752.035 1.308.145 1.843.352.536.208 1.01.524 1.424.949.425.414.741.89.949 1.424.207.535.317 1.092.352 1.843.048 1.05.059 1.428.059 4.022 0 2.594-.011 2.971-.059 4.022-.059zm0 6a3 3 0 100 6 3 3 0 000-6zm0 1.6a1.4 1.4 0 110 2.8 1.4 1.4 0 010-2.8zm5.2-1.8a.8.8 0 100 1.6.8.8 0 000-1.6z" clip-rule="evenodd" /></svg>
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($settings['social_twitter'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['social_twitter']); ?>" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-blue-400 hover:text-white transition-all duration-300">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($settings['social_linkedin'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['social_linkedin']); ?>" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-blue-700 hover:text-white transition-all duration-300">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Section 2: Links -->
                <div>
                    <h3 class="text-sm font-bold text-white tracking-wider uppercase mb-6 border-b border-gray-800 pb-2 inline-block">دسترسی سریع</h3>
                    <ul class="space-y-4">
                        <li><a href="/page/about-us" class="text-gray-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-gray-600 rounded-full"></span>درباره ما</a></li>
                        <li><a href="/page/terms" class="text-gray-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-gray-600 rounded-full"></span>قوانین و مقررات</a></li>
                        <li><a href="/page/contact-us" class="text-gray-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-gray-600 rounded-full"></span>تماس با ما</a></li>
                        <li><a href="/page/faq" class="text-gray-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-gray-600 rounded-full"></span>سوالات متداول</a></li>
                    </ul>
                </div>

                 <!-- Section 3: Categories (Mock) -->
                <div>
                    <h3 class="text-sm font-bold text-white tracking-wider uppercase mb-6 border-b border-gray-800 pb-2 inline-block">دسته‌بندی‌ها</h3>
                    <ul class="space-y-4">
                        <li><a href="/#products" class="text-gray-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-gray-600 rounded-full"></span>موبایل و تبلت</a></li>
                        <li><a href="/#products" class="text-gray-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-gray-600 rounded-full"></span>لپ‌تاپ و کامپیوتر</a></li>
                        <li><a href="/#products" class="text-gray-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-gray-600 rounded-full"></span>لوازم جانبی</a></li>
                        <li><a href="/blog" class="text-gray-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-gray-600 rounded-full"></span>اخبار تکنولوژی</a></li>
                    </ul>
                </div>

                <!-- Section 4: Contact -->
                <div>
                    <h3 class="text-sm font-bold text-white tracking-wider uppercase mb-6 border-b border-gray-800 pb-2 inline-block">اطلاعات تماس</h3>
                    <div class="space-y-6 text-sm text-gray-400">
                        <div class="flex items-start gap-4">
                             <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center shrink-0 text-primary-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-gray-200 font-bold mb-1">آدرس دفتر مرکزی</h4>
                                <p><?php echo htmlspecialchars($settings['footer_address'] ?? 'تهران، خیابان آزادی، پلاک ۱۲۳'); ?></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                             <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center shrink-0 text-primary-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-gray-200 font-bold mb-1">شماره تماس</h4>
                                <p dir="ltr" class="text-right"><?php echo htmlspecialchars($settings['contact_phone'] ?? '021-12345678'); ?></p>
                            </div>
                        </div>

                         <div class="flex items-start gap-4">
                             <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center shrink-0 text-primary-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-gray-200 font-bold mb-1">ایمیل پشتیبانی</h4>
                                <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?>" class="hover:text-primary-400 transition-colors">
                                    <?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-gray-800 text-center md:text-right flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500">
                    &copy; <?php echo jdate('Y'); ?> <?php echo htmlspecialchars($settings['site_title'] ?? 'فروشگاه مدرن'); ?>. تمامی حقوق محفوظ است.
                </p>
                <div class="flex gap-4">
                    <!-- Payment Icons Mockup -->
                    <div class="h-8 w-12 bg-white/10 rounded-md"></div>
                    <div class="h-8 w-12 bg-white/10 rounded-md"></div>
                    <div class="h-8 w-12 bg-white/10 rounded-md"></div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast Notification Container -->
    <div
        x-data="{ show: false, message: '', type: 'error' }"
        @show-toast.window="show = true; message = $event.detail.message; type = $event.detail.type || 'error'; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-6 left-6 z-[60] px-6 py-4 rounded-xl shadow-floating flex items-center gap-3 min-w-[320px] backdrop-blur-md border border-white/20"
        :class="type === 'success' ? 'bg-green-600/90 text-white' : 'bg-red-600/90 text-white'"
        style="display: none;"
    >
        <div x-show="type === 'success'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div x-show="type === 'error'">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <span x-text="message" class="font-bold text-sm"></span>
    </div>

    <!-- Auth Modal (Standardized for Alpine/Tailwind) -->
    <div
        x-data="authModal()"
        x-init="init()"
        @open-auth-modal.window="openModal()"
        x-show="isOpen"
        x-cloak
        class="relative z-50"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <!-- Background backdrop -->
        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm"
        ></div>

        <!-- Modal panel -->
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    x-show="isOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="closeModal()"
                    class="relative transform flex flex-col overflow-hidden rounded-3xl bg-white text-right shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100"
                >
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-black text-gray-900" id="modal-title" x-text="currentTitle()"></h3>
                        <button @click="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-red-50 hover:text-red-500 rounded-xl w-10 h-10 inline-flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <form @submit.prevent="currentStepAction()">
                        <!-- Modal Body -->
                        <div class="p-8 space-y-6 flex-1">
                                <!-- Step 1: Mobile -->
                                <div x-show="step === 'mobile'">
                                    <label for="mobile" class="form-label text-center mb-6 text-base">شماره موبایل خود را وارد کنید</label>
                                    <div class="relative">
                                        <input type="tel" x-model="mobile" id="mobile" class="form-input text-center text-2xl tracking-widest font-bold dir-ltr" placeholder="09xxxxxxxxx" required autofocus>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-4 text-center">برای دریافت کد تایید، شماره موبایل الزامی است.</p>
                                </div>

                                <!-- Step 2: OTP -->
                                <div x-show="step === 'otp'">
                                    <p class="text-sm text-gray-600 mb-6 text-center leading-relaxed">
                                        کد تایید ۶ رقمی به شماره <strong x-text="mobile" class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded dir-ltr inline-block"></strong> ارسال شد.
                                    </p>
                                    <div id="otp-inputs" dir="ltr" :class="{ 'otp-error': isError }" class="flex justify-center gap-2">
                                        <!-- Pincode inputs will be generated here -->
                                    </div>
                                    <div class="text-center mt-6">
                                         <button @click="step = 'mobile'; errorMessage = ''; timer.stop()" type="button" class="text-xs text-primary-600 font-bold hover:text-primary-700 transition-colors flex items-center justify-center gap-1 mx-auto">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" /></svg>
                                            ویرایش شماره موبایل
                                        </button>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div x-show="errorMessage" class="bg-red-50 text-red-600 p-4 rounded-xl flex items-start gap-3 animate-pulse">
                                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <p x-text="errorMessage" class="text-sm font-medium"></p>
                                </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-6 border-t border-gray-100 bg-gray-50/50 rounded-b-3xl">
                            <!-- Step 1 Footer -->
                            <div x-show="step === 'mobile'">
                                <button type="submit" :disabled="isLoading" class="btn btn-primary w-full py-3.5 shadow-xl shadow-primary-500/20">
                                    <span x-show="!isLoading">دریافت کد تایید</span>
                                    <span x-show="isLoading" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        در حال پردازش...
                                    </span>
                                </button>
                            </div>

                            <!-- Step 2 Footer -->
                            <div x-show="step === 'otp'" class="flex items-center justify-between gap-x-4">
                                <button
                                    @click="sendOtp()"
                                    type="button"
                                    :disabled="timer.isActive || isLoading"
                                    class="btn btn-secondary flex-1 py-3.5"
                                >
                                    <span x-show="!timer.isActive">ارسال مجدد کد</span>
                                    <span x-show="timer.isActive" class="flex items-center gap-2 text-gray-500">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span x-text="timer.formatTime()" dir="ltr" class="font-mono"></span>
                                    </span>
                                </button>

                                <button type="submit" formnovalidate :disabled="isLoading" class="btn btn-primary flex-1 py-3.5 shadow-xl shadow-primary-500/20 bg-emerald-600 hover:bg-emerald-700 hover:shadow-emerald-600/30">
                                    <span x-show="!isLoading">ورود به حساب</span>
                                    <span x-show="isLoading" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        بررسی...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<style>
    /* OTP Error State Styling */
    .otp-error input {
        border-color: #ef4444 !important; /* Tailwind red-500 */
        background-color: #fef2f2 !important; /* Tailwind red-50 */
        color: #ef4444 !important;
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }

    @keyframes shake {
      10%, 90% { transform: translate3d(-1px, 0, 0); }
      20%, 80% { transform: translate3d(2px, 0, 0); }
      30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
      40%, 60% { transform: translate3d(4px, 0, 0); }
    }
</style>

<script>
    function otpTimer(durationInSeconds) {
        return {
            remaining: durationInSeconds,
            isActive: false,
            interval: null,
            start() {
                this.remaining = durationInSeconds;
                this.isActive = true;
                this.interval = setInterval(() => {
                    this.remaining--;
                    if (this.remaining <= 0) {
                        this.stop();
                    }
                }, 1000);
            },
            stop() {
                clearInterval(this.interval);
                this.isActive = false;
                this.remaining = 0;
            },
            formatTime() {
                const minutes = Math.floor(this.remaining / 60);
                const seconds = this.remaining % 60;
                return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
        }
    }

    function authModal() {
        return {
            isOpen: false,
            step: 'mobile',
            mobile: '',
            otp: '',
            isLoading: false,
            errorMessage: '',
            pincodeInstance: null,
            isError: false,
            timer: otpTimer(120), // 2 minutes timer

            init() {
                this.$watch('step', (newState) => {
                    if (newState === 'otp') {
                        this.$nextTick(() => {
                            this.initPincode();
                        });
                    }
                });
            },

            initPincode() {
                const otpContainer = document.getElementById('otp-inputs');
                if (otpContainer) {
                    // Clear previous instance if exists
                    otpContainer.innerHTML = '';
                    this.pincodeInstance = new pinCode(otpContainer, {
                        fields: 6,
                        autofocus: true,
                        hideinput: false,
                        reset: false,
                        complete: (pincode) => {
                            this.otp = pincode;
                            this.verifyOtp();
                        }
                    });

                    // Clear error when user types
                    otpContainer.addEventListener('input', () => {
                         this.isError = false;
                         this.errorMessage = '';
                    }, { capture: true });

                    if ('OTPCredential' in navigator) {
                        const ac = new AbortController();
                        navigator.credentials.get({
                            otp: { transport: ['sms'] },
                            signal: ac.signal
                        }).then(otp => {
                            const otpCode = otp.code;
                            if (otpCode && this.pincodeInstance) {
                                const chars = otpCode.split('');
                                chars.forEach((char, index) => {
                                    const field = this.pincodeInstance.getField(index);
                                    if (field) {
                                        field.value = char;
                                        this.pincodeInstance.values[index] = char;
                                    }
                                });

                                // Check if all fields are filled to trigger complete
                                if (chars.length === this.pincodeInstance.settings.fields) {
                                    this.pincodeInstance.settings.complete(otpCode);
                                }
                            }
                        }).catch(err => {
                            // User aborted, or other error. We can safely ignore.
                            console.log('WebOTP API failed:', err);
                        });
                    }
                }
            },

            convertPersianToEnglish(str) {
                if (!str) return str;
                const persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                const arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
                return str.toString()
                    .replace(/[۰-۹]/g, d => persian.indexOf(d))
                    .replace(/[٠-٩]/g, d => arabic.indexOf(d));
            },

            openModal() { this.isOpen = true; },
            closeModal() {
                this.isOpen = false;
                // Don't reset if timer is active, so user can see it if they reopen modal
                if (!this.timer.isActive) {
                    this.reset();
                }
            },
            reset() {
                this.step = 'mobile';
                this.mobile = '';
                this.otp = '';
                this.isLoading = false;
                this.errorMessage = '';
                this.isError = false;
                this.timer.stop();
                if (this.pincodeInstance) {
                    this.pincodeInstance.reset();
                }
            },
            currentTitle() {
                return this.step === 'mobile' ? 'ورود به حساب کاربری' : 'تایید شماره موبایل';
            },
            currentStepAction() {
                // This is called on form submit (Enter key)
                if (this.step === 'mobile') {
                    this.sendOtp();
                } else if (this.step === 'otp') {
                    this.verifyOtp();
                }
            },
            sendOtp() {
                // If timer is active, don't resend
                if (this.timer.isActive) return;

                this.isLoading = true;
                this.errorMessage = '';
                this.isError = false;

                const normalizedMobile = this.convertPersianToEnglish(this.mobile);

                fetch('/api/auth/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ mobile: normalizedMobile })
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        this.step = 'otp';
                        this.timer.start();
                        if (body.new_csrf_token) {
                            document.querySelector('meta[name="csrf-token"]').setAttribute('content', body.new_csrf_token);
                        }
                    } else {
                        this.errorMessage = body.error || 'خطا در ارسال کد.';
                    }
                })
                .catch(() => { this.errorMessage = 'خطای ارتباط با سرور.'; })
                .finally(() => { this.isLoading = false; });
            },
            verifyOtp() {
                this.isLoading = true;
                this.errorMessage = '';
                this.isError = false;

                const normalizedMobile = this.convertPersianToEnglish(this.mobile);
                const normalizedOtp = this.convertPersianToEnglish(this.otp);

                fetch('/api/auth/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ mobile: normalizedMobile, otp: normalizedOtp })
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    // Update CSRF Token
                    if (body.new_csrf_token) {
                         document.querySelector('meta[name="csrf-token"]').setAttribute('content', body.new_csrf_token);
                    }

                    // Check response logic
                    // Rely STRICTLY on body.status which is now guaranteed by AuthController
                    const isSuccess = body.status === true;

                    if (isSuccess) {
                        // Success Logic
                        // 1. Update Global Store
                        if (body.user && Alpine.store('auth')) {
                            Alpine.store('auth').login(body.user);
                        }

                        // 2. Show Success Toast
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: {
                                message: body.message || 'ورود با موفقیت انجام شد.',
                                type: 'success'
                            }
                        }));

                        // 3. Handle Redirect (if present)
                        if (body.redirect) {
                            window.location.href = body.redirect;
                        } else {
                            // 4. Close Modal (no refresh)
                            this.closeModal();
                        }

                    } else {
                        // Error Logic
                        const msg = body.message || body.error || 'کد نامعتبر است.';
                        this.errorMessage = msg;
                        this.isError = true;

                        // Show Toast - explicitly type 'error' for red color
                        window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: msg, type: 'error' } }));

                        // Only reset if API says expired or similar fatal error
                        // For simple wrong code, we do NOT reset the input, as per requirements.
                        if (msg.includes('منقضی')) {
                             if (this.pincodeInstance) {
                                this.pincodeInstance.reset();
                                const firstInput = this.pincodeInstance.getField(0);
                                if (firstInput) firstInput.focus();
                            }
                        } else {
                            // Focus back on first input or let user edit existing?
                            // User asked: "Field should not be cleared"
                            // Just ensure focus is somewhere useful or let them correct it.
                             if (this.pincodeInstance) {
                                const firstInput = this.pincodeInstance.getField(0);
                                if (firstInput) firstInput.focus();
                            }
                        }
                    }
                })
                .catch((err) => {
                    console.error(err);
                    const msg = 'خطای ارتباط با سرور.';
                    this.errorMessage = msg;
                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: msg, type: 'error' } }));
                 })
                .finally(() => { this.isLoading = false; });
            }
        }
    }
</script>

<!-- SwiperJS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="/js/pincode.js"></script>
<script src="/js/error-modal.js" defer></script>

<?php
    $style = 'storefront';
    include __DIR__ . '/../../views/partials/_error_modal.php';
?>


</body>
</html>
