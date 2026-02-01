<div class="max-w-4xl mx-auto" x-data="{
    activeTab: '<?php echo $_GET['tool'] ?? 'article'; ?>',
    loading: false,
    jobId: null,
    jobStatus: null,
    jobResult: null,

    // Article Generator State
    topic: '<?php echo $_GET['topic'] ?? ''; ?>',
    sourceUrl: '',
    keywords: '',

    // Calendar Generator State
    calendarTopic: '',
    calendarPeriod: 'week',

    createJob(type, payload) {
        this.loading = true;
        this.jobId = null;
        this.jobStatus = 'pending';
        this.jobResult = null;

        fetch('/admin/api/ai/jobs/create', {
            method: 'POST',
            body: JSON.stringify({ type, payload })
        })
        .then(res => res.json())
        .then(data => {
            if (data.job_id) {
                this.jobId = data.job_id;
                this.pollStatus();
            } else {
                alert('خطا در ایجاد کار: ' + (data.error || 'ناشناخته'));
                this.loading = false;
            }
        })
        .catch(err => {
            console.error(err);
            this.loading = false;
        });
    },

    pollStatus() {
        if (!this.jobId) return;

        fetch(`/admin/api/ai/jobs/status/${this.jobId}`)
        .then(res => res.json())
        .then(job => {
            this.jobStatus = job.status;
            if (job.status === 'completed') {
                this.loading = false;
                try {
                    this.jobResult = JSON.parse(job.result);
                } catch(e) {
                    this.jobResult = job.result;
                }
            } else if (job.status === 'failed') {
                this.loading = false;
                alert('خطا در پردازش هوش مصنوعی: ' + job.error_message);
            } else {
                // Poll again after 3 seconds
                setTimeout(() => this.pollStatus(), 3000);
            }
        });
    },

    copyResult() {
        const text = typeof this.jobResult === 'string' ? this.jobResult : JSON.stringify(this.jobResult, null, 2);
        navigator.clipboard.writeText(text).then(() => alert('کپی شد!'));
    }
}">

    <!-- Tab Navigation -->
    <div class="flex p-1 bg-gray-100 dark:bg-gray-800 rounded-2xl mb-8">
        <button @click="activeTab = 'article'" :class="activeTab === 'article' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
            <?php partial('icon', ['name' => 'pages', 'class' => 'w-5 h-5']); ?>
            تولید مقاله
        </button>
        <button @click="activeTab = 'calendar'" :class="activeTab === 'calendar' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
            <?php partial('icon', ['name' => 'dashboard', 'class' => 'w-5 h-5']); ?>
            تقویم محتوایی
        </button>
        <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
            <?php partial('icon', ['name' => 'search', 'class' => 'w-5 h-5']); ?>
            بهینه‌ساز سئو
        </button>
        <button @click="activeTab = 'bulk'" :class="activeTab === 'bulk' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
            <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5']); ?>
            تولید انبوه
        </button>
    </div>

    <!-- Main Content -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden min-h-[400px]">

        <!-- Loading Overlay -->
        <div x-show="loading" class="absolute inset-0 z-20 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm flex flex-col items-center justify-center p-6 text-center" style="display: none;">
            <div class="w-16 h-16 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mb-4"></div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">در حال جادوی هوش مصنوعی...</h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-xs">این فرآیند ممکن است بین ۳۰ تا ۶۰ ثانیه طول بکشد. لطفاً پنجره را نبندید.</p>
            <div class="mt-6 px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs text-gray-400 font-mono">
                Status: <span x-text="jobStatus"></span>
            </div>
        </div>

        <!-- Article Tool -->
        <div x-show="activeTab === 'article'" class="p-8 space-y-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">موضوع یا تایتل مقاله</label>
                    <input type="text" x-model="topic" placeholder="مثال: ۱۰ راه برای کاهش وزن با پیاده‌روی" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 italic text-xs">منبع محتوا (لینک - اختیاری)</label>
                        <input type="url" x-model="sourceUrl" placeholder="https://..." class="w-full px-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 italic text-xs">کلمات کلیدی (با کاما جدا کنید)</label>
                        <input type="text" x-model="keywords" placeholder="لاغری, پیاده روی, سلامت" class="w-full px-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600 outline-none">
                    </div>
                </div>
            </div>

            <button @click="createJob('generate_article', { topic, options: { source_url: sourceUrl, keywords: keywords.split(',') } })"
                    :disabled="!topic"
                    class="w-full py-4 bg-gradient-to-r from-primary-600 to-blue-500 hover:from-primary-700 hover:to-blue-600 disabled:opacity-50 text-white font-extrabold rounded-2xl shadow-lg shadow-primary-500/20 transition-all flex items-center justify-center gap-2 transform active:scale-[0.98]">
                <?php partial('icon', ['name' => 'ai', 'class' => 'w-6 h-6']); ?>
                تولید مقاله کامل
            </button>
        </div>

        <!-- Calendar Tool -->
        <div x-show="activeTab === 'calendar'" class="p-8 space-y-6" style="display: none;">
             <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">حوزه فعالیت یا نیش سایت</label>
                    <input type="text" x-model="calendarTopic" placeholder="مثال: تکنولوژی و گجت‌ها" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">بازه زمانی</label>
                    <div class="grid grid-cols-2 gap-3">
                         <label class="relative flex items-center justify-center p-3 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <input type="radio" x-model="calendarPeriod" value="week" class="sr-only">
                            <span :class="calendarPeriod === 'week' ? 'text-primary-600 font-bold' : 'text-gray-500'" class="text-sm">هفتگی (۷ پست)</span>
                            <div x-show="calendarPeriod === 'week'" class="absolute -top-1 -right-1 w-3 h-3 bg-primary-600 rounded-full border-2 border-white dark:border-gray-800"></div>
                        </label>
                        <label class="relative flex items-center justify-center p-3 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <input type="radio" x-model="calendarPeriod" value="month" class="sr-only">
                            <span :class="calendarPeriod === 'month' ? 'text-primary-600 font-bold' : 'text-gray-500'" class="text-sm">ماهانه (۳۰ پست)</span>
                            <div x-show="calendarPeriod === 'month'" class="absolute -top-1 -right-1 w-3 h-3 bg-primary-600 rounded-full border-2 border-white dark:border-gray-800"></div>
                        </label>
                    </div>
                </div>
            </div>

            <button @click="createJob('generate_calendar', { period: calendarPeriod, topic: calendarTopic })"
                    :disabled="!calendarTopic"
                    class="w-full py-4 bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-extrabold rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
                <?php partial('icon', ['name' => 'dashboard', 'class' => 'w-6 h-6']); ?>
                ساخت تقویم محتوایی هوشمند
            </button>
        </div>

        <!-- SEO Tool -->
        <div x-show="activeTab === 'seo'" class="p-8 space-y-6" style="display: none;">
             <div class="space-y-4">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">متن محتوا جهت آنالیز و تولید متا</label>
                <textarea rows="8" placeholder="متن مقاله خود را اینجا کپی کنید..." class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all" id="seoContentInput"></textarea>
            </div>

            <button @click="createJob('generate_meta', { content: document.getElementById('seoContentInput').value })"
                    class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-extrabold rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
                <?php partial('icon', ['name' => 'search', 'class' => 'w-6 h-6']); ?>
                تولید تایتل و دیسکریپشن بهینه
            </button>
        </div>

        <!-- Bulk Tool -->
        <div x-show="activeTab === 'bulk'" class="p-8 space-y-6" style="display: none;" x-data="{ bulkTopics: '' }">
             <div class="space-y-4">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">لیست موضوعات (هر خط یک موضوع)</label>
                <textarea x-model="bulkTopics" rows="8" placeholder="مثلاً:&#10;آموزش سئو سایت&#10;چگونه لاغر شویم؟&#10;بهترین زبان‌های برنامه نویسی" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all"></textarea>
                <p class="text-xs text-gray-500">برای هر خط یک مقاله در صف انتظار ایجاد خواهد شد.</p>
            </div>

            <button @click="createBulkJobs(bulkTopics)"
                    :disabled="!bulkTopics.trim()"
                    class="w-full py-4 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 text-white font-extrabold rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
                <?php partial('icon', ['name' => 'plus', 'class' => 'w-6 h-6']); ?>
                افزودن موضوعات به صف تولید
            </button>
        </div>

        <!-- Result View -->
        <div x-show="jobResult" class="p-8 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30" style="display: none;">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    نتیجه تولید شده:
                </h4>
                <div class="flex gap-2">
                     <button @click="copyResult()" class="p-2 text-gray-500 hover:text-primary-600 transition-colors" title="کپی کردن">
                        <?php partial('icon', ['name' => 'dashboard', 'class' => 'w-5 h-5']); ?>
                    </button>
                    <button @click="jobResult = null" class="p-2 text-gray-500 hover:text-red-600 transition-colors">
                        <?php partial('icon', ['name' => 'close', 'class' => 'w-5 h-5']); ?>
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm max-h-[500px] overflow-y-auto">
                <template x-if="typeof jobResult === 'string'">
                    <div class="prose dark:prose-invert max-w-none" x-html="jobResult"></div>
                </template>
                <template x-if="typeof jobResult === 'object' && jobResult !== null">
                    <div class="space-y-4">
                        <template x-for="(val, key) in jobResult" :key="key">
                            <div class="p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                <span class="text-xs font-bold text-gray-400 uppercase block mb-1" x-text="key"></span>
                                <div class="text-gray-800 dark:text-gray-200" x-text="typeof val === 'object' ? JSON.stringify(val) : val"></div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <template x-if="activeTab === 'calendar'">
                <div class="mt-4 flex justify-center">
                    <a href="/admin/ai-content-pro/calendar" class="text-sm text-primary-600 font-bold hover:underline">مشاهده در تقویم محتوایی →</a>
                </div>
            </template>
        </div>

    </div>
</div>
