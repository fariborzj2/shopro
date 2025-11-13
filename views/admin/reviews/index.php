<?php partial('admin/header', ['title' => 'Review Management']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Review Management</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review) : ?>
                        <tr>
                            <td><?= $review['product_name'] ?></td>
                            <td><?= $review['name'] ?></td>
                            <td><?= $review['rating'] ?></td>
                            <td><?= $review['status'] ?></td>
                            <td>
                                <a href="/admin/reviews/edit/<?= $review['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                <form action="/admin/reviews/delete/<?= $review['id'] ?>" method="POST" style="display: inline-block;">
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
