<?php include __DIR__ . '/../header.tpl'; ?>

<div class="main-content-wrapper">
    <!-- Breadcrumb-like Header -->
    <div class="section pd-td-40 bg-purple-50">
        <div class="center">
            <div class="max-w800 m-auto text-center">
                <div class="color-bright mb-10">
                    <a href="/" class="purple-gray">خانه</a> /
                    <a href="/blog" class="purple-gray">وبلاگ</a> /
                    <span class="gr-text"><?php echo htmlspecialchars($post->title); ?></span>
                </div>
                <h1 class="title-size-2 font-bold mb-20"><?php echo htmlspecialchars($post->title); ?></h1>

                <div class="d-flex align-center just-center color-bright font-size-0-9 mb-20">
                    <div class="ml-15">
                        <i class="icon-calendar-2"></i>
                        <time><?php echo jdate('d F Y', strtotime($post->published_at ?? $post->created_at)); ?></time>
                    </div>
                    <?php if (!empty($post->author_name)): ?>
                    <div class="ml-15">
                        <i class="icon-user-bold"></i>
                        <span><?php echo htmlspecialchars($post->author_name); ?></span>
                    </div>
                    <?php endif; ?>
                    <div>
                        <i class="icon-eye"></i>
                        <span><?php echo number_format($post->views_count ?? 0); ?> بازدید</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="section pd-d-40">
        <div class="center">
            <div class="max-w900 m-auto">
                <!-- Featured Image -->
                <?php if (!empty($post->image_url)): ?>
                <div class="radius-20 overhide mb-40 shadow-lg">
                    <img src="<?php echo htmlspecialchars($post->image_url); ?>" alt="<?php echo htmlspecialchars($post->title); ?>" class="w-full object-cover" style="max-height: 500px; width: 100%;">
                </div>
                <?php endif; ?>

                <!-- Content Body -->
                <div class="bg-white pd-40 radius-20 content-body">
                    <?php echo $post->content; ?>
                </div>

                <!-- Tags -->
                <?php if (!empty($tags)): ?>
                <div class="mt-40 d-flex align-center flex-wrap">
                    <span class="ml-10 font-bold purple-gray">برچسب‌ها:</span>
                    <?php foreach ($tags as $tag): ?>
                        <a href="/blog/tags/<?php echo $tag->slug; ?>" class="btn-tag border m-5 purple-gray hover-bg-purple hover-white transition">
                            #<?php echo htmlspecialchars($tag->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- FAQs -->
                <?php if (!empty($faqs)): ?>
                <div class="mt-60">
                    <h2 class="title-size-3 mb-20">سوالات متداول</h2>
                    <div class="toggle-slide-box faq-list">
                        <?php foreach ($faqs as $index => $faq): ?>
                        <div class="border radius-20 bg-one m-10">
                            <div class="pd-20 d-flex just-between pointer toggle-slide">
                                <div class="d-flex align-center fix-mr10">
                                    <div class="pd-lr-10 ml-10 border-left font-size-2 width-50 min-w50 gr-text"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></div>
                                    <h3 class="font-size-1-2"><?php echo htmlspecialchars($faq->question); ?></h3>
                                </div>
                                <i class="icon-arrow-down-1"></i>
                            </div>
                            <div class="border-t slide-down">
                                <div class="pd-20 text-justify">
                                    <p><?php echo nl2br(htmlspecialchars($faq->answer)); ?></p>
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

    <!-- Related Posts -->
    <?php if (!empty($related_posts)): ?>
    <div class="section pd-td-40 bg-light-purple">
        <div class="center">
            <div class="text-center mb-40">
                <h2 class="title-size-3 purple-gray">مطالب مرتبط</h2>
            </div>

            <div class="blog-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($related_posts as $related): ?>
                    <div class="swiper-slide">
                         <div class="overhide radius-20 opc-slide bg-white h-full flex flex-col">
                            <a href="/blog/<?php echo $related->slug; ?>" class="img block aspect-video w-full overflow-hidden">
                                <img src="<?php echo htmlspecialchars($related->image_url ?? '/template-2/images/blog/default.png'); ?>" class="w-full h-full object-cover" alt="<?php echo htmlspecialchars($related->title); ?>">
                            </a>
                            <div class="pd-20 flex-1 flex flex-col">
                                <h2 class="ellipsis-y line-clamp-2 font-size-1-2 mb-2">
                                    <a href="/blog/<?php echo $related->slug; ?>" class="color-text hover-gr-text transition">
                                        <?php echo htmlspecialchars($related->title); ?>
                                    </a>
                                </h2>
                                <div class="d-flex align-center color-bright nowrap overhide mt-auto pt-4 font-size-0-9">
                                    <div class="ml-5">
                                        <i class="icon-calendar-2"></i>
                                        <time><?php echo jdate('d F Y', strtotime($related->created_at)); ?></time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex just-center mt-20">
                    <div class="btn border m-5 blog-next"><i class="icon-arrow-right-1 ml-5"></i></div>
                    <div class="btn border m-5 blog-prev"><i class="icon-arrow-left mr-5"></i></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Comment Section -->
    <div class="section pd-d-40">
        <div class="center">
            <div class="max-w800 m-auto bg-white pd-40 radius-20 border">
                 <!-- Since backend logic for comments might differ, we implement a simple placeholder or the actual form if variables exist -->
                 <h3 class="font-size-1-5 mb-20">دیدگاه‌ها</h3>

                 <!-- Comments List -->
                 <div class="comments-list mb-40">
                     <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item border-b pb-20 mb-20">
                                <div class="d-flex align-center mb-10">
                                    <div class="font-bold ml-10"><?php echo htmlspecialchars($comment->name); ?></div>
                                    <div class="font-size-0-9 color-bright"><?php echo jdate('d F Y', strtotime($comment->created_at)); ?></div>
                                </div>
                                <p class="text-justify"><?php echo nl2br(htmlspecialchars($comment->content)); ?></p>
                                <?php if (!empty($comment->reply)): ?>
                                    <div class="reply bg-gray-50 pd-15 radius-10 mt-10 mr-20 border-r-2 border-primary">
                                        <div class="font-bold font-size-0-9 mb-5 text-primary">پاسخ مدیر:</div>
                                        <p><?php echo nl2br(htmlspecialchars($comment->reply)); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                     <?php else: ?>
                        <p class="text-center color-bright">اولین نفری باشید که نظر می‌دهید.</p>
                     <?php endif; ?>
                 </div>

                 <!-- Comment Form -->
                 <div class="comment-form">
                     <h4 class="mb-20">ارسال دیدگاه</h4>
                     <form action="/blog/comments" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
                         <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
                         <?php echo csrf_field(); ?>

                         <div class="d-flex-wrap" style="margin: -5px;">
                            <?php if (!isset($_SESSION['user_id'])): ?>
                             <div class="grow-1 basis200 pd-5">
                                 <input type="text" name="name" class="input border w-full pd-10 radius-10" placeholder="نام شما" required>
                             </div>
                             <div class="grow-1 basis200 pd-5">
                                 <input type="email" name="email" class="input border w-full pd-10 radius-10" placeholder="ایمیل شما" required>
                             </div>
                             <?php endif; ?>
                         </div>
                         <div class="mt-10">
                             <textarea name="content" class="input border w-full pd-10 radius-10" rows="5" placeholder="نظر خود را بنویسید..." required></textarea>
                         </div>
                         <div class="mt-20 text-left">
                             <button type="submit" class="btn bg-gr text-white pd-lr-30" :disabled="submitting">
                                 <span x-show="!submitting">ارسال نظر</span>
                                 <span x-show="submitting">در حال ارسال...</span>
                             </button>
                         </div>
                     </form>
                 </div>
            </div>
        </div>
    </div>

</div>

<style>
    .content-body p { margin-bottom: 1rem; line-height: 1.8; text-align: justify; }
    .content-body h2 { font-size: 1.5rem; font-weight: bold; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937; }
    .content-body h3 { font-size: 1.25rem; font-weight: bold; margin-top: 1.5rem; margin-bottom: 1rem; }
    .content-body ul { list-style-type: disc; padding-right: 1.5rem; margin-bottom: 1rem; }
    .content-body img { border-radius: 1rem; margin: 1.5rem 0; max-width: 100%; height: auto; }
    .btn-tag { padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; display: inline-block; }
    .hover-bg-purple:hover { background-color: #8b5cf6; border-color: #8b5cf6; }
    .hover-white:hover { color: #fff !important; }
    .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
</style>

<?php include __DIR__ . '/../footer.tpl'; ?>