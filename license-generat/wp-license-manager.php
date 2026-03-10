<?php
/*
Plugin Name: WP License Manager
Description: Manage product licenses with API and admin panel.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

// includes
require_once plugin_dir_path(__FILE__) . 'includes/license-hooks.php';
require_once plugin_dir_path(__FILE__) . 'includes/license-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/license-api.php';

// create licenses table on plugin activation
register_activation_hook(__FILE__, 'wplm_create_table');
function wplm_create_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'licenses';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id BIGINT(20) NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) NOT NULL,
        product_id BIGINT(20) NOT NULL,
        license_key VARCHAR(255) NOT NULL,
        start_date DATETIME NOT NULL,
        end_date DATETIME NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'active',
        PRIMARY KEY(id),
        UNIQUE KEY license_key (license_key)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
