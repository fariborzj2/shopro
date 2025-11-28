<?php include __DIR__ . '/../header.tpl'; ?>

<main class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Main Content -->
            <div class="lg:col-span-8">

                <!-- Post Card -->
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">

                    <!-- Featured Image -->
                    <div class="relative aspect-w-16 aspect-h-9 bg-gray-100">
                        <img src="<?= $post->image_url ?? 'https://placehold.co/1200x600/EEE/31343C?text=No+Image' ?>"
                             alt="<?= htmlspecialchars($post->title) ?>"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-0 right-0 p-6 md:p-8 w-full text-white">
                            <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold leading-tight mb-4 drop-shadow-md">
                                <?= htmlspecialchars($post->title) ?>
                            </h1>
                            <div class="flex flex-wrap items-center gap-4 text-sm md:text-base font-medium text-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span><?= htmlspecialchars($post->author_name) ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span><?= \jdate('d F Y', strtotime($post->published_at)) ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <span><?= number_format($post->views_count) ?> بازدید</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 md:p-10">
                        <!-- Content -->
                        <div class="prose prose-lg max-w-none prose-indigo prose-img:rounded-xl prose-headings:font-bold prose-headings:text-gray-800">
                            <?= $post->content ?>
                        </div>

                        <!-- Tags -->
                        <?php if (!empty($tags)) : ?>
                            <div class="mt-10 pt-8 border-t border-gray-100 flex flex-wrap items-center gap-3">
                                <span class="text-gray-500 font-semibold flex items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    برچسب‌ها:
                                </span>
                                <?php foreach ($tags as $tag) : ?>
                                    <a href="/blog/tags/<?= $tag['slug'] ?>" class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-1.5 rounded-full text-sm font-medium transition-colors">
                                        <?= htmlspecialchars($tag['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>

                <!-- FAQ Section -->
                <?php if (!empty($faq_items)) : ?>
                    <section class="mb-8">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                سوالات متداول
                            </h2>
                            <div class="space-y-4" x-data="{ openItem: null }">
                                <?php foreach ($faq_items as $faq) : ?>
                                    <div class="border border-gray-200 rounded-xl overflow-hidden transition-colors hover:border-indigo-200">
                                        <button @click="openItem === <?= $faq->id ?> ? openItem = null : openItem = <?= $faq->id ?>"
                                                class="w-full flex justify-between items-center p-4 bg-gray-50/50 hover:bg-gray-50 text-right font-semibold text-gray-800 transition-colors">
                                            <span><?= htmlspecialchars($faq->question) ?></span>
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="{'rotate-180': openItem === <?= $faq->id ?>}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="openItem === <?= $faq->id ?>" x-collapse class="bg-white p-4 text-gray-600 leading-relaxed border-t border-gray-100">
                                            <?= $faq->answer ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Related Posts -->
                <?php if (!empty($related_posts)) : ?>
                    <section class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-800 border-r-4 border-indigo-500 pr-4">مطالب مرتبط</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($related_posts as $related) : ?>
                                <a href="/blog/<?= $related->slug ?>" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col h-full">
                                    <div class="aspect-w-16 aspect-h-10 bg-gray-200 overflow-hidden">
                                        <img src="<?= $related->image_url ?? 'https://placehold.co/600x400/EEE/31343C?text=Image' ?>"
                                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                             alt="<?= htmlspecialchars($related->title) ?>">
                                    </div>
                                    <div class="p-4 flex-1 flex flex-col">
                                        <h3 class="font-bold text-gray-800 group-hover:text-indigo-600 transition-colors mb-2 line-clamp-2"><?= htmlspecialchars($related->title) ?></h3>
                                        <div class="mt-auto text-sm text-gray-400 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <?= \jdate('d F Y', strtotime($related->published_at)) ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Comments Section -->
                <section id="comments-section" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8" x-data="commentSystem(<?= htmlspecialchars(json_encode($comments), ENT_QUOTES, 'UTF-8') ?>)">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            نظرات کاربران
                        </h2>
                        <span class="text-gray-500 text-sm font-medium bg-gray-100 px-3 py-1 rounded-full" x-text="totalComments + ' دیدگاه'"></span>
                    </div>

                    <!-- Comment Form -->
                    <div id="comment-form-container" class="mb-10 bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4" x-text="replyTo ? 'پاسخ به ' + replyToName : 'ارسال دیدگاه جدید'"></h3>

                        <form @submit.prevent="submitComment">
                            <input type="hidden" name="post_id" value="<?= $post->id ?>">
                            <input type="hidden" name="parent_id" x-model="parentId">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">نام *</label>
                                    <input type="text" name="name" class="form-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ایمیل (نمایش داده نمی‌شود)</label>
                                    <input type="email" name="email" class="form-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">دیدگاه شما *</label>
                                <textarea name="comment" rows="4" class="form-textarea w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow resize-none" required></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">کد امنیتی *</label>
                                <div class="flex items-center gap-3">
                                    <input type="text" name="captcha" class="form-input w-32 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow text-center tracking-widest" required>
                                    <div class="h-10 rounded-lg overflow-hidden border border-gray-300 shadow-sm">
                                        <img src="<?= $captcha_image ?>" alt="Captcha" class="h-full object-cover">
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex gap-3">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center gap-2" :disabled="loading">
                                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span x-text="loading ? 'در حال ارسال...' : 'ثبت دیدگاه'"></span>
                                    </button>
                                    <button type="button" x-show="replyTo" @click="cancelReply" class="text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg font-medium transition-colors">
                                        لغو پاسخ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-8">
                        <template x-if="comments.length === 0">
                            <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <p class="text-gray-500">اولین نفری باشید که نظر خود را ثبت می‌کند.</p>
                            </div>
                        </template>

                        <!-- Recursive Comment Rendering via x-for is tricky in Alpine.
                             So we used a flattened PHP loop previously, but here we want pure JS reactivity.
                             Ideally, we can use a template tag for recursion or just simple nested divs if depth is limited.
                             Or simply render the top level and let a component handle children.
                        -->
                        <template x-for="comment in comments" :key="comment.id">
                            <div class="comment-node">
                                <div class="flex gap-4 group" :id="'comment-' + comment.id">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg border-2 border-white shadow-sm">
                                            <span x-text="comment.name.charAt(0)"></span>
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="bg-gray-50 p-5 rounded-2xl rounded-tr-none border border-gray-100 relative group-hover:bg-white group-hover:shadow-sm transition-all">

                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-gray-900" x-text="comment.name"></span>
                                                    <span x-show="comment.status === 'pending'" class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full border border-yellow-200">در انتظار تایید</span>
                                                </div>
                                                <span class="text-xs text-gray-400" x-text="comment.created_at_persian || 'لحظاتی پیش'"></span>
                                            </div>

                                            <p class="text-gray-700 leading-relaxed text-sm md:text-base" x-text="comment.comment"></p>

                                            <div class="mt-3 flex justify-end">
                                                <button @click="reply(comment.id, comment.name)" class="text-xs font-semibold text-indigo-500 hover:text-indigo-700 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-3 h-3 transform scale-x-[-1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                    پاسخ
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Nested Replies -->
                                        <div class="pr-8 md:pr-12 pt-4 space-y-4" x-show="comment.children && comment.children.length > 0">
                                            <template x-for="child in comment.children" :key="child.id">
                                                <div class="flex gap-4" :id="'comment-' + child.id">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm border-2 border-white shadow-sm">
                                                            <span x-text="child.name.charAt(0)"></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow">
                                                        <div class="bg-gray-50/50 p-4 rounded-2xl rounded-tr-none border border-gray-100">
                                                            <div class="flex justify-between items-start mb-2">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="font-bold text-gray-800 text-sm" x-text="child.name"></span>
                                                                    <span x-show="child.status === 'pending'" class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-0.5 rounded-full border border-yellow-200">در انتظار تایید</span>
                                                                </div>
                                                                <span class="text-xs text-gray-400" x-text="child.created_at_persian || 'لحظاتی پیش'"></span>
                                                            </div>
                                                            <p class="text-gray-600 text-sm leading-relaxed" x-text="child.comment"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-4 space-y-8">
                <?php include __DIR__ . '/_sidebar.tpl'; ?>
            </aside>

        </div>
    </div>
</main>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('commentSystem', (initialComments) => ({
            comments: initialComments,
            parentId: null,
            replyTo: null,
            replyToName: '',
            loading: false,

            get totalComments() {
                let count = this.comments.length;
                this.comments.forEach(c => {
                    if (c.children) count += c.children.length;
                });
                return count;
            },

            reply(id, name) {
                this.parentId = id;
                this.replyTo = id;
                this.replyToName = name;

                // Move form to appropriate location if needed, or just scroll to top
                const formContainer = document.getElementById('comment-form-container');
                formContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Optional: Focus textarea
                formContainer.querySelector('textarea').focus();
            },

            cancelReply() {
                this.parentId = null;
                this.replyTo = null;
                this.replyToName = '';
            },

            async submitComment(event) {
                this.loading = true;
                const form = event.target;
                const formData = new FormData(form);

                // Add CSRF token manually if not in form, or ensure header is set.
                // Best practice with AJAX + CSRF rotation:
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                try {
                    const response = await fetch('/blog/comments/store', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        const newComment = result.comment;
                        // Assuming the user is using the app right now, they can see their "pending" comment.
                        // Add persian date mock for immediate feedback
                        newComment.created_at_persian = 'لحظاتی پیش';

                        if (this.parentId) {
                            // Find parent and push
                            const parent = this.comments.find(c => c.id == this.parentId);
                            if (parent) {
                                if (!parent.children) parent.children = [];
                                parent.children.push(newComment);
                            }
                        } else {
                            // Unshift to top
                            this.comments.unshift(newComment);
                        }

                        // Update CSRF Token for next request
                        if (result.new_csrf_token) {
                            document.querySelector('meta[name="csrf-token"]').setAttribute('content', result.new_csrf_token);
                        }

                        // Reset form
                        form.reset();
                        this.cancelReply();

                        // Show Success Toast
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: result.message || 'نظر شما ثبت شد.', type: 'success' }
                        }));

                    } else {
                         // Update CSRF Token even on failure if rotated
                         if (result.new_csrf_token) {
                            document.querySelector('meta[name="csrf-token"]').setAttribute('content', result.new_csrf_token);
                        }

                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: result.message || 'خطایی رخ داد.', type: 'error' }
                        }));
                    }
                } catch (e) {
                    console.error(e);
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'خطای ارتباط با سرور', type: 'error' }
                    }));
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>

<!-- JSON-LD Schema -->
<script type="application/ld+json">
<?= json_encode($schema_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<?php include __DIR__ . '/../footer.tpl'; ?>
