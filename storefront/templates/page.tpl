<?php include 'header.tpl'; ?>

<div class="main-content-wrapper">
    <div class="section pd-td-40">
        <div class="center">
            <div class="max-w800 m-auto bg-white pd-40 radius-20 shadow-sm content-body">
                <h1 class="title-size-2 font-bold mb-20 text-center"><?php echo htmlspecialchars($page->title ?? 'صفحه'); ?></h1>

                <div class="text-justify leading-loose">
                    <?php if (isset($page->content)): ?>
                        <?php echo $page->content; ?>
                    <?php else: ?>
                        <p>محتوای این صفحه یافت نشد.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .content-body p { margin-bottom: 1rem; line-height: 1.8; }
    .content-body h2 { font-size: 1.5rem; font-weight: bold; margin-top: 2rem; margin-bottom: 1rem; }
    .content-body ul { list-style-type: disc; padding-right: 1.5rem; margin-bottom: 1rem; }
    .shadow-sm { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
    .leading-loose { line-height: 2; }
</style>

<?php include 'footer.tpl'; ?>