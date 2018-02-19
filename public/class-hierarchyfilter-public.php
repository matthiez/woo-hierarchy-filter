<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://bitbucket.org/arivelox/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/public
 * @author     Andre Matthies <matthiez@googlemail.com>
 */
class HierarchyFilter_Public
{
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/hierarchyfilter-public.css',
            [],
            $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/hierarchyfilter-public.js',
            ['jquery'],
            $this->version, false);
    }

    public function hierarchyfilter_add_ajax_public() {
        $html = '<script type="text/javascript">';
        $html .= 'var ajaxurl = "' . admin_url('admin-ajax.php') . '"';
        $html .= '</script>';
        echo $html;
    }

    public function hierarchyfilter_load_level_2_callback() {
        global $wpdb;
        $table = $wpdb->prefix . "hierarchyfilter";
        $level_2 = $wpdb->get_results($wpdb->prepare("SELECT level_2 FROM $table WHERE level_1 = %s", $_POST['hierarchyfilter_level_1']));
        echo "<option value='' disabled selected>" . get_option('hierarchyfilter_text_level_2') . "</option>";
        if ($level_2)
            foreach ($level_2 as $key => $value)
                echo '<option value="' . $value->level_2 . '">' . $value->level_2 . '</option>';
        else echo '<option value="AJAX-ERROR">AJAX-ERROR</option>';
    }

    public function hierarchyfilter_load_level_3_callback() {
        global $wpdb;
        $table = $wpdb->prefix . "hierarchyfilter";
        $level_3 = $wpdb->get_results($wpdb->prepare("SELECT level_3 FROM $table WHERE level_2 = %s", $_POST['hierarchyfilter_level_2']));
        echo "<option value='' disabled selected>" . get_option('hierarchyfilter_text_level_3') . "</option>";
        if ($level_3)
            foreach ($level_3 as $key => $value)
                echo '<option value="' . $value->level_3 . '">' . $value->level_3 . '</option>';
        else echo '<option value="AJAX-ERROR">AJAX-ERROR</option>';
    }

    public function hierarchyfilter_remove_products_from_shop_page($query) {
        if (!$query->is_main_query() || !$query->is_post_type_archive() || is_admin()) {
            return false;
        }
        if (isset($_COOKIE[get_option('hierarchyfilter_cookie_name')])) {
            $id = $_COOKIE[get_option('hierarchyfilter_cookie_name')];
        }
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (!empty($id) && is_shop()) {
            $query->set('meta_query', [[
                'key' => 'hierarchyfilter_assosciations',
                'value' => '"' . $id . '"',
                'compare' => 'LIKE',
                'type' => 'char',
            ]]);
            return $query;
        }
        remove_action('pre_get_posts', 'hierarchyfilter_remove_products_from_shop_page');
        return true;
    }

    public function hierarchyfilter_custom_product_tab($tabs) {
        if (1 == get_option('hierarchyfilter_product_tab')) {
            $tabs['hierarchy_filter'] = [
                'title' => __(get_option('hierarchyfilter_text_product_tab'), 'hierarchyfilter'),
                'priority' => 50,
                'callback' => [$this, 'hierarchyfilter_custom_product_tab_callback']
            ];
            return $tabs;
        }
        return false;
    }

    public function hierarchyfilter_custom_product_tab_callback() {
        global $wpdb;
        $table = $wpdb->prefix . "hierarchyfilter";
        $post_meta = get_post_meta(get_the_ID(), 'hierarchyfilter_assosciations', true);
        foreach ($post_meta as $id) {
            $ids[] = $id;
            $level_1 = $wpdb->get_col($wpdb->prepare("SELECT level_1 FROM $table WHERE id = %s", $id));
            $levels_1[] = $level_1;
            $level_2 = $wpdb->get_col($wpdb->prepare("SELECT level_2 FROM $table WHERE id = %s", $id));
            $levels_2[] = $level_2;
            $level_3 = $wpdb->get_col($wpdb->prepare("SELECT level_3 FROM $table WHERE id = %s", $id));
            $levels_3[] = $level_3;
        }
        $levels_1 = array_map('current', $levels_1);
        $levels_2 = array_map('current', $levels_2);
        $levels_3 = array_map('current', $levels_3);
        $data = array_map(function ($level_1, $level_2, $level_3) {
            return [get_option('hierarchyfilter_text_level_1') => $level_1, get_option('hierarchyfilter_text_level_2') => $level_2, get_option('hierarchyfilter_text_level_3') => $level_3];
        }, $levels_1, $levels_2, $levels_3);
        echo "<h2>" . get_option('hierarchyfilter_text_product_tab_header') . "</h2>";
        if (count($data) > 0) { ?>
            <table>
                <?php if (1 == get_option('hierarchyfilter_product_tab_thead')) { ?>
                    <thead>
                    <tr>
                        <th><?php echo implode('</th><th>', array_keys(current($data))); ?></th>
                    </tr>
                    </thead>
                <?php } ?>
                <tbody>
                <?php foreach ($data as $key => $row) { ?>
                    <tr>
                        <td><?php echo implode('</td><td>', $row); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php }
    }
}
