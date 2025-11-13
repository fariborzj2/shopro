<?php partial('admin/header', ['title' => 'Edit Blog Post']) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Blog Post</h1>
            <form action="/admin/blog/posts/update/<?= $post['id'] ?>" method="POST">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= $post['title'] ?>">
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control" value="<?= $post['slug'] ?>">
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['id'] ?>" <?= $post['category_id'] == $category['id'] ? 'selected' : '' ?>><?= $category['name_fa'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="author_id">Author</label>
                    <select name="author_id" id="author_id" class="form-control">
                        <?php foreach ($authors as $author) : ?>
                            <option value="<?= $author['id'] ?>" <?= $post['author_id'] == $author['id'] ? 'selected' : '' ?>><?= $author['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" class="form-control" rows="10"><?= $post['content'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" class="form-control" rows="3"><?= $post['excerpt'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="draft" <?= $post['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $post['status'] == 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" id="tag-input" class="form-control" placeholder="Type to search for tags">
                    <div id="tag-suggestions" class="list-group"></div>
                    <div id="selected-tags"></div>
                    <input type="hidden" name="tags" id="tags-hidden">
                </div>
                <div class="form-group">
                    <label for="faq_items">FAQ Items</label>
                    <select name="faq_items[]" id="faq_items" class="form-control" multiple>
                        <?php foreach ($all_faq_items as $faq_item) : ?>
                            <option value="<?= $faq_item['id'] ?>" <?= in_array($faq_item['id'], $post_faq_items) ? 'selected' : '' ?>><?= $faq_item['question'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_editors_pick" name="is_editors_pick" value="1" <?= ($post['is_editors_pick'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_editors_pick">پیشنهاد سردبیر</label>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>

<script>
    const tagInput = document.getElementById('tag-input');
    const tagSuggestions = document.getElementById('tag-suggestions');
    const selectedTagsContainer = document.getElementById('selected-tags');
    const tagsHiddenInput = document.getElementById('tags-hidden');
    const allTags = <?= json_encode($tags) ?>;
    let selectedTags = <?= json_encode(array_map(function($tagId) use ($tags) {
        foreach ($tags as $tag) {
            if ($tag['id'] == $tagId) {
                return $tag;
            }
        }
    }, $post_tags)) ?>;

    renderSelectedTags();

    tagInput.addEventListener('keyup', () => {
        const inputText = tagInput.value.toLowerCase();
        tagSuggestions.innerHTML = '';
        if (inputText.length >= 3) {
            const filteredTags = allTags.filter(tag => tag.name.toLowerCase().includes(inputText));
            filteredTags.forEach(tag => {
                const suggestion = document.createElement('a');
                suggestion.href = '#';
                suggestion.classList.add('list-group-item', 'list-group-item-action');
                suggestion.textContent = tag.name;
                suggestion.addEventListener('click', () => {
                    addTag(tag);
                    tagInput.value = '';
                    tagSuggestions.innerHTML = '';
                });
                tagSuggestions.appendChild(suggestion);
            });
        }
    });

    function addTag(tag) {
        if (!selectedTags.find(t => t.id === tag.id)) {
            selectedTags.push(tag);
            renderSelectedTags();
        }
    }

    function removeTag(tagId) {
        selectedTags = selectedTags.filter(t => t.id !== tagId);
        renderSelectedTags();
    }

    function renderSelectedTags() {
        selectedTagsContainer.innerHTML = '';
        selectedTags.forEach(tag => {
            const tagElement = document.createElement('span');
            tagElement.classList.add('badge', 'badge-primary', 'mr-1');
            tagElement.textContent = tag.name;
            const removeButton = document.createElement('span');
            removeButton.classList.add('ml-1', 'text-danger');
            removeButton.style.cursor = 'pointer';
            removeButton.innerHTML = '&times;';
            removeButton.addEventListener('click', () => removeTag(tag.id));
            tagElement.appendChild(removeButton);
            selectedTagsContainer.appendChild(tagElement);
        });
        tagsHiddenInput.value = JSON.stringify(selectedTags.map(t => t.id));
    }
</script>

<?php partial('admin/footer') ?>
