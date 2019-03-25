<?php
/**
 * Shortcode Class
 *
 * @link       https://github.com/matthiez/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 * @author     André Matthies <matthiez@gmail.com>
 */

class HierarchyFilter_Shortcode
{

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function hierarchyfilter_tinymce_button() {
        // abort if the user does not have appropriate permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') return;
    }

    // callback to register tinymce plugin
    public function hierarchyfilter_add_tinymce_plugin($plugin_array) {
        $plugin_array['hierarchyfilter_tinymce_button'] = plugins_url('../admin/js/hierarchyfilter-admin.js', __FILE__);
        return $plugin_array;
        if (!in_array($typenow, ['post', 'page'])) {
            return;
        }
    }

    // callback to add button to toolbar
    public function hierarchyfilter_register_tinymce_button($buttons) {
        if (!get_option('hierarchyfilter_shortcode_button_disable')) {
            array_push($buttons, "hierarchyfilter_tinymce_button");
            return $buttons;
        }
        else return $buttons;
    }

    public function hierarchyfilter_shortcode_dropdown_content() {
        require_once(plugin_dir_path(__FILE__) . '../public/partials/hierarchyfilter-shortcode-display.php');
    }

    public function hierarchyfilter_shortcode_process_form() {
        if (!isset($_POST['hierarchyfilter-shortcode-submit-nonce']) || !wp_verify_nonce($_POST['hierarchyfilter-shortcode-submit-nonce'], 'hierarchyfilter-shortcode-submit')) {
            die(__('HierarchyFilter Shortcode: Nonce mismatch when processing form.', 'hierarchyfilter'));
        }
        else {
            global $wpdb;
            $table = $wpdb->prefix . "hierarchyfilter";
            $assosciation = $wpdb->get_results($wpdb->prepare("SELECT id FROM $table WHERE `level_1` = %s AND `level_2` = %s AND `level_3` = %s", $_POST['hierarchyfilter-shortcode-level-1'], $_POST['hierarchyfilter-shortcode-level-2'], $_POST['hierarchyfilter-shortcode-level-3']));
            if ($assosciation) {
                foreach ($assosciation as $key => $value) setcookie(get_option('hierarchyfilter_cookie_name'), $value->id, time() + (86400 * 30), "/");
                wp_redirect(get_permalink(wc_get_page_id('shop')));
                exit;
            }
        }
    }
}