<?php include 'header.tpl'; ?>

<main class="flex-grow py-12 md:py-20 transition-colors duration-300" x-data="contactForm()">
    <div class="container mx-auto">

        <!-- Hero Section -->
        <div class="text-center mb-16 max-w-2xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-4 tracking-tight">
                ارتباط با <span class="text-primary-600">ما</span>
            </h1>
            <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
                سوالات، پیشنهادات و نظرات خود را با ما در میان بگذارید. تیم پشتیبانی ما در سریع‌ترین زمان ممکن پاسخگوی شما خواهد بود.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            <!-- Left Column: Contact Form (7 cols) -->
            <div class="lg:col-span-7 order-2 lg:order-1">
                <div class="card p-8 md:p-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                        <span class="flex items-center justify-center w-12 h-12 rounded-2xl bg-primary-50 dark:bg-primary-900/20 text-primary-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </span>
                        ارسال پیام
                    </h2>

                    <form @submit.prevent="submitForm">
                        <div class="space-y-6">
                            <!-- Name & Email Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">نام و نام خانوادگی <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" x-model="formData.name"
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500 transition-colors py-3 px-4"
                                        :class="{'border-red-300 dark:border-red-500 focus:border-red-500 focus:ring-red-200': errors.name}"
                                        placeholder="مثال: علی رضایی">
                                    <p x-show="errors.name" x-text="errors.name" class="text-xs text-red-500 dark:text-red-400 mt-1.5 font-medium"></p>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ایمیل <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" x-model="formData.email"
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500 transition-colors py-3 px-4 text-left" dir="ltr"
                                        :class="{'border-red-300 dark:border-red-500 focus:border-red-500 focus:ring-red-200': errors.email}"
                                        placeholder="example@domain.com">
                                    <p x-show="errors.email" x-text="errors.email" class="text-xs text-red-500 dark:text-red-400 mt-1.5 font-medium text-right"></p>
                                </div>
                            </div>

                            <!-- Phone & Subject Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">شماره موبایل <span class="text-red-500">*</span></label>
                                    <input type="tel" id="phone" x-model="formData.phone"
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500 transition-colors py-3 px-4 text-left tracking-wider" dir="ltr"
                                        :class="{'border-red-300 dark:border-red-500 focus:border-red-500 focus:ring-red-200': errors.phone}"
                                        placeholder="09123456789">
                                    <p x-show="errors.phone" x-text="errors.phone" class="text-xs text-red-500 dark:text-red-400 mt-1.5 font-medium text-right"></p>
                                </div>
                                <div>
                                    <label for="subject" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">موضوع پیام <span class="text-red-500">*</span></label>
                                    <select id="subject" x-model="formData.subject"
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500 transition-colors py-3 px-4"
                                        :class="{'border-red-300 dark:border-red-500 focus:border-red-500 focus:ring-red-200': errors.subject}">
                                        <option value="" disabled selected>انتخاب کنید...</option>
                                        <option value="support">پشتیبانی محصول</option>
                                        <option value="sales">مشاوره خرید</option>
                                        <option value="cooperation">پیشنهاد همکاری</option>
                                        <option value="complaint">انتقادات و شکایات</option>
                                        <option value="other">سایر موارد</option>
                                    </select>
                                    <p x-show="errors.subject" x-text="errors.subject" class="text-xs text-red-500 dark:text-red-400 mt-1.5 font-medium"></p>
                                </div>
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">متن پیام <span class="text-red-500">*</span></label>
                                <textarea id="message" x-model="formData.message" rows="6"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500 transition-colors py-3 px-4 resize-none"
                                    :class="{'border-red-300 dark:border-red-500 focus:border-red-500 focus:ring-red-200': errors.message}"
                                    placeholder="پیام خود را اینجا بنویسید..."></textarea>
                                <p x-show="errors.message" x-text="errors.message" class="text-xs text-red-500 dark:text-red-400 mt-1.5 font-medium"></p>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full btn btn-primary py-3.5 text-base shadow-lg shadow-primary-500/20 hover:shadow-primary-500/40"
                                    :disabled="loading"
                                    :class="{'opacity-75 cursor-not-allowed': loading}">
                                    <span x-show="!loading" class="flex items-center justify-center gap-2">
                                        ارسال پیام
                                        <svg class="w-5 h-5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                    </span>
                                    <span x-show="loading" class="flex items-center justify-center gap-2">
                                        <svg class="animate-spin -ml-1 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        در حال ارسال...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Info & Map (5 cols) -->
            <div class="lg:col-span-5 space-y-6 order-1 lg:order-2">

                <!-- Info Cards -->
                <div class="card p-6 md:p-8">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">اطلاعات تماس</h3>
                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">آدرس ما</span>
                                <p class="text-sm font-medium text-gray-900 dark:text-white leading-relaxed">
                                    <?php echo htmlspecialchars($settings['footer_address'] ?? 'تهران، خیابان آزادی، پلاک ۱۲۳، ساختمان مدرن'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">تلفن تماس</span>
                                <p class="text-lg font-bold text-gray-900 dark:text-white tracking-widest" dir="ltr">
                                    <?php echo htmlspecialchars($settings['contact_phone'] ?? '021-12345678'); ?>
                                </p>
                                <p class="text-xs text-gray-400 mt-1">پاسخگویی: شنبه تا چهارشنبه ۹ تا ۱۷</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">پست الکترونیک</span>
                                <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?>" class="text-sm font-medium text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors block">
                                    <?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Placeholder -->
                <div class="card p-2 h-72 relative overflow-hidden group">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d207371.9779532187!2d51.2097346853767!3d35.69701178657688!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f8e00491ff3dcd9%3A0xf0b3697c567024bc!2sTehran%2C%20Tehran%20Province%2C%20Iran!5e0!3m2!1sen!2s!4v1717600000000!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-lg grayscale group-hover:grayscale-0 transition-all duration-700 dark:invert-[.9] dark:group-hover:invert-0"></iframe>
                    <div class="absolute inset-0 pointer-events-none rounded-lg border border-black/5 dark:border-white/5"></div>
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
