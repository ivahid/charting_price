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
$all_prices = Charting_price_Admin::get_all_prices();
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
    <?php foreach ($all_prices as $price){
      ?>
        <tr class="author-self type-post status-publish format-standard hentry">
            <td class="title column-title has-row-actions column-primary page-title" data-colname="عنوان">
                <?php echo get_the_title($price->post_id) ?>
            </td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="قیمت">
                <?php echo $price->price ?>
            </td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="تاریخ">
                <?php echo date('Y/m/d',$price->time) ?>
            </td>
        </tr>
    <?php
    } ?>
    </tbody>

</table>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
