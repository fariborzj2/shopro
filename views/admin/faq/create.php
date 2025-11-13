<?php partial('admin/header', ['title' => 'Create FAQ']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Create FAQ</h1>
            <form action="/admin/faq/store" method="POST">
                <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" name="question" id="question" class="form-control">
                </div>
                <div class="form-group">
                    <label for="answer">Answer</label>
                    <textarea name="answer" id="answer" class="form-control" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
</div>
<?php partial('admin/footer') ?>
