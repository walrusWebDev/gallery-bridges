<?php
/**
 * Plugin Name:     Gallery Bridges
 * Plugin URI:      https://tomatketfirst.com
 * Description:     A plugin to manage image galleries sourced from external APIs.
 * Version:         0.1.0
 * Author:          Lbridges
 * Author URI:      https://tomatketfirst.com
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     gallery-bridges
 * Domain Path:     /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define plugin constants (optional, but good practice)
define( 'GALLERY_BRIDGES_VERSION', '0.1.0' );
define( 'GALLERY_BRIDGES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GALLERY_BRIDGES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files (to be created later)
require_once GALLERY_BRIDGES_PLUGIN_DIR . 'includes/admin/admin-menus.php';
require_once GALLERY_BRIDGES_PLUGIN_DIR . 'includes/admin/manage-connections.php';
require_once GALLERY_BRIDGES_PLUGIN_DIR . 'includes/admin/manage-collections.php';
require_once GALLERY_BRIDGES_PLUGIN_DIR . 'includes/database/api-options-table.php';
require_once GALLERY_BRIDGES_PLUGIN_DIR . 'includes/database/image-collections-table.php';

// Activation hook to create database tables
register_activation_hook( __FILE__, 'gb_create_database_tables' );
function gb_create_database_tables() {
    require_once GALLERY_BRIDGES_PLUGIN_DIR . 'includes/database/class-gb-database.php';
    GB_Database::create_tables();
}

/**
 * Class GB_Database
 *
 * Handles the creation of the plugin's database tables.
 */
class GB_Database {

    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $api_options_table_name = $wpdb->prefix . 'gb_api_options';
        $image_collections_table_name = $wpdb->prefix . 'gb_image_collections';

        $sql_api_options = "CREATE TABLE IF NOT EXISTS {$api_options_table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            base_url TEXT NOT NULL,
            endpoint VARCHAR(255),
            api_key TEXT,
            search_structure TEXT, -- How to construct the search URL
            UNIQUE KEY (name)
        ) {$charset_collate};";

        $sql_image_collections = "CREATE TABLE IF NOT EXISTS {$image_collections_table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            api_option_id BIGINT UNSIGNED,
            search_query VARCHAR(255),
            collected_images_meta LONGTEXT,
            collected_images_source LONGTEXT,
            FOREIGN KEY (api_option_id) REFERENCES {$api_options_table_name}(id),
            UNIQUE KEY (name)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( array( $sql_api_options, $sql_image_collections ) );
    }

}