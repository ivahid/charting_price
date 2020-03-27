<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       s7n.ir
 * @since      1.0.0
 *
 * @package    Charting_price
 * @subpackage Charting_price/admin/partials
 */
$args = [
    'post_type' => 'product',
];

$query = new WP_Query($args);
if ($query->have_posts()) {
    ?>
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <th scope="col" class="manage-column column-title">عنوان</th>
            <th scope="col" class="manage-column column-title">قیمت</th>
            <th scope="col" class="manage-column column-title">آخرین بروزرسانی</th>
            <!--        <th scope="col" class="manage-column column-title">وضعیت</th>-->
        </tr>
        </thead>

        <tbody id="the-list">
        <?php
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $product_price = get_post_meta($post_id,'_price',true);
            ?>
            <tr class="author-self type-post status-publish format-standard hentry">
                <td class="title column-title has-row-actions column-primary page-title" data-colname="عنوان">
                    <?php the_title(); ?>
                </td>
                <td class="title column-title has-row-actions column-primary page-title" data-colname="قیمت">
                    <?php echo $product_price ?>
                </td>
                <td class="title column-title has-row-actions column-primary page-title" data-colname="تاریخ">
                    <?= get_the_date('Y/m/d') ?>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>

    </table>
    <?php
}