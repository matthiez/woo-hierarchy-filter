<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://bitbucket.org/arivelox/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 * @author     Andre Matthies <matthiez@googlemail.com>
 */
class HierarchyFilter_Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
    }

    /* 	public static function dbDeleteTable() {
             global $wpdb;
            $table_name = $wpdb->prefix . "hierarchyfilter";
            $wpdb->query( "DROP TABLE IF EXISTS $table_name" ); 
            delete_option('hierarchyfilter_db_version');
        } */

}
