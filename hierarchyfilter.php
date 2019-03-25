<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/matthiez/wp-hierarchy-filter/
 * @since             1.0.0
 * @package           HierarchyFilter
 *
 * @wordpress-plugin
 * Plugin Name:       HierarchyFilter
 * Plugin URI:        https://github.com/matthiez/wp-hierarchy-filter/
 * Description:       WooCommerce plugin for assosciating certain things like vehicles with products. With Shortcode support.
 * Version:           1.0.0
 * Author:            André Matthies
 * Author URI:        https://github.com/matthiez/
 * License:           MIT
 * License URI:       LICENSE.txt
 * Text Domain:       hierarchyfilter
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
} // If this file is called directly, abort.

function activate_HierarchyFilter() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-hierarchyfilter-activator.php';
    HierarchyFilter_Activator::activate();
    HierarchyFilter_Activator::hierarchyfilter_create_table();
    HierarchyFilter_Activator::hierarchyfilter_default_options();
}

function deactivate_HierarchyFilter() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-hierarchyfilter-deactivator.php';
    HierarchyFilter_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_HierarchyFilter');
register_deactivation_hook(__FILE__, 'deactivate_HierarchyFilter');

require plugin_dir_path(__FILE__) . 'includes/class-hierarchyfilter.php';

function run_HierarchyFilter() {
    $plugin = new HierarchyFilter();
    $plugin->run();
}

run_HierarchyFilter();
