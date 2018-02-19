<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
} // exit if uninstall not called from WP

global $wpdb;
$table_name = $wpdb->prefix . "hierarchyfilter";
$wpdb->query("DROP TABLE IF EXISTS $table_name");

delete_option('hierarchyfilter_product_tab');
delete_option('hierarchyfilter_product_tab_thead');
delete_option('hierarchyfilter_text_product_tab', 'HierarchyFilter');
delete_option('hierarchyfilter_text_product_tab_header');
delete_option('hierarchyfilter_text_level_1');
delete_option('hierarchyfilter_text_level_2');
delete_option('hierarchyfilter_text_level_3');
delete_option('hierarchyfilter_text_submit');
delete_option('hierarchyfilter_cookie_name');
delete_option('hierarchyfilter_shortcode_labels');
delete_option('hierarchyfilter_widget_labels');
