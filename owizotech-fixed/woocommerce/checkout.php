<?php
/**
 * WooCommerce: Checkout Page
 */
get_header(); ?>

<div class="woocommerce-checkout-page">

    <div class="inner-page-header">
        <div class="container">
            <?php owizo_breadcrumbs(); ?>
            <h1 class="inner-page-title"><?php _e('Checkout', 'owizotech'); ?></h1>
        </div>
    </div>

    <!-- Checkout Steps Indicator -->
    <div class="checkout-steps">
        <div class="container">
            <div class="steps-track">
                <div class="step completed">
                    <span class="step-num">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    <span class="step-label"><?php _e('Cart', 'owizotech'); ?></span>
                </div>
                <div class="step-line completed"></div>
                <div class="step active">
                    <span class="step-num">2</span>
                    <span class="step-label"><?php _e('Details', 'owizotech'); ?></span>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <span class="step-num">3</span>
                    <span class="step-label"><?php _e('Confirm', 'owizotech'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="container section">
        <?php woocommerce_output_all_notices(); ?>

        <?php if ( WC()->cart->is_empty() ) : ?>
        <div class="cart-empty">
            <p><?php _e('Your cart is empty. Please add products before checking out.', 'owizotech'); ?></p>
            <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-primary">
                <?php _e('Browse Products', 'owizotech'); ?>
            </a>
        </div>
        <?php else : ?>

        <div class="checkout-layout">

            <!-- CHECKOUT FORM -->
            <div class="checkout-form-col">
                <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                <form name="checkout" method="post" class="checkout woocommerce-checkout"
                    action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                    enctype="multipart/form-data">

                    <!-- Returning Customer Login -->
                    <?php if ( ! is_user_logged_in() && 'yes' === get_option('woocommerce_enable_checkout_login_reminder') ) : ?>
                    <div class="checkout-returning-customer">
                        <p class="returning-notice">
                            <?php _e('Returning customer?', 'owizotech'); ?>
                            <a href="#" class="show-login-toggle"><?php _e('Click here to login', 'owizotech'); ?></a>
                        </p>
                    </div>
                    <?php endif; ?>

                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                    <div id="customer_details">

                        <!-- Billing Fields -->
                        <div class="checkout-section card">
                            <h3 class="checkout-section-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <?php _e('Billing Details', 'owizotech'); ?>
                            </h3>
                            <?php do_action('woocommerce_checkout_billing'); ?>
                        </div>

                        <!-- Ship to Different Address -->
                        <div class="checkout-section card">
                            <h3 class="checkout-section-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                                <?php _e('Shipping Address', 'owizotech'); ?>
                            </h3>
                            <?php do_action('woocommerce_checkout_shipping'); ?>
                        </div>

                    </div><!-- #customer_details -->

                    <!-- Order Notes -->
                    <div class="checkout-section card">
                        <h3 class="checkout-section-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                            <?php _e('Additional Information', 'owizotech'); ?>
                        </h3>
                        <?php do_action('woocommerce_before_order_notes', WC()->checkout() ); ?>
                        <?php if ( apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes')) ) : ?>
                        <div class="woocommerce-additional-fields__field-wrapper">
                            <p class="form-row notes" id="order_comments_field">
                                <label for="order_comments"><?php _e('Order notes', 'owizotech'); ?></label>
                                <textarea name="order_comments" id="order_comments" rows="3"
                                    placeholder="<?php esc_attr_e('Notes about your order, e.g. special delivery instructions.', 'owizotech'); ?>"
                                    class="input-text"></textarea>
                            </p>
                        </div>
                        <?php endif; ?>
                        <?php do_action('woocommerce_after_order_notes', WC()->checkout() ); ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                    <!-- Payment Section -->
                    <div class="checkout-section card" id="payment-section">
                        <h3 class="checkout-section-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            <?php _e('Payment Method', 'owizotech'); ?>
                        </h3>
                        <?php do_action('woocommerce_checkout_payment'); ?>
                    </div>

                </form>
            </div>

            <!-- ORDER SUMMARY -->
            <div class="checkout-summary-col">
                <div class="checkout-order-summary card">
                    <h3 class="summary-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <?php _e('Your Order', 'owizotech'); ?>
                        <span class="summary-item-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </h3>

                    <!-- Mini Cart Items -->
                    <div class="order-summary-items">
                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                            $product  = $cart_item['data'];
                            $quantity = $cart_item['quantity'];
                            if ( ! $product || ! $product->exists() ) continue;
                            $img_url = wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) ?: wc_placeholder_img_src();
                        ?>
                        <div class="order-summary-item">
                            <div class="order-item-img">
                                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" width="56" height="56" loading="lazy">
                                <span class="order-item-qty"><?php echo esc_html($quantity); ?></span>
                            </div>
                            <div class="order-item-info">
                                <span class="order-item-name"><?php echo esc_html($product->get_name()); ?></span>
                            </div>
                            <div class="order-item-price">
                                <?php echo WC()->cart->get_product_subtotal( $product, $quantity ); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Totals -->
                    <div class="order-summary-totals">
                        <div class="total-row">
                            <span><?php _e('Subtotal', 'owizotech'); ?></span>
                            <span><?php wc_cart_totals_subtotal_html(); ?></span>
                        </div>
                        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                        <div class="total-row total-row--discount">
                            <span><?php printf( __('Coupon: %s', 'owizotech'), esc_html($code) ); ?></span>
                            <span>-<?php wc_cart_totals_coupon_html($coupon); ?></span>
                        </div>
                        <?php endforeach; ?>
                        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                        <div class="total-row">
                            <span><?php echo esc_html($fee->name); ?></span>
                            <span><?php echo wc_price($fee->total); ?></span>
                        </div>
                        <?php endforeach; ?>
                        <?php if ( WC()->cart->needs_shipping() ) : ?>
                        <div class="total-row">
                            <span><?php _e('Shipping', 'owizotech'); ?></span>
                            <span>
                                <?php
                                $packages = WC()->shipping()->get_packages();
                                if ( ! empty($packages) ) {
                                    wc_cart_totals_shipping_html();
                                }
                                ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        <?php if ( WC()->cart->get_taxes_total() > 0 ) : ?>
                        <div class="total-row">
                            <span><?php _e('Tax', 'owizotech'); ?></span>
                            <span><?php echo wc_price( WC()->cart->get_taxes_total() ); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="total-row total-row--final">
                            <span><?php _e('Total', 'owizotech'); ?></span>
                            <span><?php wc_cart_totals_order_total_html(); ?></span>
                        </div>
                    </div>

                    <!-- Security badges -->
                    <div class="checkout-trust">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <?php _e('SSL encrypted & secure checkout', 'owizotech'); ?>
                    </div>
                </div>

                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="continue-shopping-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                    <?php _e('Back to Cart', 'owizotech'); ?>
                </a>
            </div>

        </div><!-- .checkout-layout -->

        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
