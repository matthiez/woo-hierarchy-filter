<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://bitbucket.org/arivelox/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 * @author     Andre Matthies <matthiez@googlemail.com>
 */
 
class HierarchyFilter {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		$this->plugin_name = 'hierarchyfilter';
		$this->version = '1.0.0';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcode_hooks();
		$this->define_widget_hooks();
	}

	private function load_dependencies() {
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-hierarchyfilter-admin.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hierarchyfilter-i18n.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hierarchyfilter-loader.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hierarchyfilter-shortcode.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-hierarchyfilter-public.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hierarchyfilter-widget.php';
		$this->loader = new HierarchyFilter_Loader();
	}

	private function set_locale() {
		$plugin_i18n = new HierarchyFilter_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	private function define_admin_hooks() {
		$admin_hooks = new HierarchyFilter_Admin($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('admin_enqueue_scripts', $admin_hooks, 'enqueue_styles'); // admin styles
		$this->loader->add_action('admin_enqueue_scripts', $admin_hooks, 'enqueue_scripts'); // admin scripts
		$this->loader->add_action('admin_menu', $admin_hooks, 'hierarchyfilter_add_admin_menu'); // admin menu
		$this->loader->add_action('admin_init', $admin_hooks, 'hierarchyfilter_register_sections'); // sections
		$this->loader->add_action('admin_init', $admin_hooks, 'hierarchyfilter_register_settings'); // settings
		$this->loader->add_action('admin_init', $admin_hooks, 'hierarchyfilter_register_fields'); // fields
		/*       META BOXES       */
		$this->loader->add_action('add_meta_boxes', $admin_hooks, 'hierarchyfilter_meta_box_single_register'); // register meta box
		$this->loader->add_action( 'woocommerce_product_after_variable_attributes', $admin_hooks, 'hierarchyfilter_variations_metabox', 10, 3 );
		/*       META DATA       */
		$this->loader->add_action('woocommerce_process_product_meta', $admin_hooks, 'hierarchyfilter_meta_box_single_save'); // save fields
		$this->loader->add_action( 'woocommerce_save_product_variation', $admin_hooks, 'hierarchyfilter_variations_metabox_save', 20, 2 );
		/*       ALLOW SQL UPLOADS       */
		$this->loader->add_filter('upload_mimes', $admin_hooks, 'hierarchyfilter_allow_sql_uploads', 1, 1);
		/*       AJAX       */
		$this->loader->add_action('wp_ajax_hierarchyfilter_add_new', $admin_hooks, 'hierarchyfilter_ajax_add_new');
		$this->loader->add_action('wp_ajax_hierarchyfilter_admin_database_delete', $admin_hooks, 'hierarchyfilter_ajax_admin_database_delete');
		$this->loader->add_action('wp_ajax_hierarchyfilter_admin_database_edit_level_1', $admin_hooks, 'hierarchyfilter_ajax_admin_database_edit_level_1');
		$this->loader->add_action('wp_ajax_hierarchyfilter_admin_database_edit_level_2', $admin_hooks, 'hierarchyfilter_ajax_admin_database_edit_level_2');
		$this->loader->add_action('wp_ajax_hierarchyfilter_admin_database_edit_level_3', $admin_hooks, 'hierarchyfilter_ajax_admin_database_edit_level_3');
	}

	private function define_public_hooks() {
		$public_hooks = new HierarchyFilter_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('wp_enqueue_scripts', $public_hooks, 'enqueue_styles'); // public styles
		$this->loader->add_action('wp_enqueue_scripts', $public_hooks, 'enqueue_scripts'); // public scripts
		/*       AJAX       */
		$this->loader->add_action('wp_head', $public_hooks, 'hierarchyfilter_add_ajax_public');
		$this->loader->add_action('wp_ajax_hierarchyfilter_load_level_2', $public_hooks, 'hierarchyfilter_load_level_2_callback');
		$this->loader->add_action('wp_ajax_hierarchyfilter_load_level_3', $public_hooks, 'hierarchyfilter_load_level_3_callback');
		$this->loader->add_action('wp_ajax_nopriv_hierarchyfilter_load_level_2', $public_hooks, 'hierarchyfilter_load_level_2_callback');
		$this->loader->add_action('wp_ajax_nopriv_hierarchyfilter_load_level_3', $public_hooks, 'hierarchyfilter_load_level_3_callback');
		/*       QUERY       */
		$this->loader->add_action('pre_get_posts', $public_hooks, 'hierarchyfilter_remove_products_from_shop_page'); // query mod to display only assosciated products
		/*       CUSTOM PRODUCT TAB       */
		$this->loader->add_filter( 'woocommerce_product_tabs', $public_hooks, 'hierarchyfilter_custom_product_tab' );
	}
	
	private function define_shortcode_hooks() {
		$shortcode_hooks = new HierarchyFilter_Shortcode( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('admin_head', $shortcode_hooks, 'hierarchyfilter_tinymce_button'); // dependency check for shortcode button
		$this->loader->add_filter('mce_external_plugins', $shortcode_hooks, 'hierarchyfilter_add_tinymce_plugin'); // add a callback to regiser our tinymce plugin   
		$this->loader->add_filter('mce_buttons', $shortcode_hooks, 'hierarchyfilter_register_tinymce_button'); // add a callback to add our button to the TinyMCE toolbar
		add_shortcode('hierarchyfilter_dropdown' , array($shortcode_hooks, 'hierarchyfilter_shortcode_dropdown_content') );
		/*       POST PROCESSING       */
		$this->loader->add_action('admin_post_hierarchyfilter_shortcode_process_form', $shortcode_hooks, 'hierarchyfilter_shortcode_process_form');
		$this->loader->add_action('admin_post_nopriv_hierarchyfilter_shortcode_process_form', $shortcode_hooks, 'hierarchyfilter_shortcode_process_form');
	}
	
	private function define_widget_hooks() {
		$widget_hooks = new HierarchyFilter_Widget( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'widgets_init', $widget_hooks, 'hierarchyfilter_load_widget' );
		/*       POST PROCESSING       */
		$this->loader->add_action('admin_post_hierarchyfilter_widget_process_form', $widget_hooks, 'hierarchyfilter_widget_process_form');
		$this->loader->add_action('admin_post_nopriv_hierarchyfilter_widget_process_form', $widget_hooks, 'hierarchyfilter_widget_process_form');
	}
	
	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}
}
