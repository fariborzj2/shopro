<?php partial('admin/header', ['title' => 'Blog Tags']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Blog Tags</h1>
            <a href="/admin/blog/tags/create" class="btn btn-primary">Create Tag</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tags as $tag) : ?>
                        <tr>
                            <td><?= $tag['name'] ?></td>
                            <td><?= $tag['slug'] ?></td>
                            <td><?= $tag['status'] ?></td>
                            <td>
                                <a href="/admin/blog/tags/edit/<?= $tag['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                <form action="/admin/blog/tags/delete/<?= $tag['id'] ?>" method="POST" style="display: inline-block;">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php partial('admin/footer') ?>
