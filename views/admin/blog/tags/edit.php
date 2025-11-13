<?php partial('admin/header', ['title' => 'Edit Blog Tag']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Blog Tag</h1>
            <form action="/admin/blog/tags/update/<?= $tag['id'] ?>" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= $tag['name'] ?>">
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control" value="<?= $tag['slug'] ?>">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="active" <?= $tag['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $tag['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
<?php partial('admin/footer') ?>
