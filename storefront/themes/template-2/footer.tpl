        <!-- Footer -->
        <div class="section">
            <div class="center">
                <div class="footer pd-30 white">

                    <div class="mb-20">
                        <div class="">
                            <div class="mb-20">
                                <a href="/" class="logo max-w120"><img src="/template-2/images/logo.svg" alt=""></a>
                            </div>
                            <p class="text-justify grow-1 basis400 font-size-1-2">
                                <?php echo htmlspecialchars($settings['meta_description'] ?? 'فروشگاه مدرن ارائه دهنده بهترین خدمات و محصولات دیجیتال.'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="mt-20 d-flex-wrap pd-t-40 white fix-mr10 border-t">

                        <div class="footer-item basis150 grow-1">
                            <h3>دسترسی سریع</h3>
                            <div class="ft-list mt-10">
                                <ul>
                                    <li><a href="/page/about-us">درباره ما</a></li>
                                    <li><a href="/page/contact-us">تماس با ما</a></li>
                                    <li><a href="/page/terms">قوانین و مقررات</a></li>
                                    <li><a href="/page/faq">سوالات متداول</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="footer-item basis150 grow-1">
                            <h3>خدمات ما</h3>
                            <div class="ft-list mt-10">
                                <ul>
                                    <li><a href="/">خرید اشتراک</a></li>
                                    <li><a href="/blog">وبلاگ آموزشی</a></li>
                                    <li><a href="/dashboard/orders">پیگیری سفارش</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="footer-item basis150 grow-1">
                            <h3>تماس با ما</h3>
                            <div class="ft-list mt-10">
                                <ul>
                                    <li><?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?></li>
                                    <li><?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?></li>
                                    <li><?php echo htmlspecialchars($settings['footer_address'] ?? ''); ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="footer-item basis200 grow-4">
                            <div class="title">
                                <h2>راه های ارتباطی با ما</h2>
                                <span class="color-bright">از طریق تلگرام و یا برقراری تماس می‌توانید با تیم پشتیبانی ما در ارتباط باشید.</span>
                            </div>
                            <div class="d-flex-wrap align-center just-around text-center fix-mr5">

                                <?php if (!empty($settings['social_telegram'])): ?>
                                <a href="<?php echo htmlspecialchars($settings['social_telegram']); ?>" class="btn border white m-5 ">
                                    <i class="icon-telegram-bold ml-5"></i> پشتیبانی تلگرام
                                </a>
                                <?php endif; ?>

                                <a href="tel:<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>" class="white m-5 grow-1" >
                                    <div class="font-size-2" dir="ltr"><?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?></div>
                                    <div class="">پشتیبانی تلفنی</div>
                                </a>

                            </div>
                        </div>

                    </div>

                </div>

                <div class="d-flex-wrap align-center just-around">
                    <div class="grow-1 pd-10">
                        <p>&copy; <?php echo jdate('Y'); ?> <?php echo htmlspecialchars($settings['site_title'] ?? 'فروشگاه'); ?>. تمامی حقوق محفوظ است.</p>
                    </div>
                    <div class="d-flex-wrap m-10 fix-mr5">
                        <?php if (!empty($settings['social_instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['social_instagram']); ?>" aria-label="instagram pages" class="d-block height-40 width-40 min-w40 bg-light-blue radius-100 text-center line44 m-5 color-text"><i class="icon-instagram-1"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/template-2/js/cute-alert.js" defer></script>
    <script src="/template-2/js/swiper-bundle.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.4/lottie.min.js" defer></script>
    <script src="/template-2/js/init-masonry.js" defer></script>
    <script src="/template-2/js/site.js" defer></script>
    <script src="/js/pincode.js"></script>

    <!-- Toast Notification Container -->
    <div
        x-data="{ show: false, message: '', type: 'error' }"
        @show-toast.window="show = true; message = $event.detail.message; type = $event.detail.type || 'error'; setTimeout(() => show = false, 3000)"
        x-show="show"
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

    <!-- Auth Modal -->
    <div
        x-data="authModal()"
        x-init="init()"
        @open-auth-modal.window="openModal()"
        x-show="isOpen"
        x-cloak
        class="relative z-50 auth-modal-reset"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <!-- Background backdrop -->
        <div
            x-show="isOpen"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"
        ></div>

        <!-- Modal panel -->
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    x-show="isOpen"
                    @click.outside="closeModal()"
                    class="relative transform flex flex-col overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100"
                >
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50 rounded-t-2xl">
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title" x-text="currentTitle()"></h3>
                        <button @click="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center">
                            <span class="sr-only">Close modal</span>
                            ✖
                        </button>
                    </div>

                    <form @submit.prevent="currentStepAction()">
                        <!-- Modal Body -->
                        <div class="p-6 space-y-4 flex-1">
                                <!-- Step 1: Mobile -->
                                <div x-show="step === 'mobile'">
                                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2 text-right">شماره موبایل خود را وارد کنید</label>
                                    <input type="tel" x-model="mobile" id="mobile" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50 text-center text-lg tracking-wider" placeholder="09xxxxxxxxx" required style="border: 1px solid #ccc;">
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
                                <button type="submit" :disabled="isLoading" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-3 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-500 transition-colors disabled:opacity-50" style="background-color: #2563eb; color: white;">
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
                                        <span x-text="timer.formatTime()" dir="ltr"></span>
                                    </span>
                                </button>

                                <button type="submit" formnovalidate :disabled="isLoading" class="inline-flex w-full justify-center rounded-xl bg-green-600 px-3 py-3 text-sm font-bold text-white shadow-sm hover:bg-green-500 transition-colors disabled:opacity-50 flex-1" style="background-color: #16a34a; color: white;">
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
    .otp-error input {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
        color: #ef4444 !important;
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
            timer: otpTimer(120),

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
                    otpContainer.addEventListener('input', () => {
                         this.isError = false;
                         this.errorMessage = '';
                    }, { capture: true });
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
                if (this.step === 'mobile') {
                    this.sendOtp();
                } else if (this.step === 'otp') {
                    this.verifyOtp();
                }
            },
            sendOtp() {
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
                    if (body.new_csrf_token) {
                         document.querySelector('meta[name="csrf-token"]').setAttribute('content', body.new_csrf_token);
                    }
                    const isSuccess = body.status === true;

                    if (isSuccess) {
                        if (body.user && Alpine.store('auth')) {
                            Alpine.store('auth').login(body.user);
                        }
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: {
                                message: body.message || 'ورود با موفقیت انجام شد.',
                                type: 'success'
                            }
                        }));
                        if (body.redirect) {
                            window.location.href = body.redirect;
                        } else {
                            this.closeModal();
                        }
                    } else {
                        const msg = body.message || body.error || 'کد نامعتبر است.';
                        this.errorMessage = msg;
                        this.isError = true;
                        window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: msg, type: 'error' } }));
                         if (this.pincodeInstance) {
                            const firstInput = this.pincodeInstance.getField(0);
                            if (firstInput) firstInput.focus();
                        }
                    }
                })
                .catch((err) => {
                    const msg = 'خطای ارتباط با سرور.';
                    this.errorMessage = msg;
                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: msg, type: 'error' } }));
                 })
                .finally(() => { this.isLoading = false; });
            }
        }
    }
</script>

<?php
    $style = 'storefront';
    include __DIR__ . '/../../views/partials/_error_modal.php';
?>

</body>
</html>
