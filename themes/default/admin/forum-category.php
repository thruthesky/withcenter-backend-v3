<?php

$cat = get_category_by_slug(in('slug'));

d($cat);

?>
<h1><?=in('slug')?> Settings</h1>


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
        <td><input name="name" value="<?=$cat->name?>" @keyup="debounce(updateName, 500, 'name')"></td>
    </tr>
    <tr>
        <td>Post list under view page</td>
        <td><input type="checkbox"></td>
    </tr>
    <tr>
        <td>No of posts per page</td>
        <td><input type="text" @keyup="debounce(updateNoPosts, 500, 'no')"></td>
    </tr>
    <tr>
        <td>No of pages on navigator</td>
        <td><input type="text"></td>
    </tr>
    </tbody>
</table>

<script>






    const mixin = {

        created() {

        },
        methods: {
            updateName() {
                console.log("update category name(title)")
            },
            updateNoPosts() {
                console.log("update no posts...");
            }
        }
    }
</script>