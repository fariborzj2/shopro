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
        </div>
    </div>
</div>

<?php partial('storefront/footer') ?>
