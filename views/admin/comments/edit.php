<?php partial('admin/header', ['title' => 'Edit Comment']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Comment</h1>
            <form action="/admin/comments/update/<?= $comment['id'] ?>" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= $comment['name'] ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= $comment['email'] ?>">
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea name="comment" id="comment" class="form-control" rows="5"><?= $comment['comment'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="pending" <?= $comment['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= $comment['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= $comment['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
<?php partial('admin/footer') ?>
