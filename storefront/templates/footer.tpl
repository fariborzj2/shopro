
        </main>

        <!-- ===== Footer ===== -->
        <footer class="bg-gray-100 rounded-2xl mt-20">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800">لینک‌های مهم</h4>
                        <ul class="space-y-2">
                            <li><a href="/page/about-us" class="text-gray-600 hover:text-gray-900">درباره ما</a></li>
                            <li><a href="/page/terms" class="text-gray-600 hover:text-gray-900">قوانین و مقررات</a></li>
                            <li><a href="/page/contact-us" class="text-gray-600 hover:text-gray-900">تماس با ما</a></li>
                            <li><a href="/page/faq" class="text-gray-600 hover:text-gray-900">سوالات متداول</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800">اطلاعات تماس</h4>
                        <p class="text-gray-600">تهران، خیابان آزادی، پلاک ۱۲۳</p>
                        <p class="text-gray-600">info@example.com</p>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800">شبکه‌های اجتماعی</h4>
                        <div class="flex space-x-4 space-x-reverse">
                           <a href="#">...</a> <!-- SVG Icons would go here -->
                        </div>
                    </div>
                </div>
                <div class="mt-12 border-t border-gray-200 pt-8 text-center">
                    <p class="text-base text-gray-500">&copy; ۱۴۰۳ - تمامی حقوق محفوظ است.</p>
                </div>
            </div>
        </footer>

    </div>

    <!-- Auth Modal -->
    <div
        x-data="authModal()"
        @open-auth-modal.window="openModal()"
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center"
    >
        <div
            @click.outside="closeModal()"
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-lg shadow-xl w-full max-w-sm p-8"
        >
            <h2 class="text-2xl font-bold text-center mb-6" x-text="currentTitle()"></h2>

            <!-- Step 1: Enter Mobile -->
            <div x-show="step === 'mobile'">
                <form @submit.prevent="sendOtp()">
                    <input type="tel" x-model="mobile" placeholder="شماره موبایل (مثال: 09123456789)" class="w-full border-gray-300 rounded-md text-center">
                    <button type="submit" :disabled="isLoading" class="w-full bg-blue-600 text-white py-2 rounded-md mt-4">
                        <span x-show="!isLoading">ارسال کد تایید</span>
                        <span x-show="isLoading">در حال ارسال...</span>
                    </button>
                </form>
            </div>

            <!-- Step 2: Verify OTP -->
            <div x-show="step === 'otp'">
                <p class="text-center text-sm text-gray-600 mb-4">کد ۶ رقمی ارسال شده به <span x-text="mobile"></span> را وارد کنید.</p>
                <form @submit.prevent="verifyOtp()">
                    <input type="text" x-model="otp" maxlength="6" placeholder="------" class="w-full border-gray-300 rounded-md text-center tracking-[1em]">
                    <button type="submit" :disabled="isLoading" class="w-full bg-green-600 text-white py-2 rounded-md mt-4">
                        <span x-show="!isLoading">تایید و ورود</span>
                        <span x-show="isLoading">در حال بررسی...</span>
                    </button>
                </form>
                <button @click="step = 'mobile'; errorMessage = ''" class="text-sm text-blue-600 mt-4 text-center w-full">تغییر شماره موبایل</button>
            </div>

            <p x-show="errorMessage" x-text="errorMessage" class="text-red-500 text-sm text-center mt-4"></p>
        </div>
    </div>

<script>
function authModal() {
    return {
        isOpen: false,
        step: 'mobile', // 'mobile' or 'otp'
        mobile: '',
        otp: '',
        isLoading: false,
        errorMessage: '',

        openModal() { this.isOpen = true; },
        closeModal() { this.isOpen = false; this.reset(); },
        reset() {
            this.step = 'mobile';
            this.mobile = '';
            this.otp = '';
            this.isLoading = false;
            this.errorMessage = '';
        },
        currentTitle() {
            return this.step === 'mobile' ? 'ورود یا ثبت‌نام' : 'کد تایید را وارد کنید';
        },

        sendOtp() {
            this.isLoading = true;
            this.errorMessage = '';
            fetch('/api/auth/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ mobile: this.mobile })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200) {
                    this.step = 'otp';
                    // Update the CSRF token with the new one from the server
                    if (body.new_csrf_token) {
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', body.new_csrf_token);
                    }
                } else {
                    this.errorMessage = body.error || 'خطایی رخ داده است.';
                }
            })
            .finally(() => { this.isLoading = false; });
        },

        verifyOtp() {
            this.isLoading = true;
            this.errorMessage = '';
            fetch('/api/auth/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ mobile: this.mobile, otp: this.otp })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200) {
                    alert('شما با موفقیت وارد شدید!');
                    this.closeModal();
                    window.location.reload(); // Reload to update user state
                } else {
                    this.errorMessage = body.error || 'کد تایید نامعتبر است.';
                }
            })
            .finally(() => { this.isLoading = false; });
        }
    }
}
</script>
</body>
</html>
