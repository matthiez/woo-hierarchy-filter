<?php
/**
 * Widget Class
 *
 * @link       https://bitbucket.org/arivelox/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 * @author     Andre Matthies <matthiez@googlemail.com>
 */

class HierarchyFilter_Widget extends WP_Widget
{

    function __construct() {
        parent::__construct(
            'hierarchyfilter_widget', // base ID of your widget
            __('HierarchyFilter Widget', 'hierarchyfilter'), // widget name will appear in UI
            ['description' => __('HierarchyFilter Widget', 'hierarchyfilter'),] // widget description
        );
    }

    public function hierarchyfilter_load_widget() {
        register_widget('hierarchyfilter_widget');
    }

    // creating widget front-end
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        require_once(plugin_dir_path(__FILE__) . '../public/partials/hierarchyfilter-widget-display.php');
        echo $args['after_widget'];
    }

    // widget Backend 
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Your Title', 'hierarchyfilter');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?= $this->get_field_id('title'); ?>" name="<?= $this->get_field_name('title'); ?>" type="text"
                   value="<?= esc_attr($title); ?>"/>
        </p>
        <?php
    }

    // updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

    public function hierarchyfilter_widget_process_form() {
        if (!isset($_POST['hierarchyfilter-widget-submit-nonce']) || !wp_verify_nonce($_POST['hierarchyfilter-widget-submit-nonce'], 'hierarchyfilter-widget-submit')) {
            die(__('HierarchyFilter Widget: Nonce mismatch when processing form.', 'hierarchyfilter'));
        }
        else {
            global $wpdb;
            $table = $wpdb->prefix . "hierarchyfilter";
            $assosciation = $wpdb->get_results($wpdb->prepare("SELECT id FROM $table WHERE `level_1` = %s AND `level_2` = %s AND `level_3` = %s", $_POST['hierarchyfilter-widget-level-1'], $_POST['hierarchyfilter-widget-level-2'], $_POST['hierarchyfilter-widget-level-3']));
            if ($assosciation) {
                foreach ($assosciation as $key => $value) setcookie(get_option('hierarchyfilter_cookie_name'), $value->id, time() + (86400 * 30), "/");
                wp_redirect(get_permalink(wc_get_page_id('shop')));
                exit;
            }
        }
    }
}