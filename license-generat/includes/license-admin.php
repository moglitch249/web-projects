<?php
if (!defined('ABSPATH')) exit;

// admin menu
add_action('admin_menu', function() {
    add_menu_page('License Manager', 'License Manager', 'manage_options', 'wplm_admin', 'wplm_admin_page', 'dashicons-admin-network', 25);
});

function wplm_admin_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'licenses';

    if (isset($_POST['wplm_update_license'])) {
        $id = intval($_POST['license_id']);
        $end_date = sanitize_text_field($_POST['end_date']);
        $status = sanitize_text_field($_POST['status']);
        $wpdb->update($table, ['end_date'=>$end_date, 'status'=>$status], ['id'=>$id]);
        echo '<div class="notice notice-success"><p>License updated!</p></div>';
    }

    $licenses = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");
    echo '<div class="wrap"><h1>License Manager</h1><table class="widefat fixed" cellspacing="0"><thead><tr>
          <th>ID</th><th>User</th><th>Product</th><th>License Key</th><th>Start</th><th>End</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
    foreach ($licenses as $l) {
        echo '<tr>
              <td>' . esc_html($l->id) . '</td>
              <td>' . esc_html($l->user_id) . '</td>
              <td>' . esc_html($l->product_id) . '</td>
              <td>' . esc_html($l->license_key) . '</td>
              <td>' . esc_html($l->start_date) . '</td>
              <td>' . esc_html($l->end_date) . '</td>
              <td>' . esc_html($l->status) . '</td>
              <td>
                <form method="post" style="display:inline">
                  <input type="hidden" name="license_id" value="' . esc_attr($l->id) . '">
                  <input type="text" name="end_date" value="' . esc_attr($l->end_date) . '" style="width:120px">
                  <select name="status">
                    <option value="active"' . selected($l->status,'active',false) . '>Active</option>
                    <option value="expired"' . selected($l->status,'expired',false) . '>Expired</option>
                  </select>
                  <input type="submit" name="wplm_update_license" class="button button-small" value="Update">
                </form>
              </td></tr>';
    }
    echo '</tbody></table></div>';
}
