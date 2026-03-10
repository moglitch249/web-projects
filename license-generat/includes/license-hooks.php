<?php
if (!defined('ABSPATH')) exit;

/* =====================================================
   1️⃣ توليد كود لايسنس (مرة واحدة فقط)
===================================================== */
if (!function_exists('wplm_generate_license')) {
    function wplm_generate_license($length = 16) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $chars[wp_rand(0, strlen($chars) - 1)];
        }
        return $key;
    }
}

/* =====================================================
   2️⃣ تفعيل اللايسنس للمنتج + مدة اللايسنس
===================================================== */
add_action('woocommerce_product_options_general_product_data', function () {

    woocommerce_wp_checkbox([
        'id' => '_wplm_enable_license',
        'label' => 'Enable License System'
    ]);

    woocommerce_wp_text_input([
        'id' => '_license_days',
        'label' => 'License Duration (Days)',
        'type' => 'number',
        'desc_tip' => true,
        'description' => 'Default: 30 days'
    ]);
});

add_action('woocommerce_process_product_meta', function ($post_id) {
    update_post_meta($post_id, '_wplm_enable_license', isset($_POST['_wplm_enable_license']) ? 'yes' : 'no');
    if (isset($_POST['_license_days'])) {
        update_post_meta($post_id, '_license_days', intval($_POST['_license_days']));
    }
});

/* =====================================================
   3️⃣ منع إضافة المنتج للسلة لو عنده لايسنس نشط
===================================================== */
add_filter('woocommerce_add_to_cart_validation', function ($passed, $product_id) {

    if (!is_user_logged_in()) return $passed;

    if (get_post_meta($product_id, '_wplm_enable_license', true) !== 'yes')
        return $passed;

    global $wpdb;
    $table = $wpdb->prefix . 'licenses';
    $user_id = get_current_user_id();

    $license = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table
         WHERE user_id=%d AND product_id=%d
         AND status='active'
         AND end_date >= NOW()
         LIMIT 1",
        $user_id, $product_id
    ));

    if ($license) {
        wc_add_notice(
            sprintf(
                'You already have an active license.<br>
                 <strong>License:</strong> %s<br>
                 <strong>Expires:</strong> %s',
                esc_html($license->license_key),
                esc_html($license->end_date)
            ),
            'error'
        );
        return false;
    }

    return $passed;
}, 10, 2);

/* =====================================================
   4️⃣ توليد اللايسنس مباشرة بعد الدفع
===================================================== */
add_action('woocommerce_payment_complete', function ($order_id) {

    $order = wc_get_order($order_id);
    if (!$order) return;

    global $wpdb;
    $table = $wpdb->prefix . 'licenses';
    $user_id = $order->get_user_id();

    foreach ($order->get_items() as $item) {

        $product_id = $item->get_product_id();

        if (get_post_meta($product_id, '_wplm_enable_license', true) !== 'yes')
            continue;

        $days = get_post_meta($product_id, '_license_days', true);
        if (!$days) $days = 30;

        $license_key = wplm_generate_license();

        $wpdb->insert($table, [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'license_key' => $license_key,
            'start_date' => current_time('mysql'),
            'end_date' => date('Y-m-d H:i:s', strtotime("+$days days")),
            'status' => 'active'
        ]);

        wc_add_order_item_meta($item->get_id(), 'License Key', $license_key);
    }

    $order->update_status('completed', 'License generated successfully');
});

/* =====================================================
   5️⃣ زر Renew License (صفحة المنتج)
===================================================== */
add_action('woocommerce_single_product_summary', function () {

    if (!is_user_logged_in()) return;

    global $product, $wpdb;
    $product_id = $product->get_id();
    $user_id = get_current_user_id();

    if (get_post_meta($product_id, '_wplm_enable_license', true) !== 'yes')
        return;

    $table = $wpdb->prefix . 'licenses';

    $license = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table
         WHERE user_id=%d AND product_id=%d
         ORDER BY end_date DESC LIMIT 1",
        $user_id, $product_id
    ));

    if (!$license) return;

    $url = add_query_arg([
        'add-to-cart' => $product_id,
        'renew_license' => 1
    ], wc_get_cart_url());

    echo '<a href="' . esc_url($url) . '" class="button alt">🔄 Renew License</a>';
}, 35);

/* =====================================================
   6️⃣ تمييز الطلب كـ Renew
===================================================== */
add_filter('woocommerce_add_cart_item_data', function ($data) {
    if (isset($_GET['renew_license'])) {
        $data['renew_license'] = true;
    }
    return $data;
}, 10);

/* =====================================================
   7️⃣ تنفيذ التجديد بعد الدفع
===================================================== */
add_action('woocommerce_payment_complete', function ($order_id) {

    $order = wc_get_order($order_id);
    if (!$order) return;

    global $wpdb;
    $table = $wpdb->prefix . 'licenses';
    $user_id = $order->get_user_id();

    foreach ($order->get_items() as $item) {

        if (!$item->get_meta('renew_license')) continue;

        $product_id = $item->get_product_id();
        $days = get_post_meta($product_id, '_license_days', true);
        if (!$days) $days = 30;

        $license = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table
             WHERE user_id=%d AND product_id=%d
             ORDER BY end_date DESC LIMIT 1",
            $user_id, $product_id
        ));

        if ($license) {
            $new_end = date(
                'Y-m-d H:i:s',
                strtotime("+$days days", strtotime($license->end_date))
            );

            $wpdb->update(
                $table,
                ['end_date' => $new_end],
                ['id' => $license->id]
            );

            wc_add_order_item_meta(
                $item->get_id(),
                'License Renewed Until',
                $new_end
            );
        }
    }
}, 5);
