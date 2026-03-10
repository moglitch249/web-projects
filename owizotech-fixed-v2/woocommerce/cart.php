<?php
/**
 * WooCommerce: Cart Page
 */
get_header(); ?>

<div class="woocommerce-cart-page">

    <div class="inner-page-header">
        <div class="container">
            <?php owizo_breadcrumbs(); ?>
            <h1 class="inner-page-title"><?php _e('Shopping Cart', 'owizotech'); ?></h1>
        </div>
    </div>

    <div class="container section">
        <?php woocommerce_output_all_notices(); ?>

        <?php if ( WC()->cart->is_empty() ) : ?>

        <div class="cart-empty">
            <div class="empty-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <h2><?php _e('Your cart is empty', 'owizotech'); ?></h2>
            <p><?php _e('Looks like you haven\'t added anything yet.', 'owizotech'); ?></p>
            <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-primary btn-lg">
                <?php _e('Start Shopping', 'owizotech'); ?>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <?php else : ?>

        <div class="cart-layout">

            <!-- CART ITEMS -->
            <div class="cart-items-col">
                <div class="cart-items-header">
                    <h2><?php printf( _n('%d Item', '%d Items', WC()->cart->get_cart_contents_count(), 'owizotech'), WC()->cart->get_cart_contents_count() ); ?></h2>
                    <a href="<?php echo esc_url( wc_get_cart_url() . '?clear-cart' ); ?>" class="cart-clear-btn" id="clear-cart-btn">
                        <?php _e('Clear Cart', 'owizotech'); ?>
                    </a>
                </div>

                <form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" id="cart-form">

                    <div class="cart-items-list">
                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                            $product   = $cart_item['data'];
                            $product_id = $cart_item['product_id'];
                            $variation_id = $cart_item['variation_id'];
                            $quantity  = $cart_item['quantity'];
                            $img_url   = wp_get_attachment_image_url( $product->get_image_id(), 'owizo-product-card' ) ?: wc_placeholder_img_src();

                            if ( ! $product || ! $product->exists() || $quantity <= 0 ) continue;
                        ?>
                        <div class="cart-item" data-key="<?php echo esc_attr($cart_item_key); ?>">

                            <!-- Product Image -->
                            <div class="cart-item-img">
                                <a href="<?php echo esc_url( get_permalink($product_id) ); ?>">
                                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" width="100" height="100" loading="lazy">
                                </a>
                            </div>

                            <!-- Product Details -->
                            <div class="cart-item-details">
                                <a href="<?php echo esc_url( get_permalink($product_id) ); ?>" class="cart-item-name">
                                    <?php echo esc_html( $product->get_name() ); ?>
                                </a>

                                <?php if ( $cart_item['variation_id'] ) :
                                    $variation = wc_get_product( $cart_item['variation_id'] );
                                    if ( $variation ) :
                                        foreach ( $cart_item['variation'] as $attr => $value ) : ?>
                                        <span class="cart-item-attr">
                                            <?php echo esc_html( wc_attribute_label( str_replace('attribute_', '', $attr) ) ); ?>:
                                            <strong><?php echo esc_html( $value ); ?></strong>
                                        </span>
                                        <?php endforeach;
                                    endif;
                                endif; ?>

                                <!-- Quantity (mobile) -->
                                <div class="cart-item-qty-mobile">
                                    <button type="button" class="qty-btn qty-btn-minus" aria-label="<?php esc_attr_e('Decrease', 'owizotech'); ?>">−</button>
                                    <input
                                        type="number"
                                        class="qty input-text"
                                        name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]"
                                        value="<?php echo esc_attr($quantity); ?>"
                                        min="0"
                                        max="<?php echo esc_attr( $product->get_max_purchase_quantity() ?: 9999 ); ?>"
                                        step="1"
                                        aria-label="<?php esc_attr_e('Quantity', 'owizotech'); ?>"
                                    >
                                    <button type="button" class="qty-btn qty-btn-plus" aria-label="<?php esc_attr_e('Increase', 'owizotech'); ?>">+</button>
                                </div>
                            </div>

                            <!-- Quantity (desktop) -->
                            <div class="cart-item-qty">
                                <button type="button" class="qty-btn qty-btn-minus" aria-label="<?php esc_attr_e('Decrease', 'owizotech'); ?>">−</button>
                                <input
                                    type="number"
                                    class="qty input-text"
                                    name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]"
                                    value="<?php echo esc_attr($quantity); ?>"
                                    min="0"
                                    max="<?php echo esc_attr( $product->get_max_purchase_quantity() ?: 9999 ); ?>"
                                    step="1"
                                    aria-label="<?php esc_attr_e('Quantity', 'owizotech'); ?>"
                                >
                                <button type="button" class="qty-btn qty-btn-plus" aria-label="<?php esc_attr_e('Increase', 'owizotech'); ?>">+</button>
                            </div>

                            <!-- Price -->
                            <div class="cart-item-price">
                                <?php echo WC()->cart->get_product_subtotal( $product, $quantity ); ?>
                            </div>

                            <!-- Remove -->
                            <a
                                href="<?php echo esc_url( wc_get_cart_remove_url($cart_item_key) ); ?>"
                                class="cart-item-remove"
                                aria-label="<?php echo esc_attr( sprintf( __('Remove %s from cart', 'owizotech'), $product->get_name() ) ); ?>"
                            >
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Coupon + Update -->
                    <div class="cart-actions">
                        <div class="coupon-form">
                            <input
                                type="text"
                                name="coupon_code"
                                id="coupon_code"
                                placeholder="<?php esc_attr_e('Coupon code', 'owizotech'); ?>"
                                class="coupon-input"
                            >
                            <button type="submit" name="apply_coupon" value="<?php esc_attr_e('Apply', 'owizotech'); ?>" class="btn btn-secondary btn-sm">
                                <?php _e('Apply', 'owizotech'); ?>
                            </button>
                        </div>
                        <button type="submit" name="update_cart" value="<?php esc_attr_e('Update cart', 'owizotech'); ?>" class="btn btn-ghost btn-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1,4 1,10 7,10"/><path d="M3.51 15a9 9 0 1 0 .49-3.8"/></svg>
                            <?php _e('Update Cart', 'owizotech'); ?>
                        </button>
                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                    </div>

                </form>
            </div>

            <!-- ORDER SUMMARY -->
            <div class="cart-summary-col">
                <div class="cart-summary card">
                    <h3 class="summary-title"><?php _e('Order Summary', 'owizotech'); ?></h3>

                    <div class="summary-rows">
                        <div class="summary-row">
                            <span><?php _e('Subtotal', 'owizotech'); ?></span>
                            <span><?php woocommerce_cart_totals(); ?></span>
                        </div>
                    </div>

                    <?php do_action('woocommerce_cart_totals_before_order_total'); ?>

                    <?php woocommerce_cart_totals(); ?>

                    <div class="checkout-btn-wrap">
                        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-primary btn-lg checkout-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <?php _e('Proceed to Checkout', 'owizotech'); ?>
                        </a>
                    </div>

                    <!-- Trust -->
                    <div class="summary-trust">
                        <div class="trust-item-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            <?php _e('Secure checkout', 'owizotech'); ?>
                        </div>
                        <div class="trust-item-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <?php _e('Free returns', 'owizotech'); ?>
                        </div>
                    </div>
                </div>

                <!-- Continue Shopping -->
                <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="continue-shopping-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                    <?php _e('Continue Shopping', 'owizotech'); ?>
                </a>
            </div>

        </div><!-- .cart-layout -->

        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
