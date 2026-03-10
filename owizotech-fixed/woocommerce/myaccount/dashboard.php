<?php
/**
 * My Account Dashboard - OwizoTech
 */
defined('ABSPATH') || exit;

$allowed_html = ['a' => ['href' => [], 'title' => []]];
?>

<div class="account-dashboard">

    <!-- Quick Stats -->
    <div class="dashboard-stats">
        <?php
        $customer_orders = wc_get_orders(['customer' => get_current_user_id(), 'status' => ['wc-completed'], 'limit' => -1]);
        $total_orders    = wc_get_customer_order_count( get_current_user_id() );
        $total_spent     = wc_get_customer_total_spent( get_current_user_id() );
        ?>
        <div class="dash-stat-card">
            <div class="dash-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <div class="dash-stat-info">
                <span class="dash-stat-value"><?php echo esc_html($total_orders); ?></span>
                <span class="dash-stat-label"><?php _e('Total Orders', 'owizotech'); ?></span>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon dash-stat-icon--green">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="dash-stat-info">
                <span class="dash-stat-value"><?php echo wp_kses( wc_price($total_spent), $allowed_html ); ?></span>
                <span class="dash-stat-label"><?php _e('Total Spent', 'owizotech'); ?></span>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon dash-stat-icon--purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <div class="dash-stat-info">
                <?php
                $wishlist_count = function_exists('YITH_WCWL') ? count(YITH_WCWL()->get_products()) : '—';
                ?>
                <span class="dash-stat-value"><?php echo esc_html($wishlist_count); ?></span>
                <span class="dash-stat-label"><?php _e('Wishlist Items', 'owizotech'); ?></span>
            </div>
        </div>
    </div>

    <!-- Welcome message -->
    <div class="dashboard-welcome">
        <p>
            <?php
            printf(
                wp_kses_post( __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ) ),
                esc_url( wc_get_account_endpoint_url( 'orders' ) ),
                esc_url( wc_get_account_endpoint_url( 'edit-address' ) ),
                esc_url( wc_get_account_endpoint_url( 'edit-account' ) )
            );
            ?>
        </p>
    </div>

    <!-- Recent Orders -->
    <?php
    $recent_orders = wc_get_orders(['customer' => get_current_user_id(), 'limit' => 5, 'orderby' => 'date', 'order' => 'DESC']);
    if ( $recent_orders ) : ?>
    <div class="dashboard-section">
        <div class="dashboard-section-header">
            <h3 class="dashboard-section-title"><?php _e('Recent Orders', 'owizotech'); ?></h3>
            <a href="<?php echo esc_url( wc_get_account_endpoint_url('orders') ); ?>" class="dashboard-view-all">
                <?php _e('View All', 'owizotech'); ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
        <div class="recent-orders-list">
            <?php foreach ( $recent_orders as $order ) :
                $status = $order->get_status();
                $status_labels = [
                    'pending'    => __('Pending', 'owizotech'),
                    'processing' => __('Processing', 'owizotech'),
                    'on-hold'    => __('On Hold', 'owizotech'),
                    'completed'  => __('Completed', 'owizotech'),
                    'cancelled'  => __('Cancelled', 'owizotech'),
                    'refunded'   => __('Refunded', 'owizotech'),
                    'failed'     => __('Failed', 'owizotech'),
                ];
            ?>
            <div class="recent-order-row">
                <div class="ro-id">
                    <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="ro-number">#<?php echo esc_html( $order->get_order_number() ); ?></a>
                    <span class="ro-date"><?php echo esc_html( $order->get_date_created()->date_i18n( get_option('date_format') ) ); ?></span>
                </div>
                <span class="order-status-badge order-status-<?php echo esc_attr($status); ?>">
                    <?php echo esc_html( $status_labels[$status] ?? ucfirst($status) ); ?>
                </span>
                <span class="ro-total"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
                <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="btn btn-ghost btn-sm">
                    <?php _e('View', 'owizotech'); ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
