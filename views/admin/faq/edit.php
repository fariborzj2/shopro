<?php partial('admin/header', ['title' => 'Edit FAQ']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit FAQ</h1>
            <form action="/admin/faq/update/<?= $faq_item['id'] ?>" method="POST">
                <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" name="question" id="question" class="form-control" value="<?= $faq_item['question'] ?>">
                </div>
                <div class="form-group">
                    <label for="answer">Answer</label>
                    <textarea name="answer" id="answer" class="form-control" rows="5"><?= $faq_item['answer'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="active" <?= $faq_item['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $faq_item['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
<?php partial('admin/footer') ?>
