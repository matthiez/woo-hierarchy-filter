<?php

/**
 * Fired during plugin activation
 *
 * @link       https://bitbucket.org/arivelox/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 * @author     Andre Matthies <matthiez@googlemail.com>
 */
 
register_activation_hook(__FILE__, array('HierarchyFilter_Activator', 'hierarchyfilter_create_table'));
register_activation_hook(__FILE__, array('HierarchyFilter_Activator', 'hierarchyfilter_default_options'));
class HierarchyFilter_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	}

	public static function hierarchyfilter_create_table() {
		global $wpdb;
		$table = $wpdb->prefix . "hierarchyfilter";
		$charset = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		level_1 varchar(255) NOT NULL,
		level_2 varchar(255) NOT NULL,
		level_3 varchar(255) NOT NULL,
		PRIMARY KEY  (id),
		CONSTRAINT assosciation UNIQUE (level_1,level_2,level_3)
		) $charset;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$wpdb->insert($table , array( 'level_1' => 'Alfa Romeo', 'level_2' => '145 (930)', 'level_3' => '1.9 TD 66 kW' ));
		$wpdb->insert($table , array( 'level_1' => 'Alfa Romeo', 'level_2' => '2600 Spider (106)', 'level_3' => '2.6 107 kW' ));
		$wpdb->insert($table , array( 'level_1' => 'Alfa Romeo', 'level_2' => '	Montreal', 'level_3' => '2.6(105,64) 143 kW' ));
		$wpdb->insert($table , array( 'level_1' => 'Audi', 'level_2' => '100 (C1)', 'level_3' => '1.6 63 kW' ));
		$wpdb->insert($table , array( 'level_1' => 'BMW', 'level_2' => '1 Cabriolet (E88)', 'level_3' => '118 d 100 kW' ));
		$wpdb->insert($table , array( 'level_1' => 'Bugatti', 'level_2' => 'Veyron EB 16.4', 'level_3' => '8.0 W16 736' ));
		$wpdb->insert($table , array( 'level_1' => 'Daewoo', 'level_2' => 'KALOS (KLAS)', 'level_3' => '1.4 61 kW' ));
		$wpdb->insert($table , array( 'level_1' => 'FIAT', 'level_2' => '124 Coupe', 'level_3' => '1600 77 kW' ));
	}
	
	public static function hierarchyfilter_default_options() {
		add_option('hierarchyfilter_product_tab', 1);
		add_option('hierarchyfilter_product_tab_thead', 1);
		add_option('hierarchyfilter_text_product_tab', 'HierarchyFilter');
		add_option('hierarchyfilter_text_product_tab_header', 'Assosciated Vehicles');
		add_option('hierarchyfilter_text_level_1', 'Make');
		add_option('hierarchyfilter_text_level_2', 'Model');
		add_option('hierarchyfilter_text_level_3', 'Engine');
		add_option('hierarchyfilter_text_submit', 'Submit');
		add_option('hierarchyfilter_cookie_name', 'hierarchyfilter_customer_vehicle_id');
		add_option('hierarchyfilter_shortcode_labels', 1);
		add_option('hierarchyfilter_widget_labels', 1);
	}
}