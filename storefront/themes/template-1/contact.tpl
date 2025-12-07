<?php include 'header.tpl'; ?>

<main class="flex-grow bg-gray-50 py-12 md:py-20 relative overflow-hidden"
    x-data="contactForm()"
>
    <!-- Background Decor (Optional, consistent with theme glass effect) -->
    <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-primary-50 to-gray-50 -z-10"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-100 rounded-full blur-3xl opacity-50 -z-10"></div>
    <div class="absolute top-48 -left-24 w-72 h-72 bg-blue-100 rounded-full blur-3xl opacity-50 -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Hero Section -->
        <div class="text-center mb-16 max-w-2xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 tracking-tight leading-tight">
                با ما در <span class="text-primary-600 relative inline-block">
                    ارتباط باشید
                    <svg class="absolute -bottom-2 left-0 w-full h-3 text-primary-200 -z-10" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" /></svg>
                </span>
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed">
                سوالات، پیشنهادات و نظرات خود را با ما در میان بگذارید. تیم پشتیبانی ما در سریع‌ترین زمان ممکن پاسخگوی شما خواهد بود.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">

            <!-- Left Column: Contact Form -->
            <div class="glass-panel p-8 md:p-10 order-2 lg:order-1 transition-all duration-300 hover:shadow-2xl hover:shadow-primary-500/10">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary-100 text-primary-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </span>
                    ارسال پیام
                </h2>

                <form @submit.prevent="submitForm">
                    <div class="space-y-6">
                        <!-- Name & Email Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="form-label">نام و نام خانوادگی <span class="text-red-500">*</span></label>
                                <input type="text" id="name" x-model="formData.name"
                                    class="form-input transition-all duration-200 focus:ring-4 focus:ring-primary-100"
                                    :class="{'border-red-300 focus:border-red-500 focus:ring-red-100': errors.name}"
                                    placeholder="مثال: علی رضایی">
                                <p x-show="errors.name" x-text="errors.name" class="text-xs text-red-500 mt-1 font-medium"></p>
                            </div>
                            <div>
                                <label for="email" class="form-label">ایمیل <span class="text-red-500">*</span></label>
                                <input type="email" id="email" x-model="formData.email"
                                    class="form-input transition-all duration-200 focus:ring-4 focus:ring-primary-100 text-left" dir="ltr"
                                    :class="{'border-red-300 focus:border-red-500 focus:ring-red-100': errors.email}"
                                    placeholder="example@domain.com">
                                <p x-show="errors.email" x-text="errors.email" class="text-xs text-red-500 mt-1 font-medium text-right"></p>
                            </div>
                        </div>

                        <!-- Phone & Subject Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="form-label">شماره موبایل <span class="text-red-500">*</span></label>
                                <input type="tel" id="phone" x-model="formData.phone"
                                    class="form-input transition-all duration-200 focus:ring-4 focus:ring-primary-100 text-left tracking-wider" dir="ltr"
                                    :class="{'border-red-300 focus:border-red-500 focus:ring-red-100': errors.phone}"
                                    placeholder="09123456789">
                                <p x-show="errors.phone" x-text="errors.phone" class="text-xs text-red-500 mt-1 font-medium text-right"></p>
                            </div>
                            <div>
                                <label for="subject" class="form-label">موضوع پیام <span class="text-red-500">*</span></label>
                                <select id="subject" x-model="formData.subject"
                                    class="form-input transition-all duration-200 focus:ring-4 focus:ring-primary-100"
                                    :class="{'border-red-300 focus:border-red-500 focus:ring-red-100': errors.subject}">
                                    <option value="" disabled selected>انتخاب کنید...</option>
                                    <option value="support">پشتیبانی محصول</option>
                                    <option value="sales">مشاوره خرید</option>
                                    <option value="cooperation">پیشنهاد همکاری</option>
                                    <option value="complaint">انتقادات و شکایات</option>
                                    <option value="other">سایر موارد</option>
                                </select>
                                <p x-show="errors.subject" x-text="errors.subject" class="text-xs text-red-500 mt-1 font-medium"></p>
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="form-label">متن پیام <span class="text-red-500">*</span></label>
                            <textarea id="message" x-model="formData.message" rows="5"
                                class="form-input transition-all duration-200 focus:ring-4 focus:ring-primary-100 resize-none"
                                :class="{'border-red-300 focus:border-red-500 focus:ring-red-100': errors.message}"
                                placeholder="پیام خود را اینجا بنویسید..."></textarea>
                            <p x-show="errors.message" x-text="errors.message" class="text-xs text-red-500 mt-1 font-medium"></p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full btn btn-primary py-3.5 text-base shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 transform hover:-translate-y-1"
                                :disabled="loading"
                                :class="{'opacity-75 cursor-not-allowed': loading}">
                                <span x-show="!loading" class="flex items-center gap-2">
                                    ارسال پیام
                                    <svg class="w-5 h-5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                </span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="animate-spin -ml-1 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    در حال ارسال...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Info & Map -->
            <div class="space-y-8 order-1 lg:order-2">

                <!-- Info Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                    <!-- Address -->
                    <div class="glass-panel p-6 flex items-start gap-4 hover:border-primary-200 transition-colors">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 mb-1">آدرس ما</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                <?php echo htmlspecialchars($settings['footer_address'] ?? 'تهران، خیابان آزادی، پلاک ۱۲۳، ساختمان مدرن'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="glass-panel p-6 flex items-start gap-4 hover:border-primary-200 transition-colors">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 mb-1">تلفن تماس</h3>
                            <p class="text-sm text-gray-500 font-mono font-bold" dir="ltr">
                                <?php echo htmlspecialchars($settings['contact_phone'] ?? '021-12345678'); ?>
                            </p>
                            <p class="text-xs text-gray-400 mt-1">پاسخگویی: شنبه تا چهارشنبه ۹ تا ۱۷</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="glass-panel p-6 flex items-start gap-4 hover:border-primary-200 transition-colors">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 mb-1">پست الکترونیک</h3>
                            <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?>" class="text-sm text-gray-500 hover:text-primary-600 transition-colors block">
                                <?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Map Placeholder -->
                <div class="glass-panel p-2 h-64 lg:h-80 relative overflow-hidden group">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d207371.9779532187!2d51.2097346853767!3d35.69701178657688!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f8e00491ff3dcd9%3A0xf0b3697c567024bc!2sTehran%2C%20Tehran%20Province%2C%20Iran!5e0!3m2!1sen!2s!4v1717600000000!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-xl grayscale group-hover:grayscale-0 transition-all duration-700"></iframe>
                    <div class="absolute inset-0 pointer-events-none rounded-xl border border-black/5"></div>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
    function contactForm() {
        return {
            formData: {
                name: '',
                email: '',
                phone: '',
                subject: '',
                message: ''
            },
            errors: {},
            loading: false,

            validate() {
                this.errors = {};

                if (!this.formData.name.trim()) this.errors.name = 'نام و نام خانوادگی الزامی است.';

                if (!this.formData.email.trim()) {
                    this.errors.email = 'ایمیل الزامی است.';
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email)) {
                    this.errors.email = 'فرمت ایمیل صحیح نیست.';
                }

                if (!this.formData.phone.trim()) {
                    this.errors.phone = 'شماره موبایل الزامی است.';
                } else if (!/^09[0-9]{9}$/.test(this.formData.phone)) {
                    this.errors.phone = 'شماره موبایل باید ۱۱ رقم و با 09 شروع شود.';
                }

                if (!this.formData.subject) this.errors.subject = 'لطفا یک موضوع انتخاب کنید.';

                if (!this.formData.message.trim()) this.errors.message = 'متن پیام الزامی است.';

                return Object.keys(this.errors).length === 0;
            },

            submitForm() {
                if (!this.validate()) return;

                this.loading = true;

                fetch('/contact-us/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.formData)
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200 && body.success) {
                        // Success
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: {
                                message: body.message,
                                type: 'success'
                            }
                        }));

                        // Reset Form
                        this.formData = { name: '', email: '', phone: '', subject: '', message: '' };

                        // Update CSRF
                        if (body.new_csrf_token) {
                            document.querySelector('meta[name="csrf-token"]').setAttribute('content', body.new_csrf_token);
                        }

                    } else {
                        // Error
                        if (body.errors) {
                            this.errors = body.errors;
                        } else {
                            window.dispatchEvent(new CustomEvent('show-toast', {
                                detail: {
                                    message: body.message || 'خطایی رخ داده است.',
                                    type: 'error'
                                }
                            }));
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            message: 'خطای ارتباط با سرور.',
                            type: 'error'
                        }
                    }));
                })
                .finally(() => {
                    this.loading = false;
                });
            }
        }
    }
</script>

<?php include 'footer.tpl'; ?>
