        </main>

        <!-- ===== Footer ===== -->
        <footer class="site-footer glass-panel">
            <div class="container">
                <div class="footer-grid">
                    <nav class="footer-column" aria-labelledby="footer-links-title">
                        <h4 id="footer-links-title" class="footer-heading">لینک‌های دسترسی</h4>
                        <ul>
                            <li><a href="/page/about-us" class="footer-link">درباره ما</a></li>
                            <li><a href="/page/terms" class="footer-link">قوانین و مقررات</a></li>
                            <li><a href="/page/contact-us" class="footer-link">تماس با ما</a></li>
                            <li><a href="/page/faq" class="footer-link">سوالات متداول</a></li>
                        </ul>
                    </nav>

                    <div class="footer-column">
                        <h4 class="footer-heading">ارتباط با ما</h4>
                        <address style="font-style: normal; color: var(--color-text-muted);">
                            <p style="margin-bottom: 0.5rem">تهران، خیابان آزادی، پلاک ۱۲۳</p>
                            <p>info@example.com</p>
                        </address>
                    </div>

                    <div class="footer-column">
                        <h4 class="footer-heading">شبکه‌های اجتماعی</h4>
                        <div style="display: flex; gap: 1rem;">
                            <!-- SVG Icons (Placeholders) -->
                            <a href="#" class="btn-ghost" style="padding: 0.5rem; border-radius: 50%; display: grid; place-items: center;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div style="text-center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--color-border); color: var(--color-text-muted); text-align: center;">
                    <p class="text-sm">&copy; ۱۴۰۳ - تمامی حقوق برای فروشگاه محفوظ است.</p>
                </div>
            </div>
        </footer>

    </div>

    <!-- Auth Modal (Refactored to new Design System) -->
    <div
        x-data="authModal()"
        @open-auth-modal.window="openModal()"
        x-show="isOpen"
        x-cloak
        class="modal-overlay"
        x-transition:enter="fade-enter-active"
        x-transition:enter-start="fade-enter-from"
        x-transition:enter-end="fade-enter-to"
        x-transition:leave="fade-leave-active"
        x-transition:leave-start="fade-leave-from"
        x-transition:leave-end="fade-leave-to"
    >
        <div
            @click.outside="closeModal()"
            x-show="isOpen"
            x-transition:enter="slide-up-enter-active"
            x-transition:enter-start="slide-up-enter-from"
            x-transition:enter-end="slide-up-enter-to"
            x-transition:leave="slide-up-leave-active"
            x-transition:leave-start="slide-up-leave-from"
            x-transition:leave-end="slide-up-leave-to"
            class="modal-content"
        >
            <h2 class="footer-heading" style="text-align: center; font-size: 1.5rem; margin-bottom: 2rem;" x-text="currentTitle()"></h2>

            <!-- Step 1: Enter Mobile -->
            <div x-show="step === 'mobile'">
                <form @submit.prevent="sendOtp()">
                    <div style="margin-bottom: 1.5rem;">
                        <input type="tel" x-model="mobile" placeholder="شماره موبایل (مثال: 09123456789)" class="form-input" required>
                    </div>
                    <button type="submit" :disabled="isLoading" class="btn btn-primary" style="width: 100%;">
                        <span x-show="!isLoading">ارسال کد تایید</span>
                        <span x-show="isLoading">در حال ارسال...</span>
                    </button>
                </form>
            </div>

            <!-- Step 2: Verify OTP -->
            <div x-show="step === 'otp'">
                <p style="text-align: center; margin-bottom: 1.5rem; color: var(--color-text-muted);">
                    کد ۶ رقمی ارسال شده به <span x-text="mobile" style="font-weight: bold; color: var(--color-text-main);"></span> را وارد کنید.
                </p>
                <form @submit.prevent="verifyOtp()">
                    <div style="margin-bottom: 1.5rem;">
                        <input type="text" x-model="otp" maxlength="6" placeholder="------" class="form-input" style="letter-spacing: 0.5em; font-weight: bold; font-size: 1.25rem;" required>
                    </div>
                    <button type="submit" :disabled="isLoading" class="btn btn-success" style="width: 100%; background-color: var(--color-success); color: white;">
                        <span x-show="!isLoading">تایید و ورود</span>
                        <span x-show="isLoading">در حال بررسی...</span>
                    </button>
                </form>
                <button @click="step = 'mobile'; errorMessage = ''" class="btn-ghost" style="width: 100%; margin-top: 1rem; border: none; font-size: 0.9rem;">
                    تغییر شماره موبایل
                </button>
            </div>

            <p x-show="errorMessage" x-text="errorMessage" style="color: var(--color-danger); text-align: center; margin-top: 1.5rem; font-size: 0.9rem;"></p>
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
            .catch(() => {
                this.errorMessage = 'خطای ارتباط با سرور.';
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
                    // Success feedback can be improved, but strict logic preservation means we keep the alert for now or enhance it slightly?
                    // The prompt allows visual enhancement.
                    // I'll keep the logic simple as per instructions.
                    alert('شما با موفقیت وارد شدید!');
                    this.closeModal();
                    window.location.reload();
                } else {
                    this.errorMessage = body.error || 'کد تایید نامعتبر است.';
                }
            })
            .catch(() => {
                this.errorMessage = 'خطای ارتباط با سرور.';
            })
            .finally(() => { this.isLoading = false; });
        }
    }
}
</script>
</body>
</html>
