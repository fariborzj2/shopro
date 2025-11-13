<?php partial('admin/header', ['title' => 'Settings']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Settings</h1>
            <form action="/admin/settings" method="POST">
                <div class="form-group">
                    <label for="related_posts_limit">Number of Related Posts</label>
                    <input type="number" name="related_posts_limit" id="related_posts_limit" class="form-control" value="<?= $settings['related_posts_limit'] ?? 5 ?>">
                </div>
                <hr>
                <h4>تنظیمات اسلایدر وبلاگ</h4>
                <div class="form-group">
                    <label for="slider_posts_limit">تعداد مطالب اسلایدر</label>
                    <input type="number" name="slider_posts_limit" id="slider_posts_limit" class="form-control" value="<?= $settings['slider_posts_limit'] ?? 5 ?>">
                </div>
                <div class="form-group">
                    <label for="slider_time_range">بازه زمانی (روز)</label>
                    <input type="number" name="slider_time_range" id="slider_time_range" class="form-control" value="<?= $settings['slider_time_range'] ?? 40 ?>">
                </div>
                <hr>
                <h4>دسته‌بندی‌های منتخب</h4>
                <div class="form-group">
                    <label for="featured_categories">انتخاب دسته‌بندی‌ها</label>
                    <select name="featured_categories[]" id="featured_categories" class="form-control" multiple>
                        <?php
                        $selected_cats = json_decode($settings['featured_categories'] ?? '[]', true);
                        foreach ($blog_categories as $category) : ?>
                            <option value="<?= $category['id'] ?>" <?= in_array($category['id'], $selected_cats) ? 'selected' : '' ?>><?= $category['name_fa'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                 <div class="form-group">
                    <label for="featured_category_posts_limit">تعداد مطالب هر دسته‌بندی منتخب</label>
                    <input type="number" name="featured_category_posts_limit" id="featured_category_posts_limit" class="form-control" value="<?= $settings['featured_category_posts_limit'] ?? 3 ?>">
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
</div>
<?php partial('admin/footer') ?>
