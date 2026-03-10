<?php
if (!defined('ABSPATH')) exit;

add_action('rest_api_init', 'wplm_register_license_api');

function wplm_register_license_api() {

    register_rest_route('license/v1', '/verify', [
        'methods'  => 'POST',
        'callback' => 'wplm_api_verify_license',
        'permission_callback' => '__return_true',
    ]);

}

function wplm_api_verify_license($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'licenses';

    $license_key = sanitize_text_field(
        $request->get_param('license')
    );

    if (!$license_key) {
        return new WP_Error(
            'missing_license',
            'License key is required',
            ['status' => 400]
        );
    }

    $row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table} WHERE license_key = %s LIMIT 1",
            $license_key
        )
    );

    if (!$row) {
        return [
            'status' => 'invalid',
            'license' => $license_key
        ];
    }

    if (strtotime($row->end_date) < time()) {
        return [
            'status'  => 'expired',
            'license' => $row->license_key,
            'expires' => $row->end_date
        ];
    }

    return [
        'status'     => 'valid',
        'license'    => $row->license_key,
        'user_id'    => (int) $row->user_id,
        'product_id' => (int) $row->product_id,
        'expires'    => $row->end_date
    ];
}
