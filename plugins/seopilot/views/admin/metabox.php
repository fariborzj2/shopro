<?php
// plugins/seopilot/views/admin/metabox.php

// This partial is intended to be included in Product/Post edit forms.
// It requires Alpine.js to be loaded in the main layout.

// Expected variables:
// $metaData (array|null): Existing meta data for the entity
// $defaultTitle (string): Fallback title (e.g. product name)
// $defaultDesc (string): Fallback description

// Prepare data: Decode entities recursively to ensure &zwnj; becomes visible character
// and prevent double encoding in the JSON output.
$cleanMeta = $metaData ?? [];
array_walk_recursive($cleanMeta, function(&$item) {
    if (is_string($item)) {
        $item = html_entity_decode($item, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
});
?>

<div class="bg-white rounded-2xl shadow-card p-6 mt-6" x-data="seoPilotMeta(<?= htmlspecialchars(json_encode($cleanMeta, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($defaultTitle ?? '', ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($defaultDesc ?? '', ENT_QUOTES, 'UTF-8') ?>')">
    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-4">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="text-primary">🚀</span>
            SeoPilot Enterprise
        </h3>
        <div class="flex items-center gap-2">
            <span class="text-sm text-slate-500">امتیاز سئو:</span>
            <div class="h-2 w-24 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-full bg-red-500 transition-all duration-500" :class="scoreColor" :style="'width: ' + score + '%'"></div>
            </div>
            <span class="text-sm font-bold" x-text="score + '/100'"></span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form Inputs -->
        <div class="space-y-4">

            <!-- Focus Keyword -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">کلمه کلیدی تمرکزی</label>
                <input type="text" name="seopilot_focus_keyword" x-model="focusKeyword" @input="analyze()" class="form-input w-full rounded-xl border-slate-300" placeholder="مثلاً: خرید گوشی آیفون">
            </div>

            <!-- SEO Title -->
            <div>
                <div class="flex justify-between mb-1">
                    <label class="block text-sm font-medium text-slate-700">عنوان سئو (Title)</label>
                    <span class="text-xs" :class="titleWidth > 580 ? 'text-red-500' : 'text-slate-500'">
                        <span x-text="titleWidth"></span>px / 580px
                    </span>
                </div>
                <input type="text" name="seopilot_title" x-model="title" @input="analyze()" class="form-input w-full rounded-xl border-slate-300" :placeholder="defaultTitle">
                <div class="mt-1 h-1 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500" :style="'width: ' + Math.min((titleWidth / 580) * 100, 100) + '%'"></div>
                </div>
            </div>

            <!-- Meta Description -->
            <div>
                <div class="flex justify-between mb-1">
                    <label class="block text-sm font-medium text-slate-700">توضیحات متا (Description)</label>
                    <span class="text-xs text-slate-500">
                        <span x-text="descLength"></span> / 160 کاراکتر
                    </span>
                </div>
                <textarea name="seopilot_description" x-model="description" @input="analyze()" rows="4" class="form-input w-full rounded-xl border-slate-300" :placeholder="defaultDesc"></textarea>
            </div>

            <!-- Canonical URL -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Canonical URL (اختیاری)</label>
                <input type="text" name="seopilot_canonical" x-model="canonical" class="form-input w-full rounded-xl border-slate-300 ltr text-left">
            </div>

        </div>

        <!-- Live Preview -->
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
            <h4 class="text-xs font-bold text-slate-400 uppercase mb-3">پیش‌نمایش در گوگل</h4>

            <!-- Desktop Result -->
            <div class="mb-6 font-sans">
                <div class="flex items-center gap-2 mb-1">
                    <div class="bg-slate-200 rounded-full w-7 h-7 flex items-center justify-center text-xs">Fav</div>
                    <div class="flex flex-col">
                        <span class="text-xs text-slate-800">example.com</span>
                        <span class="text-[10px] text-slate-500">https://example.com/product/...</span>
                    </div>
                </div>
                <h3 class="text-xl text-[#1a0dab] hover:underline cursor-pointer truncate" x-text="title || defaultTitle"></h3>
                <p class="text-sm text-[#4d5156] mt-1 line-clamp-2" x-text="description || defaultDesc"></p>
            </div>

            <!-- Mobile Result -->
            <div class="border-t border-slate-200 pt-4">
                 <h4 class="text-xs font-bold text-slate-400 uppercase mb-3">نمای موبایل</h4>
                 <div class="max-w-[320px]">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="bg-slate-200 rounded-full w-6 h-6"></div>
                        <span class="text-xs text-slate-800 font-medium">example.com</span>
                    </div>
                    <h3 class="text-lg text-[#1a0dab] leading-snug mb-1" x-text="title || defaultTitle"></h3>
                    <p class="text-sm text-[#4d5156]" x-text="description || defaultDesc"></p>
                 </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('seoPilotMeta', (initialData, defaultTitle, defaultDesc) => ({
            focusKeyword: initialData.focus_keyword || '',
            title: initialData.title || '',
            description: initialData.description || '',
            canonical: initialData.canonical || '',
            defaultTitle: defaultTitle,
            defaultDesc: defaultDesc,

            // Metrics
            titleWidth: 0,
            descLength: 0,
            score: 0,

            init() {
                this.analyze();
            },

            analyze() {
                // Calculate Pixel Width (Approx)
                this.titleWidth = this.calculatePixelWidth(this.title || this.defaultTitle);
                this.descLength = (this.description || this.defaultDesc).length;

                // Calculate Score
                let s = 50; // Base score
                const keyword = this.focusKeyword.trim();
                const currentTitle = this.title || this.defaultTitle;
                const currentDesc = this.description || this.defaultDesc;

                if (keyword) {
                    if (currentTitle.includes(keyword)) s += 20;
                    if (currentDesc.includes(keyword)) s += 10;
                }

                if (this.titleWidth >= 200 && this.titleWidth <= 580) s += 10;
                if (this.descLength >= 120 && this.descLength <= 160) s += 10;

                this.score = Math.min(s, 100);
            },

            get scoreColor() {
                if (this.score < 50) return 'bg-red-500';
                if (this.score < 80) return 'bg-yellow-500';
                return 'bg-green-500';
            },

            calculatePixelWidth(text) {
                // Simplified pixel calculation for Arial 16px
                // Persian characters are approx 9px, English 8px average
                let width = 0;
                for (let i = 0; i < text.length; i++) {
                    const code = text.charCodeAt(i);
                    // Persian range
                    if (code >= 0x0600 && code <= 0x06FF) {
                        width += 9;
                    } else if (text[i] === ' ') {
                        width += 4;
                    } else if (text[i] === text[i].toUpperCase() && text[i] !== text[i].toLowerCase()) {
                        width += 13; // Capital letters
                    } else {
                        width += 8;
                    }
                }
                return width;
            }
        }));
    });
</script>
