<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://bitbucket.org/arivelox/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 * @author     Andre Matthies <matthiez@googlemail.com>
 */
 
class HierarchyFilter_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'hierarchyfilter',
			false,
			dirname( dirname( plugin_basename(__FILE__) ) ) . '/languages/'
		);

	}

}
