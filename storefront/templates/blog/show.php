<?php partial('storefront/header', ['title' => $pageTitle, 'metaDescription' => $metaDescription, 'canonicalUrl' => $canonicalUrl, 'schema_data' => $schema_data]) ?>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mt-4"><?= $post->title ?></h1>
            <p class="lead">by <a href="#"><?= $post->author_name ?></a></p>
            <hr>
            <p>Posted on <?= date('F j, Y, g:i a', strtotime($post->created_at)) ?></p>
            <hr>
            <img class="img-fluid rounded" src="<?= $post->featured_image ?>" alt="">
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
                                <img src="<?= \App\Core\Captcha::generate() ?>" alt="Captcha" class="ml-2">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">ارسال نظر</button>
                    </form>
                </div>

                <div id="comments-list" class="mt-4">
                    <?php function display_comments($comments, $level = 0) { ?>
                        <?php foreach ($comments as $comment) : ?>
                            <div class="comment" style="margin-left: <?= $level * 20 ?>px">
                                <p><strong><?= $comment['name'] ?></strong></p>
                                <p><?= $comment['comment'] ?></p>
                                <?php if ($level < 3) : ?>
                                    <button class="btn btn-sm btn-link" @click="showReplyForm(<?= $comment['id'] ?>)">پاسخ</button>
                                <?php endif; ?>
                                <?php if (!empty($comment['children'])) : ?>
                                    <?php display_comments($comment['children'], $level + 1); ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php } ?>
                    <?php display_comments($comments); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('comments', () => ({
            parentId: null,
            showReplyForm(commentId) {
                this.parentId = commentId;
                const form = document.getElementById('comment-form-container');
                const commentElement = event.target.closest('.comment');
                commentElement.appendChild(form);
            },
            async submitComment(event) {
                const formData = new FormData(event.target);
                const response = await fetch('/comments/store', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    const newComment = result.comment;
                    const commentList = document.getElementById('comments-list');
                    const commentElement = document.createElement('div');
                    commentElement.classList.add('comment');
                    commentElement.innerHTML = `
                        <p><strong>${newComment.name}</strong></p>
                        <p>${newComment.comment}</p>
                    `;
                    if (this.parentId) {
                        const parentComment = document.querySelector(`[data-comment-id="${this.parentId}"]`);
                        parentComment.querySelector('.replies').appendChild(commentElement);
                    } else {
                        commentList.prepend(commentElement);
                    }
                    event.target.reset();
                    this.parentId = null;
                } else {
                    alert(result.message || 'An error occurred.');
                }
            }
        }))
    })
</script>

<?php partial('storefront/footer') ?>
