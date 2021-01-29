<?php

// $cat = get_category_settings(['slug' => in('slug')]);

$cat = get_category_by_slug(in('slug'));
$catMetas = get_term_meta($cat->term_id);

// d($cat);
?>
<h1><?= in('slug') ?> Settings</h1>


<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Options</th>
            <th scope="col">Values</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Title</td>
            <td>
                <input name="name" value="<?= $cat->name ?>" @keyup="debounce(updateTitle($event), 500, 'name')">
            </td>
        </tr>
        <tr>
            <td>Post list under view page</td>
            <td>
                <input 
                    type="checkbox" 
                    name="list_on_view" 
                    @change="debounce(updateListOnView($event), 500, 'list')" 
                    <?php if ($catMetas['list_on_view'][0]) echo 'checked' ?>>
            </td>
        </tr>
        <tr>
            <td>No of posts per page</td>
            <td>
                <input 
                    type="number" 
                    value="<?= $catMetas['posts_per_page'][0] ?? 20 ?>" 
                    @keyup="debounce(updatePostsPerPage($event), 500, 'no')">
            </td>
        </tr>
        <tr>
            <td>No of pages on navigator</td>
            <td>
                <input 
                    type="number" 
                    value="<?= $catMetas['no_of_pages_on_nav'][0] ?? 5 ?>" 
                    @keyup="debounce(updateNoOfPagesOnNav($event), 500, 'pages')">
            </td>
        </tr>
    </tbody>
</table>

<ul>
    <li>
        Post list under view page - is enabled if the box is checked.
    </li>
</ul>

<script>
    const mixin = {

        created() {

        },
        methods: {
            updateTitle(event) {
                this.updateCategory('cat_name', event.target.value, function(data) {
                    alert('Title updated to : ' + event.target.value)
                });
            },
            updateListOnView(event) {
                this.updateCategory('list_on_view', event.target.checked, function(data) {
                    alert('Listing on view page updated to : ' + event.target.checked)
                });
            },
            updatePostsPerPage(event) {
                this.updateCategory('posts_per_page', event.target.value, function(data) {
                    alert('Post per page updated to : ' + event.target.value)
                });
            },
            updateNoOfPagesOnNav(event) {
                this.updateCategory('no_of_pages_on_nav', event.target.value, function(data) {
                    alert('Number of pagers on navigation updated to : ' + event.target.value)
                });
            },
            updateCategory(key, value, successCallback) {
                data = {
                    'cat_ID': <?= $cat->term_id ?>,
                    'name': key,
                    'value': value
                };
                console.log(data);
                request('forum.updateCategory', data, successCallback, app.error);
            }
        }
    }
</script>