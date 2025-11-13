<?php partial('admin/header', ['title' => 'Comment Management']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Comment Management</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Post</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment) : ?>
                        <tr>
                            <td><?= $comment['post_title'] ?></td>
                            <td><?= $comment['name'] ?></td>
                            <td><?= $comment['status'] ?></td>
                            <td>
                                <a href="/admin/comments/edit/<?= $comment['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                <form action="/admin/comments/delete/<?= $comment['id'] ?>" method="POST" style="display: inline-block;">
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
