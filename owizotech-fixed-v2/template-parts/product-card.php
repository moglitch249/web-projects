<?php
/**
 * Template Part: Product Card
 * Reusable WooCommerce product card with hover effects, wishlist, quick-add.
 *
 * @param WC_Product $args['product'] — WooCommerce product object (optional, falls back to global)
 */

global $product;
if ( isset($args['product']) ) {
    $product = $args['product'];
}
if ( ! $product instanceof WC_Product ) {
    $product = wc_get_product( get_the_ID() );
}
if ( ! $product ) return;

$product_id    = $product->get_id();
$product_url   = $product->get_permalink();
$product_name  = $product->get_name();
$price_html    = $product->get_price_html();
$avg_rating    = $product->get_average_rating();
$review_count  = $product->get_review_count();
$is_on_sale    = $product->is_on_sale();
$is_new        = ( ( time() - strtotime( $product->get_date_created() ) ) < (30 * DAY_IN_SECONDS) );
$is_featured   = $product->is_featured();
$in_stock      = $product->is_in_stock();
$img_id        = $product->get_image_id();
$img_url       = $img_id ? wp_get_attachment_image_url($img_id, 'owizo-product-card') : wc_placeholder_img_src('owizo-product-card');
$img_2x        = $img_id ? wp_get_attachment_image_url($img_id, 'owizo-product-single') : '';

// Discount percentage
$discount = '';
if ( $is_on_sale && $product->get_regular_price() ) {
    $discount = round( ($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price() * 100 );
}
?>
<div class="product-card card" data-product-id="<?php echo esc_attr($product_id); ?>">

    <!-- Product Image -->
    <div class="product-card-img-wrap">
        <a href="<?php echo esc_url($product_url); ?>" class="product-card-img-link" aria-label="<?php echo esc_attr($product_name); ?>">
            <img
                src="<?php echo esc_url($img_url); ?>"
                <?php if ($img_2x) echo 'srcset="' . esc_attr($img_url) . ' 1x, ' . esc_attr($img_2x) . ' 2x"'; ?>
                alt="<?php echo esc_attr($product_name); ?>"
                class="product-card-img"
                loading="lazy"
                width="400"
                height="400"
            >
        </a>

        <!-- Badges -->
        <div class="product-badges">
            <?php if ($is_on_sale && $discount) : ?>
                <span class="badge badge-sale">-<?php echo esc_html($discount); ?>%</span>
            <?php elseif ($is_new) : ?>
                <span class="badge badge-new"><?php _e('NEW', 'owizotech'); ?></span>
            <?php elseif ($is_featured) : ?>
                <span class="badge badge-featured"><?php _e('FEATURED', 'owizotech'); ?></span>
            <?php endif; ?>
            <?php if ( ! $in_stock ) : ?>
                <span class="badge badge-out"><?php _e('Out of Stock', 'owizotech'); ?></span>
            <?php endif; ?>
        </div>

        <!-- Quick Actions (hover) -->
        <div class="product-quick-actions" role="group" aria-label="<?php esc_attr_e('Quick actions', 'owizotech'); ?>">
            <?php if ( class_exists('YITH_WCWL') ) : ?>
            <button
                class="quick-action-btn wishlist-btn"
                data-product-id="<?php echo esc_attr($product_id); ?>"
                aria-label="<?php esc_attr_e('Add to Wishlist', 'owizotech'); ?>"
                aria-pressed="false"
            >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </button>
            <?php endif; ?>

            <a
                href="<?php echo esc_url($product_url); ?>"
                class="quick-action-btn quickview-btn"
                aria-label="<?php esc_attr_e('Quick View', 'owizotech'); ?>"
            >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </a>
        </div>
    </div>

    <!-- Product Info -->
    <div class="product-card-body">

        <!-- Category -->
        <?php
        $terms = get_the_terms($product_id, 'product_cat');
        if ($terms && ! is_wp_error($terms)) : ?>
        <a href="<?php echo esc_url(get_term_link($terms[0])); ?>" class="product-category">
            <?php echo esc_html($terms[0]->name); ?>
        </a>
        <?php endif; ?>

        <!-- Name -->
        <h3 class="product-title">
            <a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_name); ?></a>
        </h3>

        <!-- Rating -->
        <?php if ($review_count > 0) : ?>
        <div class="product-rating" aria-label="<?php echo sprintf(esc_attr__('Rating: %s out of 5', 'owizotech'), $avg_rating); ?>">
            <?php echo owizo_stars(round($avg_rating)); ?>
            <span class="product-review-count">(<?php echo esc_html($review_count); ?>)</span>
        </div>
        <?php endif; ?>

        <!-- Price + Add to Cart -->
        <div class="product-card-footer">
            <div class="product-price"><?php echo $price_html; ?></div>

            <?php if ($in_stock) : ?>
                <?php if ($product->is_type('simple')) : ?>
                <button
                    class="btn btn-primary btn-sm add-to-cart-btn"
                    data-product-id="<?php echo esc_attr($product_id); ?>"
                    data-nonce="<?php echo wp_create_nonce('add-to-cart-' . $product_id); ?>"
                    aria-label="<?php echo esc_attr(sprintf(__('Add %s to cart', 'owizotech'), $product_name)); ?>"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span><?php _e('Add', 'owizotech'); ?></span>
                </button>
                <?php else : ?>
                <a href="<?php echo esc_url($product_url); ?>" class="btn btn-secondary btn-sm">
                    <?php _e('View Options', 'owizotech'); ?>
                </a>
                <?php endif; ?>
            <?php else : ?>
            <span class="out-of-stock-label"><?php _e('Out of Stock', 'owizotech'); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
