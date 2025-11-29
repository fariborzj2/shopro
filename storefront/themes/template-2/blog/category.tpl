<?php include __DIR__ . '/../header.tpl'; ?>

<div class="main-content-wrapper">
    <!-- Hero -->
    <div class="section pd-td-40 hero">
        <div class="center">
            <div class="hero-info text-center max-w600 m-auto relative">
                <div class="star-amin float-star"></div>
                <div class="color-bright"><span class="icon-category gr-text font-size-1-5"></span> دسته‌بندی</div>
                <h1 class="title-size-1">مطالب <span class="gr-text"><?php echo htmlspecialchars($category->name ?? 'دسته‌بندی'); ?></span></h1>
            </div>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="section pd-d-40">
        <div class="center">
            <?php if (empty($posts)): ?>
                <div class="text-center py-5">
                    <p class="font-size-1-5 purple-gray">هیچ مطلبی در این دسته‌بندی یافت نشد.</p>
                    <a href="/blog" class="btn border mt-20">بازگشت به وبلاگ</a>
                </div>
            <?php else: ?>
                <div class="d-flex-wrap" style="margin: -15px;">
                    <?php foreach ($posts as $post): ?>
                        <div class="basis300 grow-1 pd-15">
                            <div class="bg-white radius-20 overhide shadow-sm hover-up transition">
                                <a href="/blog/<?php echo $post->slug; ?>" class="d-block relative aspect-video overflow-hidden">
                                    <img
                                        src="<?php echo htmlspecialchars($post->image_url ?? '/template-2/images/blog/default.png'); ?>"
                                        alt="<?php echo htmlspecialchars($post->title); ?>"
                                        class="w-full h-full object-cover"
                                    >
                                </a>
                                <div class="pd-20">
                                    <div class="d-flex align-center color-bright font-size-0-9 mb-10">
                                        <div class="ml-10">
                                            <i class="icon-calendar-2"></i>
                                            <time><?php echo jdate('d F Y', strtotime($post->created_at)); ?></time>
                                        </div>
                                    </div>
                                    <h2 class="font-size-1-2 mb-10 ellipsis-y line-clamp-2" style="min-height: 3rem;">
                                        <a href="/blog/<?php echo $post->slug; ?>" class="color-text font-bold hover-gr-text">
                                            <?php echo htmlspecialchars($post->title); ?>
                                        </a>
                                    </h2>
                                    <p class="color-bright font-size-1 ellipsis-y line-clamp-3 mb-20 text-justify" style="min-height: 4.5rem;">
                                        <?php echo htmlspecialchars($post->excerpt); ?>
                                    </p>
                                    <div class="d-flex just-between align-center border-t pt-15">
                                        <a href="/blog/<?php echo $post->slug; ?>" class="btn-sm border purple-gray hover-bg-purple hover-white transition">
                                            ادامه مطلب <i class="icon-arrow-left font-size-0-8 mr-5"></i>
                                        </a>
                                        <div class="color-bright font-size-0-9">
                                            <i class="icon-eye"></i> <?php echo number_format($post->views_count ?? 0); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if (isset($paginator) && $paginator->total_pages > 1): ?>
                    <div class="d-flex just-center mt-40">
                        <div class="pagination d-flex align-center">
                            <?php if ($paginator->current_page > 1): ?>
                                <a href="<?php echo $paginator->getPrevUrl(); ?>" class="btn border m-5"><i class="icon-arrow-right-1"></i></a>
                            <?php endif; ?>

                            <span class="m-5 font-bold purple-gray">صفحه <?php echo $paginator->current_page; ?> از <?php echo $paginator->total_pages; ?></span>

                            <?php if ($paginator->current_page < $paginator->total_pages): ?>
                                <a href="<?php echo $paginator->getNextUrl(); ?>" class="btn border m-5"><i class="icon-arrow-left"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .hover-up:hover { transform: translateY(-5px); }
    .transition { transition: all 0.3s ease; }
    .aspect-video { aspect-ratio: 16/9; }
    .w-full { width: 100%; }
    .h-full { height: 100%; }
    .object-cover { object-fit: cover; }
    .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .btn-sm { padding: 5px 15px; border-radius: 10px; font-size: 0.9rem; }
</style>

<?php include __DIR__ . '/../footer.tpl'; ?>