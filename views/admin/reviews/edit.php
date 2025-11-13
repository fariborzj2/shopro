<?php partial('admin/header', ['title' => 'Edit Review']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Review</h1>
            <form action="/admin/reviews/update/<?= $review['id'] ?>" method="POST">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="pending" <?= $review['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= $review['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= $review['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="admin_reply">Admin Reply</label>
                    <textarea name="admin_reply" id="admin_reply" class="form-control" rows="5"><?= $review['admin_reply'] ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
<?php partial('admin/footer') ?>
