<?php
/**
 * My Account - Orders - OwizoTech
 */
defined('ABSPATH') || exit;

$customer_orders = wc_get_orders( apply_filters( 'woocommerce_my_account_my_orders_query', [
    'customer' => get_current_user_id(),
    'page'     => isset($_GET['paged']) ? absint($_GET['paged']) : 1,
    'paginate' => true,
    'limit'    => (int) apply_filters('woocommerce_my_account_my_orders_per_page', 10),
] ) );

$has_orders = 0 < $customer_orders->total;
?>

<div class="account-orders-page">
    <div class="account-section-header">
        <h2 class="account-section-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <?php _e('My Orders', 'owizotech'); ?>
        </h2>
        <?php if ($has_orders) : ?>
        <span class="account-section-count"><?php echo esc_html($customer_orders->total); ?> <?php _e('orders', 'owizotech'); ?></span>
        <?php endif; ?>
    </div>

    <?php if ( $has_orders ) : ?>
    <div class="orders-table-wrap">
        <table class="owizo-orders-table">
            <thead>
                <tr>
                    <th><?php _e('Order', 'owizotech'); ?></th>
                    <th><?php _e('Date', 'owizotech'); ?></th>
                    <th><?php _e('Status', 'owizotech'); ?></th>
                    <th><?php _e('Items', 'owizotech'); ?></th>
                    <th><?php _e('Total', 'owizotech'); ?></th>
                    <th><?php _e('Actions', 'owizotech'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $customer_orders->orders as $order ) :
                    $status = $order->get_status();
                    $items  = $order->get_items();
                    $item_names = [];
                    foreach ( array_slice($items, 0, 2) as $item ) {
                        $item_names[] = $item->get_name();
                    }
                    $items_text = implode(', ', $item_names);
                    if ( count($items) > 2 ) $items_text .= ' +' . (count($items) - 2);
                ?>
                <tr class="order-row">
                    <td class="order-id-cell">
                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="order-number-link">
                            #<?php echo esc_html($order->get_order_number()); ?>
                        </a>
                    </td>
                    <td class="order-date-cell">
                        <span class="order-date"><?php echo esc_html($order->get_date_created()->date_i18n(get_option('date_format'))); ?></span>
                    </td>
                    <td class="order-status-cell">
                        <span class="order-status-badge order-status-<?php echo esc_attr($status); ?>">
                            <?php echo esc_html( wc_get_order_status_name($status) ); ?>
                        </span>
                    </td>
                    <td class="order-items-cell">
                        <span class="order-items-preview" title="<?php echo esc_attr($items_text); ?>">
                            <?php echo esc_html( substr($items_text, 0, 40) . (strlen($items_text) > 40 ? '…' : '') ); ?>
                        </span>
                    </td>
                    <td class="order-total-cell">
                        <strong class="order-total-amount"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></strong>
                    </td>
                    <td class="order-actions-cell">
                        <div class="order-action-btns">
                            <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn btn-ghost btn-sm">
                                <?php _e('View', 'owizotech'); ?>
                            </a>
                            <?php if ( $order->has_status('completed') ) : ?>
                            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg('order_again', $order->get_id()), 'woocommerce-order_again' ) ); ?>" class="btn btn-secondary btn-sm">
                                <?php _e('Reorder', 'owizotech'); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
    <div class="orders-pagination">
        <?php for ($i = 1; $i <= $customer_orders->max_num_pages; $i++) : ?>
        <a href="<?php echo esc_url( add_query_arg('paged', $i) ); ?>"
           class="page-btn <?php echo (isset($_GET['paged']) && (int)$_GET['paged'] === $i) ? 'active' : (($i === 1 && !isset($_GET['paged'])) ? 'active' : ''); ?>">
            <?php echo esc_html($i); ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <?php else : ?>
    <div class="account-empty-state">
        <div class="empty-icon">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
        <h3><?php _e('No orders yet', 'owizotech'); ?></h3>
        <p><?php _e('You haven\'t placed any orders. Start shopping now!', 'owizotech'); ?></p>
        <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
            <?php _e('Browse Shop', 'owizotech'); ?>
        </a>
    </div>
    <?php endif; ?>
</div>
