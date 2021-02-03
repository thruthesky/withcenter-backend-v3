<?php

?>

<hr>
<h1>FORUM CATEGORIES</h1>

<form method="get">
    <input type="hidden" name="page" value="admin/forum/list.submit">
    <input type="text" name="cat_name" value="">
    <button type="submit">Create Category</button>
</form>
<?php

$categories = get_category_list();

?>

<section class="p-5">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Category ID</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col" class="text-center">View List</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($categories as $category) {
                $category->list_on_view = get_term_meta($category->cat_ID, 'list_on_view', true);
            //    print_r($category);
            ?>
                <tr>
                    <td><?php echo $category->cat_ID ?></td>
                    <td><a href="?page=admin/forum/setting&slug=<?php echo $category->slug ?>"><?php

                            if ( $category->parent ) echo "&nbsp;&nbsp;&nbsp;-- ";
                            echo $category->slug ?></a></td>
                    <td><?php
                        echo $category->name
                        ?></td>
                    <td><?php echo $category->description ?></td>
                    <td class="text-center"> <i class="fa fa-<?=$category->list_on_view ? 'check green' : 'times red'?>"></i></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>


<ul>
    <li>For the detail settings, click category ID.</li>
    <li>
        View List - is the option for listing posts under view page.
    </li>
</ul>