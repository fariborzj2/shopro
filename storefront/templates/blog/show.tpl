<?php include __DIR__ . '/../header.tpl'; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mt-4"><?= $post->title ?></h1>
            <p class="lead">by <a href="#"><?= $post->author_name ?></a></p>
            <hr>
            <p>Posted on <?= date('F j, Y, g:i a', strtotime($post->published_at)) ?></p>
            <hr>
            <img class="img-fluid rounded" src="<?= $post->image_url ?? 'https://placehold.co/800x400' ?>" alt="<?= htmlspecialchars($post->title) ?>">
            <hr>
            <?= $post->content ?>
            <hr>
            <?php if (!empty($tags)) : ?>
                <div class="tags">
                    <strong>Tags:</strong>
                    <?php foreach ($tags as $tag) : ?>
                        <a href="/blog/tags/<?= $tag['slug'] ?>" class="badge badge-primary"><?= $tag['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($faq_items)) : ?>
                <div class="faq">
                    <h2>Frequently Asked Questions</h2>
                    <div id="accordion">
                        <?php foreach ($faq_items as $faq) : ?>
                            <div class="card">
                                <div class="card-header" id="heading-<?= $faq->id ?>">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-<?= $faq->id ?>" aria-expanded="true" aria-controls="collapse-<?= $faq->id ?>">
                                            <?= $faq->question ?>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapse-<?= $faq->id ?>" class="collapse" aria-labelledby="heading-<?= $faq->id ?>" data-parent="#accordion">
                                    <div class="card-body">
                                        <?= $faq->answer ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($related_posts)) : ?>
                <div class="related-posts">
                    <h2>Related Posts</h2>
                    <div class="row">
                        <?php foreach ($related_posts as $related_post) : ?>
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="/blog/<?= $related_post->slug ?>"><?= $related_post->title ?></a></h5>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <hr>

            <div class="comments-section" x-data="comments">
                <h2>نظرات</h2>
                <div id="comment-form-container">
                    <form @submit.prevent="submitComment">
                        <input type="hidden" name="post_id" value="<?= $post->id ?>">
                        <input type="hidden" name="parent_id" x-model="parentId">
                        <div class="form-group">
                            <label for="name">نام</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">ایمیل (اختیاری)</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="comment">نظر</label>
                            <textarea name="comment" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="captcha">کپچا</label>
                            <div class="d-flex">
                                <input type="text" name="captcha" class="form-control" required>
                                <img src="<?= $captcha_image ?>" alt="Captcha" class="ml-2">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">ارسال نظر</button>
                    </form>
                </div>

                <div id="comments-list" class="mt-4">
                    <?php
                    if (!function_exists('display_comments')) {
                        function display_comments($comments, $level = 0) {
                            foreach ($comments as $comment) {
                                ?>
                                <div class="comment" style="margin-left: <?= $level * 20 ?>px">
                                    <p><strong><?= htmlspecialchars($comment['name']) ?></strong></p>
                                    <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                                    <?php if ($level < 3) : ?>
                                        <button class="btn btn-sm btn-link" @click="showReplyForm(<?= $comment['id'] ?>)">پاسخ</button>
                                    <?php endif; ?>
                                    <?php if (!empty($comment['children'])) : ?>
                                        <?php display_comments($comment['children'], $level + 1); ?>
                                    <?php endif; ?>
                                </div>
                                <?php
                            }
                        }
                    }
                    // Initialize empty comments if not set
                    $comments = $comments ?? [];
                    display_comments($comments);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function renderComment(comment, level = 0) {
        return `
            <div class="comment" style="margin-left: ${level * 20}px" data-comment-id="${comment.id}">
                <p><strong>${comment.name}</strong></p>
                <p>${comment.comment}</p>
                ${level < 3 ? `<button class="btn btn-sm btn-link" @click="showReplyForm(${comment.id})">پاسخ</button>` : ''}
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
                const commentElement = event.target.closest('.comment');
                commentElement.querySelector('.replies').prepend(formContainer);
            },
            async submitComment(event) {
                const form = event.target;
                const formData = new FormData(form);

                // Client-side validation
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const response = await fetch('/comments/store', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    const newComment = result.comment;
                    newComment.children = [];
                    const commentHTML = renderComment(newComment, this.parentId ? document.querySelector(`[data-comment-id="${this.parentId}"]`).style.marginLeft.replace('px','') / 20 + 1 : 0);

                    if (this.parentId) {
                        const parentComment = document.querySelector(`[data-comment-id="${this.parentId}"]`);
                        parentComment.querySelector('.replies').insertAdjacentHTML('beforeend', commentHTML);
                    } else {
                        document.getElementById('comments-list').insertAdjacentHTML('afterbegin', commentHTML);
                    }

                    // Reset form and move it back to its original place
                    form.reset();
                    this.parentId = null;
                    document.querySelector('.comments-section').prepend(document.getElementById('comment-form-container'));
                } else {
                    alert(result.message || 'خطایی رخ داد.');
                }
            }
        }))
    })
</script>

<?php include __DIR__ . '/../footer.tpl'; ?>
