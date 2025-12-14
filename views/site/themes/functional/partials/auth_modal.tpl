<!-- Auth Modal Component (Alpine.js) -->
<div x-data="authModal()"
     @open-auth-modal.window="open()"
     class="relative z-[60]"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true"
     x-show="isOpen"
     x-cloak>

    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="isOpen"
                 @click.away="close()"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-right w-full">
                            <h3 class="text-xl font-bold leading-6 text-slate-900 mb-2" id="modal-title">
                                <span x-text="step === 1 ? 'ورود یا ثبت نام' : 'کد تایید'"></span>
                            </h3>
                            <div class="mt-2">
                                <p x-text="step === 1 ? 'لطفا شماره موبایل خود را وارد کنید.' : 'کد ارسال شده به ' + mobile + ' را وارد کنید.'" class="text-sm text-slate-500 mb-6"></p>

                                <!-- Step 1: Mobile Input -->
                                <div x-show="step === 1" class="space-y-4">
                                    <div class="relative">
                                        <input type="tel" x-model="mobile" @keyup.enter="sendOtp" placeholder="09xxxxxxxxx"
                                               class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all text-left font-mono text-lg" dir="ltr">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                        </div>
                                    </div>
                                    <p x-show="error" x-text="error" class="text-red-500 text-sm text-right"></p>
                                    <button @click="sendOtp" :disabled="loading" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-lg shadow-primary-500/20">
                                        <span x-show="loading" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                                        <span x-text="loading ? 'در حال ارسال...' : 'ادامه'"></span>
                                    </button>
                                </div>

                                <!-- Step 2: OTP Input -->
                                <div x-show="step === 2" class="space-y-6">
                                     <div class="dir-ltr code-input-container flex justify-center gap-2" id="otp-inputs">
                                        <!-- Inputs injected by pincode.js usually, or manual -->
                                        <!-- Using manual distinct inputs for better styling control if pincode.js not compatible, but trying to use pincode.js class -->
                                        <div class="pin-code flex gap-2 justify-center" dir="ltr">
                                            <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border border-slate-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 bg-slate-50 font-mono transition-all">
                                            <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border border-slate-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 bg-slate-50 font-mono transition-all">
                                            <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border border-slate-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 bg-slate-50 font-mono transition-all">
                                            <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border border-slate-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 bg-slate-50 font-mono transition-all">
                                            <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border border-slate-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 bg-slate-50 font-mono transition-all">
                                        </div>
                                    </div>

                                    <p x-show="error" x-text="error" class="text-red-500 text-sm text-center"></p>

                                    <div class="flex flex-col gap-3">
                                        <button @click="verifyOtp" :disabled="loading" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-lg shadow-primary-500/20">
                                             <span x-show="loading" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                                            <span x-text="loading ? 'در حال بررسی...' : 'تایید و ورود'"></span>
                                        </button>
                                        <button @click="step = 1" class="text-slate-500 text-sm hover:text-slate-700">تغییر شماره موبایل</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function authModal() {
        return {
            isOpen: false,
            step: 1,
            mobile: '',
            otp: '',
            loading: false,
            error: null,

            open() {
                this.isOpen = true;
                this.step = 1;
                this.error = null;
                this.mobile = '';
            },

            close() {
                this.isOpen = false;
            },

            async sendOtp() {
                if (!this.mobile || this.mobile.length < 10) {
                    this.error = 'شماره موبایل معتبر نیست';
                    return;
                }
                this.loading = true;
                this.error = null;

                try {
                    // Logic to call API
                    const formData = new FormData();
                    formData.append('mobile', this.mobile);
                    // Fetch CSRF
                    const csrf = document.querySelector('meta[name="csrf-token"]').content;
                    formData.append('csrf_token', csrf);

                    const res = await fetch('/login', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();

                    if (data.success) {
                        this.step = 2;
                        // Initialize pincode if needed, or focus first input
                        this.$nextTick(() => {
                            const firstInput = this.$el.querySelector('.pin-code input');
                            if(firstInput) firstInput.focus();
                            this.initPincode();
                        });
                    } else {
                        this.error = data.message || 'خطا در ارسال کد';
                    }
                } catch (e) {
                    this.error = 'خطای ارتباط با سرور';
                } finally {
                    this.loading = false;
                }
            },

            initPincode() {
                // Simple implementation for demo or reuse global PincodeInput
                const inputs = this.$el.querySelectorAll('.pin-code input');
                inputs.forEach((input, index) => {
                    input.addEventListener('input', (e) => {
                        if(e.target.value.length === 1 && index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                        this.otp = Array.from(inputs).map(i => i.value).join('');
                    });
                    input.addEventListener('keydown', (e) => {
                         if(e.key === 'Backspace' && !e.target.value && index > 0) {
                            inputs[index - 1].focus();
                        }
                    });
                });
            },

            async verifyOtp() {
                 if (this.otp.length < 5) {
                    this.error = 'کد تایید کامل نیست';
                    return;
                }
                this.loading = true;
                this.error = null;

                try {
                    const formData = new FormData();
                    formData.append('mobile', this.mobile);
                    formData.append('otp', this.otp);
                    const csrf = document.querySelector('meta[name="csrf-token"]').content;
                    formData.append('csrf_token', csrf);

                    const res = await fetch('/verify-otp', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();

                    if (data.success) {
                        window.location.reload();
                    } else {
                        this.error = data.message || 'کد تایید اشتباه است';
                    }
                } catch (e) {
                    this.error = 'خطای ارتباط با سرور';
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
