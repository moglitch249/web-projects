<?php
/**
 * My Account - Edit Addresses - OwizoTech
 */
defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

// Safe address types without calling undefined functions
$address_types = [
    'billing'  => [
        'title' => __('Billing Address', 'owizotech'),
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
    ],
    'shipping' => [
        'title' => __('Shipping Address', 'owizotech'),
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>',
    ],
];
?>

<div class="account-addresses-page">
    <div class="account-section-header">
        <h2 class="account-section-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            <?php _e('My Addresses', 'owizotech'); ?>
        </h2>
    </div>
    <p class="addresses-desc"><?php _e('The following addresses will be used on the checkout page by default.', 'owizotech'); ?></p>

    <div class="addresses-grid">
        <?php foreach ( $address_types as $name => $data ) :
            $address = wc_get_account_formatted_address( $name, $customer_id );
            $edit_url = wc_get_account_endpoint_url('edit-address') . $name . '/';
        ?>
        <div class="address-card card">
            <div class="address-card-header">
                <h3 class="address-card-title">
                    <?php echo $data['icon']; ?>
                    <?php echo esc_html($data['title']); ?>
                </h3>
                <a href="<?php echo esc_url($edit_url); ?>" class="btn btn-ghost btn-sm address-edit-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <?php _e('Edit', 'owizotech'); ?>
                </a>
            </div>
            <div class="address-card-body">
                <?php if ( $address ) : ?>
                    <address><?php echo wp_kses_post($address); ?></address>
                <?php else : ?>
                    <p class="address-empty"><?php _e('No address saved yet.', 'owizotech'); ?></p>
                    <a href="<?php echo esc_url($edit_url); ?>" class="btn btn-secondary btn-sm">
                        <?php _e('Add Address', 'owizotech'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
