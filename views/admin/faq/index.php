<?php partial('admin/header', ['title' => 'FAQ Management']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>FAQ Management</h1>
            <a href="/admin/faq/create" class="btn btn-primary">Create FAQ</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faq_items as $faq_item) : ?>
                        <tr>
                            <td><?= $faq_item['question'] ?></td>
                            <td><?= $faq_item['status'] ?></td>
                            <td>
                                <a href="/admin/faq/edit/<?= $faq_item['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                <form action="/admin/faq/delete/<?= $faq_item['id'] ?>" method="POST" style="display: inline-block;">
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
