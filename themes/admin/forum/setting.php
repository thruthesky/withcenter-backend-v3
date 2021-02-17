<?php

$cat = get_category_by_slug(in('slug'));

$categories = get_category_tree();



?>
<h1><?= in('slug') ?> 설정</h1>


<form @submit.prevent="onForumSettingFormSubmit($event)">
    <input type="hidden" name="cat_ID" value="<?=$cat->cat_ID?>">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">옵션</th>
            <th scope="col">설정</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td><?=ln('Parent Category', '부모 게시판')?></td>
            <td>
                <select name="category_parent">
                    <option value="0">None</option>
                    <? foreach( $categories as $_category ) {
                        if ( $_category->term_id == $cat->term_id ) continue;
                        ?>
                        <option value="<?=$_category->term_id?>" <? if ($_category->term_id == $cat->category_parent) echo "selected"?>>
                            <?=str_repeat('-', $_category->depth )?><?=$_category->name?>
                        </option>
                    <? } ?>
                </select>
            </td>
        </tr>


        <tr>
            <td><?=ln('Title', '게시판 이름')?></td>
            <td>
                <input
                        name="cat_name"
                        value="<?= $cat->cat_name ?>">
            </td>
        </tr>

        <tr>
            <td><?=ln('Description', '설명')?></td>
            <td>
                <input
                        name="category_description"
                        value="<?= $cat->category_description ?>">
            </td>
        </tr>

        <tr class="table-dark">
            <td colspan="2">포인트 설정</td>
        </tr>
        <tr class="table-light">
            <td colspan="2">
                <div class="hint">
                    포인트 설정에서 삭제 포인트는 음수 값만 입력 할 수 있습니다.
                </div>
            </td>
        </tr>

        <tr>
            <td><?=ln('Point', '글 쓰기 포인트')?></td>
            <td>
                <input type="number" name="post_create_point" value="<?=category_meta($cat->cat_ID, 'post_create_point','0')?>">
            </td>
        </tr>
        <tr>
            <td><?=ln('Point', '글 삭제 포인트')?></td>
            <td>
                <input type="number" name="post_delete_point" value="<?=category_meta($cat->cat_ID, 'post_delete_point','0')?>">
            </td>
        </tr>

        <tr>
            <td><?=ln('Point', '코멘트 쓰기 포인트')?></td>
            <td>
                <input type="number" name="comment_create_point" value="<?=category_meta($cat->cat_ID, 'comment_create_point','0')?>">
            </td>
        </tr>
        <tr>
            <td><?=ln('Point', '코멘트 삭제 포인트')?></td>
            <td>
                <input type="number" name="comment_delete_point" value="<?=category_meta($cat->cat_ID, 'comment_delete_point','0')?>">
            </td>
        </tr>


        <tr>
            <td><?=ln('Point', '시간/수 제한')?></td>
            <td>
                <input class="w-64px" type="number" name="POINT_HOUR_LIMIT" value="<?=category_meta($cat->cat_ID, POINT_HOUR_LIMIT, '0')?>">
                /
                <input class="w-64px" type="number" name="POINT_HOUR_COUNT_LIMIT" value="<?=category_meta($cat->cat_ID, POINT_HOUR_COUNT_LIMIT, '0')?>">
            </td>
        </tr>
        <tr>
            <td><?=ln('Point', '일/수 제한')?></td>
            <td>
                <input class="w-64px" type="number" name="POINT_DAILY_LIMIT" value="<?=category_meta($cat->cat_ID, POINT_DAILY_LIMIT, '0')?>">
            </td>
        </tr>




        <tr class="table-dark">
            <td colspan="2">위젯 설정</td>
        </tr>

        <tr>
            <td><?=ln('Post Edit Widget', '글 수정 위젯')?></td>
            <td>
                <?
                select_list_widgets($cat->term_id, 'forum-edit', 'forum_edit_widget');
                ?>
            </td>
        </tr>




        <tr>
            <td><?=ln('Post View Widget', '글 읽기 위젯')?></td>
            <td>
                <?
                select_list_widgets($cat->term_id, 'forum-view', 'forum_view_widget');
                ?>
            </td>
        </tr>




        <tr>
            <td><?=ln('Forum List Header', '글 목록 헤더 위젯')?></td>
            <td>
                <?
                select_list_widgets($cat->term_id, 'forum-list-header', 'forum_list_header_widget');
                ?>
            </td>
        </tr>



        <tr>
            <td><?=ln('Forum List Widget', '글 목록 위젯')?></td>
            <td>
                <?
                select_list_widgets($cat->term_id, 'forum-list', 'forum_list_widget');
                ?>
            </td>
        </tr>


        <tr>
            <td><?=ln('Forum List Pagination Widget', '네비게이션 위젯')?></td>
            <td>
                <?
                select_list_widgets($cat->term_id, 'pagination', 'pagination_widget');
                ?>
            </td>
        </tr>






        <tr>
            <td><?=ln('Post list under view page', '글 읽기 아래 목록')?></td>
            <td>
                <label>
                    <input
                            type="radio"
                            name="list_on_view"
                            value="Y"
                        <?php if (category_meta($cat->cat_ID, 'list_on_view', '') == 'Y' ) echo 'checked' ?>> Yes,
                </label>
                &nbsp;
                <label>
                    <input
                            type="radio"
                            name="list_on_view"
                            value="N"
                        <?php if (category_meta($cat->cat_ID, 'list_on_view', '') != 'Y' ) echo 'checked' ?>> No
                </label>
            </td>
        </tr>
        <tr>
            <td><?=ln('No of posts per page', '페이지 글 수')?></td>
            <td>
                <input
                        name="posts_per_page"
                        type="text"
                        value="<?=category_meta($cat->cat_ID, 'posts_per_page', POSTS_PER_PAGE)?>">
            </td>
        </tr>
        <tr>
            <td nowrap><?=ln('No of pages on navigator', '네이게이션 페이지 수')?></td>
            <td>
                <input
                        name="no_of_pages_on_nav"
                        type="text"
                        value="<?=category_meta($cat->cat_ID, 'no_of_pages_on_nav', NO_OF_PAGES_ON_NAV)?>">
            </td>
        </tr>


        <tr>
            <td></td>
            <td>
                <button type="submit">Submit</button>
            </td>
        </tr>
        </tbody>
    </table>


</form>

<ul>
    <li>
        글 읽기 아래 목록 - 글 읽기 페이지 아래에, 글 목록을 보여주는 옵션입니다.
    </li>
</ul>

<script>
    const mixin = {
        methods: {
            onForumSettingFormSubmit(event) {
                console.log('form data: ', getFormData(event));
                request('forum.updateCategory', getFormData(event), function(setting) {
                    console.log("settings updated: ", setting);
                    refresh();
                }, app.error);
            }
        }
    }
</script>