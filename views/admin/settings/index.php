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
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
</div>
<?php partial('admin/footer') ?>
