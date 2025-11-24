<?php include __DIR__ . '/../header.tpl'; ?>

<style>
    /* Typography & Layout */
    .blog-post-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .post-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900;
        line-height: 1.2;
        color: var(--color-text-main);
        margin-bottom: 1.5rem;
    }
    .post-meta {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        color: var(--color-text-muted);
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }
    .post-image {
        width: 100%;
        border-radius: var(--radius-lg);
        margin-bottom: 3rem;
        box-shadow: var(--shadow-lg);
    }
    .post-content {
        font-size: 1.15rem;
        line-height: 2;
        color: #334155;
        margin-bottom: 3rem;
    }
    .post-content h2 { font-size: 1.8rem; font-weight: 800; margin-top: 3rem; margin-bottom: 1rem; color: var(--color-text-main); }
    .post-content p { margin-bottom: 1.5rem; text-align: justify; }

    /* Tags */
    .tags-container { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 3rem; }
    .badge-tag {
        background: rgba(59, 130, 246, 0.1);
        color: var(--color-primary);
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: var(--transition-smooth);
    }
    .badge-tag:hover { background: var(--color-primary); color: white; }

    /* Comments */
    .comments-section {
        background: white;
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        box-shadow: var(--shadow-glass);
        margin-top: 4rem;
        border: 1px solid var(--color-border);
    }

    .comment {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .comment-author { font-weight: 700; color: var(--color-text-main); margin-bottom: 0.5rem; }
    .comment-body { color: #475569; line-height: 1.6; }

    /* FAQ Accordion (Reusable) */
    .accordion-card { border: 1px solid var(--color-border); border-radius: var(--radius-md); margin-bottom: 0.5rem; overflow: hidden; }
    .accordion-btn { width: 100%; text-align: right; padding: 1rem; background: #f8fafc; font-weight: 600; }
    .accordion-body { padding: 1rem; background: white; border-top: 1px solid var(--color-border); }
</style>

<div class="container" style="padding-block: 4rem;">

    <article class="max-w-4xl mx-auto">
        <!-- Header -->
        <header class="blog-post-header">
            <h1 class="post-title"><?= htmlspecialchars($post->title) ?></h1>
            <div class="post-meta">
                <span>نویسنده: <?= htmlspecialchars($post->author_name) ?></span>
                <span>•</span>
                <span><?= date('Y/m/d', strtotime($post->published_at)) ?></span>
            </div>
            <img class="post-image" src="<?= $post->image_url ?? 'https://placehold.co/1200x600/EEE/31343C?text=No+Image' ?>" alt="<?= htmlspecialchars($post->title) ?>">
        </header>

        <!-- Content -->
        <div class="post-content prose">
            <?= $post->content ?>
        </div>

        <!-- Tags -->
        <?php if (!empty($tags)) : ?>
            <div class="tags-container">
                <span style="font-weight: 700; align-self: center;">برچسب‌ها:</span>
                <?php foreach ($tags as $tag) : ?>
                    <a href="/blog/tags/<?= $tag['slug'] ?>" class="badge-tag"><?= htmlspecialchars($tag['name']) ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- FAQ Section -->
        <?php if (!empty($faq_items)) : ?>
            <section style="margin-bottom: 4rem;">
                <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 1.5rem;">سوالات متداول</h2>
                <div x-data="{ openItem: null }">
                    <?php foreach ($faq_items as $faq) : ?>
                        <div class="accordion-card">
                            <button @click="openItem === <?= $faq->id ?> ? openItem = null : openItem = <?= $faq->id ?>" class="accordion-btn">
                                <?= htmlspecialchars($faq->question) ?>
                            </button>
                            <div x-show="openItem === <?= $faq->id ?>" style="display: none;" x-transition class="accordion-body">
                                <?= $faq->answer ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Related Posts -->
        <?php if (!empty($related_posts)) : ?>
            <section style="margin-bottom: 4rem; padding-top: 2rem; border-top: 1px solid var(--color-border);">
                <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 1.5rem;">مطالب مرتبط</h2>
                <div class="product-grid" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
                    <?php foreach ($related_posts as $related_post) : ?>
                        <a href="/blog/<?= $related_post->slug ?>" class="glass-panel" style="padding: 1.5rem; display: block; border-radius: var(--radius-md);">
                            <h3 style="font-weight: 700; font-size: 1.1rem; color: var(--color-text-main);"><?= htmlspecialchars($related_post->title) ?></h3>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Comments -->
        <section class="comments-section" x-data="comments">
            <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 2rem;">نظرات کاربران</h2>

            <div id="comment-form-container" style="margin-bottom: 3rem;">
                <form @submit.prevent="submitComment" class="glass-panel" style="padding: 2rem; background: #f9fafb;">
                    <input type="hidden" name="post_id" value="<?= $post->id ?>">
                    <input type="hidden" name="parent_id" x-model="parentId">

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">نام *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">ایمیل</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">متن نظر *</label>
                        <textarea name="comment" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; gap: 1rem;">
                        <input type="text" name="captcha" class="form-control" placeholder="کد امنیتی" style="max-width: 150px;" required>
                        <img src="<?= $captcha_image ?>" alt="Captcha" style="border-radius: var(--radius-md);">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <button type="submit" class="btn btn-primary">ارسال دیدگاه</button>
                        <button type="button" x-show="parentId" @click="parentId = null; $el.closest('form').reset(); document.querySelector('.comments-section').prepend(document.getElementById('comment-form-container'))" class="btn btn-ghost text-sm">لغو پاسخ</button>
                    </div>
                </form>
            </div>

            <div id="comments-list">
                <?php
                if (!function_exists('display_comments')) {
                    function display_comments($comments, $level = 0) {
                        foreach ($comments as $comment) {
                            $margin = min($level * 30, 90);
                            ?>
                            <div class="comment" style="margin-right: <?= $margin ?>px" data-comment-id="<?= $comment['id'] ?>">
                                <div class="comment-author"><?= htmlspecialchars($comment['name']) ?></div>
                                <div class="comment-body"><?= nl2br(htmlspecialchars($comment['comment'])) ?></div>
                                <?php if ($level < 3) : ?>
                                    <div style="text-align: left; margin-top: 1rem;">
                                        <button class="btn btn-ghost" style="padding: 0.25rem 0.75rem; font-size: 0.85rem;" @click="showReplyForm(<?= $comment['id'] ?>)">پاسخ</button>
                                    </div>
                                <?php endif; ?>
                                <div class="replies">
                                    <?php if (!empty($comment['children'])) : ?>
                                        <?php display_comments($comment['children'], $level + 1); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                $comments = $comments ?? [];
                display_comments($comments);
                ?>
            </div>
        </section>

    </article>
</div>

<!-- JS Logic for Comments -->
<script>
    function renderComment(comment, level = 0) {
        const margin = Math.min(level * 30, 90);
        return `
            <div class="comment" style="margin-right: ${margin}px" data-comment-id="${comment.id}">
                <div class="comment-author">${comment.name}</div>
                <div class="comment-body">${comment.comment}</div>
                ${level < 3 ? `<div style="text-align: left; margin-top: 1rem;"><button class="btn btn-ghost" style="padding: 0.25rem 0.75rem; font-size: 0.85rem;" @click="showReplyForm(${comment.id})">پاسخ</button></div>` : ''}
                <div class="replies">
                    ${comment.children ? comment.children.map(child => renderComment(child, level + 1)).join('') : ''}
                </div>
            </div>
        `;
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('comments', () => ({
            parentId: null,
            showReplyForm(commentId) {
                this.parentId = commentId;
                const formContainer = document.getElementById('comment-form-container');
                const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                // Insert form after the comment body, before replies
                commentElement.insertBefore(formContainer, commentElement.querySelector('.replies'));
                formContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            },
            async submitComment(event) {
                const form = event.target;
                const formData = new FormData(form);

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                try {
                    const response = await fetch('/comments/store', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (result.success) {
                        const newComment = result.comment;
                        newComment.children = [];

                        let level = 0;
                        if (this.parentId) {
                             // Calculate level based on parent's margin roughly or just increment
                             // For simplicity in JS dynamic render, we can assume level + 1 logic
                             // But finding the level in DOM is tricky without data attributes.
                             // We will just approximate or use 1 for replies.
                             // Actually, let's just append to parent's replies container.
                             // We don't need to recalculate margin in JS template string if we append to nested container,
                             // UNLESS the PHP recursive function used margin-right.
                             // The PHP function uses margin-right on the div itself.
                             // So nested divs don't need extra margin if they are inside the parent div?
                             // No, the PHP logic puts them inside 'replies' div.
                             // BUT it sets margin based on level parameter.
                             // So yes, we need level.

                             // Hack: Check parent's margin
                             const parentEl = document.querySelector(`[data-comment-id="${this.parentId}"]`);
                             const parentMargin = parseInt(parentEl.style.marginRight || 0);
                             level = (parentMargin / 30) + 1;
                        }

                        const commentHTML = renderComment(newComment, level);

                        if (this.parentId) {
                            const parentComment = document.querySelector(`[data-comment-id="${this.parentId}"]`);
                            parentComment.querySelector('.replies').insertAdjacentHTML('beforeend', commentHTML);
                        } else {
                            document.getElementById('comments-list').insertAdjacentHTML('afterbegin', commentHTML);
                        }

                        form.reset();
                        this.parentId = null;
                        document.querySelector('.comments-section').prepend(document.getElementById('comment-form-container'));
                        alert('نظر شما با موفقیت ثبت شد.');
                    } else {
                        alert(result.message || 'خطایی رخ داد.');
                    }
                } catch (e) {
                    alert('خطای ارتباط با سرور.');
                }
            }
        }))
    })
</script>

<!-- JSON-LD Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "<?= htmlspecialchars($post->title) ?>",
    "image": "<?= $post->image_url ?? '' ?>",
    "editor": "<?= htmlspecialchars($post->author_name) ?>",
    "keywords": "<?= implode(' ', array_column($tags, 'name')) ?>",
    "publisher": "فروشگاه مدرن",
    "url": "<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/blog/<?= $post->slug ?>",
    "datePublished": "<?= $post->published_at ?>",
    "dateCreated": "<?= $post->created_at ?>",
    "dateModified": "<?= $post->updated_at ?>",
    "description": "<?= htmlspecialchars($post->excerpt) ?>",
    "articleBody": "<?= strip_tags($post->content) ?>"
}
</script>

<?php include __DIR__ . '/../footer.tpl'; ?>
