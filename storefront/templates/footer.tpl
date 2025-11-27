    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 lg:gap-12">

                <!-- Section 1: About Us -->
                <div class="md:col-span-1">
                    <h3 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-4">درباره فروشگاه مدرن</h3>
                    <p class="text-gray-500 text-sm leading-relaxed text-justify">
                        فروشگاه مدرن با هدف ارائه بهترین محصولات دیجیتال و لوازم جانبی با ضمانت اصالت و قیمت مناسب تأسیس شده است. ما متعهد به ارائه خدماتی متمایز و پشتیبانی ۲۴ ساعته هستیم تا تجربه خریدی مطمئن و لذت‌بخش را برای شما رقم بزنیم.
                    </p>
                </div>

                <!-- Section 2: Links -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-4">دسترسی سریع</h3>
                    <ul class="space-y-3">
                        <li><a href="/page/about-us" class="text-base text-gray-500 hover:text-primary-600 transition-colors">درباره ما</a></li>
                        <li><a href="/page/terms" class="text-base text-gray-500 hover:text-primary-600 transition-colors">قوانین و مقررات</a></li>
                        <li><a href="/page/contact-us" class="text-base text-gray-500 hover:text-primary-600 transition-colors">تماس با ما</a></li>
                        <li><a href="/page/faq" class="text-base text-gray-500 hover:text-primary-600 transition-colors">سوالات متداول</a></li>
                    </ul>
                </div>

                <!-- Section 3: Contact -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-4">ارتباط با ما</h3>
                    <div class="space-y-3 text-base text-gray-500">
                        <p class="flex items-start">
                            <span class="ml-2">📍</span>
                            <span><?php echo htmlspecialchars($settings['footer_address'] ?? 'تهران، خیابان آزادی، پلاک ۱۲۳'); ?></span>
                        </p>
                        <p class="flex items-center">
                            <span class="ml-2">📞</span>
                            <span dir="ltr"><?php echo htmlspecialchars($settings['contact_phone'] ?? '021-12345678'); ?></span>
                        </p>
                        <p class="flex items-center">
                            <span class="ml-2">📧</span>
                            <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?>" class="hover:text-primary-600 transition-colors">
                                <?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Section 4: Socials -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-4">شبکه‌های اجتماعی</h3>
                    <div class="flex space-x-4 space-x-reverse">
                        <?php if (!empty($settings['social_instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['social_instagram']); ?>" target="_blank" class="text-gray-400 hover:text-pink-600 transition-colors">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.407-.06 4.123-.06h.08c2.594 0 2.971.011 4.022.059.752.035 1.308.145 1.843.352.536.208 1.01.524 1.424.949.425.414.741.89.949 1.424.207.535.317 1.092.352 1.843.048 1.05.059 1.428.059 4.022 0 2.594-.011 2.971-.059 4.022-.059zm0 6a3 3 0 100 6 3 3 0 000-6zm0 1.6a1.4 1.4 0 110 2.8 1.4 1.4 0 010-2.8zm5.2-1.8a.8.8 0 100 1.6.8.8 0 000-1.6z" clip-rule="evenodd" /></svg>
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($settings['social_twitter'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['social_twitter']); ?>" target="_blank" class="text-gray-400 hover:text-blue-400 transition-colors">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($settings['social_linkedin'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['social_linkedin']); ?>" target="_blank" class="text-gray-400 hover:text-blue-700 transition-colors">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-100">
                <p class="text-base text-gray-400 text-center">
                    &copy; <?php echo jdate('Y'); ?> <?php echo htmlspecialchars($settings['site_title'] ?? 'فروشگاه مدرن'); ?>. تمامی حقوق محفوظ است.
                </p>
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
        class="fixed bottom-6 left-6 z-[60] px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 min-w-[300px]"
        :class="type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'"
        style="display: none;"
    >
        <div x-show="type === 'success'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div x-show="type === 'error'">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <span x-text="message" class="font-medium"></span>
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
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"
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
                    class="relative transform flex flex-col overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100"
                >
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50 rounded-t-2xl">
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title" x-text="currentTitle()"></h3>
                        <button @click="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <form @submit.prevent="currentStepAction()">
                        <!-- Modal Body -->
                        <div class="p-6 space-y-4 flex-1">
                                <!-- Step 1: Mobile -->
                                <div x-show="step === 'mobile'">
                                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2 text-right">شماره موبایل خود را وارد کنید</label>
                                    <input type="tel" x-model="mobile" id="mobile" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50 text-center text-lg tracking-wider" placeholder="09xxxxxxxxx" required>
                                </div>

                                <!-- Step 2: OTP -->
                                <div x-show="step === 'otp'">
                                    <p class="text-sm text-gray-600 mb-4 text-center">
                                        کد تایید ۶ رقمی ارسال شده به شماره <strong x-text="mobile" class="font-bold text-gray-900"></strong> را وارد کنید.
                                    </p>
                                    <div id="otp-inputs" dir="ltr" :class="{ 'otp-error': isError }">
                                        <!-- Pincode inputs will be generated here -->
                                    </div>
                                    <div class="text-center mt-4">
                                         <button @click="step = 'mobile'; errorMessage = ''; timer.stop()" type="button" class="text-sm text-gray-500 hover:text-gray-800 transition-colors">
                                            تغییر شماره
                                        </button>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <p x-show="errorMessage" x-text="errorMessage" class="mt-4 text-sm text-red-600 text-center bg-red-50 p-3 rounded-lg"></p>
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <!-- Step 1 Footer -->
                            <div x-show="step === 'mobile'">
                                <button type="submit" :disabled="isLoading" class="inline-flex w-full justify-center rounded-xl bg-primary-600 px-3 py-3 text-sm font-bold text-white shadow-sm hover:bg-primary-500 transition-colors disabled:opacity-50">
                                    <span x-show="!isLoading">ارسال کد تایید</span>
                                    <span x-show="isLoading">در حال ارسال...</span>
                                </button>
                            </div>

                            <!-- Step 2 Footer -->
                            <div x-show="step === 'otp'" class="flex items-center justify-between gap-x-4">
                                <button
                                    @click="sendOtp()"
                                    type="button"
                                    :disabled="timer.isActive || isLoading"
                                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-3 py-3 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 transition-colors disabled:opacity-50 flex-1"
                                >
                                    <span x-show="!timer.isActive">ارسال مجدد</span>
                                    <span x-show="timer.isActive" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span x-text="timer.formatTime()" dir="ltr"></span>
                                    </span>
                                </button>

                                <button type="submit" formnovalidate :disabled="isLoading" class="inline-flex w-full justify-center rounded-xl bg-green-600 px-3 py-3 text-sm font-bold text-white shadow-sm hover:bg-green-500 transition-colors disabled:opacity-50 flex-1">
                                    <span x-show="!isLoading">تایید و ورود</span>
                                    <span x-show="isLoading">در حال بررسی...</span>
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
    }
    .otp-error input:focus {
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
        border-color: #ef4444 !important;
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
                    // The backend now returns { status: true/false, user: {...} } or { status: false, message: ... }
                    // However, we also check HTTP status code as a fallback.
                    const isSuccess = body.status === true || status === 200;

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

                        // Show Toast
                        window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: msg, type: 'error' } }));

                        if (this.pincodeInstance) {
                            this.pincodeInstance.reset();
                            // Keep focus on the first input
                             const firstInput = this.pincodeInstance.getField(0);
                             if (firstInput) firstInput.focus();
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
