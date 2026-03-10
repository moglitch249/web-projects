<?php
if (!defined('ABSPATH')) exit;

/**
 * Schedule daily license expiration check
 */
add_action('wp', function () {
    if (!wp_next_scheduled('wplm_check_expired_licenses')) {
        wp_schedule_event(time(), 'daily', 'wplm_check_expired_licenses');
    }
});

/**
 * Expire licenses automatically
 */
add_action('wplm_check_expired_licenses', function () {
    global $wpdb;
    $table = $wpdb->prefix . 'licenses';

    $wpdb->query("
        UPDATE {$table}
        SET status = 'expired'
        WHERE status = 'active'
        AND end_date < NOW()
    ");
});
