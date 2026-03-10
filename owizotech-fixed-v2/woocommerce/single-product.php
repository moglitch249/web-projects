<?php
/**
 * WooCommerce: Single Product Page
 * Full product detail with gallery, info, tabs, related products.
 */
get_header();
while ( have_posts() ) : the_post(); global $product; ?>

<div class="single-product-page">
    <div class="container">
        <?php owizo_breadcrumbs(); ?>
    </div>

    <!-- Product Main Section -->
    <div class="container">
        <div class="product-detail-layout">

            <!-- GALLERY -->
            <div class="product-gallery-col">
                <div class="product-gallery" id="product-gallery">
                    <!-- Main image -->
                    <div class="product-gallery-main">
                        <?php
                        $attachment_ids = $product->get_gallery_image_ids();
                        $main_img_id    = $product->get_image_id();
                        $main_img_url   = $main_img_id ? wp_get_attachment_image_url($main_img_id, 'owizo-product-single') : wc_placeholder_img_src();
                        ?>
                        <div class="gallery-main-wrap">
                            <img
                                id="gallery-main-img"
                                src="<?php echo esc_url($main_img_url); ?>"
                                alt="<?php echo esc_attr($product->get_name()); ?>"
                                class="gallery-main-img"
                                loading="eager"
                                fetchpriority="high"
                            >
                            <?php if ($product->is_on_sale()) :
                                $discount = round(($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price() * 100);
                            ?>
                            <span class="badge badge-sale gallery-sale-badge">-<?php echo $discount; ?>%</span>
                            <?php endif; ?>

                            <!-- Zoom overlay hint -->
                            <div class="gallery-zoom-hint" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><path d="M11 8v6M8 11h6"/></svg>
                                <?php _e('Click to zoom', 'owizotech'); ?>
                            </div>
                        </div>

                        <!-- Thumbnails -->
                        <?php if (!empty($attachment_ids)) : ?>
                        <div class="gallery-thumbs" role="list" aria-label="<?php esc_attr_e('Product images', 'owizotech'); ?>">
                            <!-- Main image thumb -->
                            <button
                                class="gallery-thumb active"
                                data-full="<?php echo esc_url($main_img_url); ?>"
                                aria-label="<?php esc_attr_e('Main product image', 'owizotech'); ?>"
                                role="listitem"
                            >
                                <img src="<?php echo esc_url(wp_get_attachment_image_url($main_img_id, 'owizo-product-card')); ?>" alt="" loading="lazy" width="80" height="80">
                            </button>
                            <?php foreach ($attachment_ids as $img_id) :
                                $thumb_url = wp_get_attachment_image_url($img_id, 'owizo-product-card');
                                $full_url  = wp_get_attachment_image_url($img_id, 'owizo-product-single');
                                $alt       = get_post_meta($img_id, '_wp_attachment_image_alt', true);
                            ?>
                            <button
                                class="gallery-thumb"
                                data-full="<?php echo esc_url($full_url); ?>"
                                aria-label="<?php echo esc_attr($alt ?: $product->get_name()); ?>"
                                role="listitem"
                            >
                                <img src="<?php echo esc_url($thumb_url); ?>" alt="" loading="lazy" width="80" height="80">
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- PRODUCT INFO -->
            <div class="product-info-col">
                <div class="product-info">

                    <!-- Category + Brand -->
                    <div class="product-meta-top">
                        <?php
                        $cats = get_the_terms($product->get_id(), 'product_cat');
                        if ($cats && ! is_wp_error($cats)) {
                            echo '<a href="' . esc_url(get_term_link($cats[0])) . '" class="product-category-link">' . esc_html($cats[0]->name) . '</a>';
                        }
                        $brands = get_the_terms($product->get_id(), 'owizo_brand');
                        if ($brands && ! is_wp_error($brands)) {
                            echo '<span class="product-brand">' . esc_html($brands[0]->name) . '</span>';
                        }
                        ?>
                    </div>

                    <!-- Title -->
                    <h1 class="product-detail-title"><?php the_title(); ?></h1>

                    <!-- Rating + Stock -->
                    <div class="product-detail-meta">
                        <?php if ($product->get_review_count() > 0) : ?>
                        <div class="product-rating-summary">
                            <?php echo owizo_stars($product->get_average_rating()); ?>
                            <a href="#reviews" class="review-link">
                                <?php echo sprintf(_n('%d review', '%d reviews', $product->get_review_count(), 'owizotech'), $product->get_review_count()); ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <div class="product-stock-status <?php echo $product->is_in_stock() ? 'in-stock' : 'out-of-stock'; ?>">
                            <span class="stock-dot"></span>
                            <?php echo $product->is_in_stock() ? __('In Stock', 'owizotech') : __('Out of Stock', 'owizotech'); ?>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="product-detail-price">
                        <?php echo $product->get_price_html(); ?>
                        <?php if ($product->is_on_sale() && $product->get_regular_price()) :
                            $savings = $product->get_regular_price() - $product->get_sale_price();
                        ?>
                        <span class="price-savings">
                            <?php echo sprintf(__('Save %s', 'owizotech'), wc_price($savings)); ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Short Description -->
                    <?php if ($product->get_short_description()) : ?>
                    <div class="product-short-desc">
                        <?php echo wpautop(wp_kses_post($product->get_short_description())); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Add to Cart Form -->
                    <div class="product-add-cart">
                        <?php do_action('woocommerce_before_add_to_cart_form'); ?>
                        <?php woocommerce_template_single_add_to_cart(); ?>
                        <?php do_action('woocommerce_after_add_to_cart_form'); ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="product-actions">
                        <?php if (function_exists('YITH_WCWL_Frontend')) : ?>
                        <button class="btn btn-ghost product-wishlist-btn" data-product-id="<?php echo $product->get_id(); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            <?php _e('Wishlist', 'owizotech'); ?>
                        </button>
                        <?php endif; ?>

                        <button class="btn btn-ghost product-share-btn" id="share-product">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                            <?php _e('Share', 'owizotech'); ?>
                        </button>
                    </div>

                    <!-- Trust Badges -->
                    <div class="product-trust-badges">
                        <div class="trust-badge-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <?php _e('100% Authentic', 'owizotech'); ?>
                        </div>
                        <div class="trust-badge-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <?php _e('Free Shipping', 'owizotech'); ?>
                        </div>
                        <div class="trust-badge-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            <?php _e('30-Day Returns', 'owizotech'); ?>
                        </div>
                        <div class="trust-badge-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            <?php _e('Secure Payment', 'owizotech'); ?>
                        </div>
                    </div>

                    <!-- SKU / Category info -->
                    <div class="product-metadata">
                        <?php if ($product->get_sku()) : ?>
                        <span class="meta-item"><strong><?php _e('SKU:', 'owizotech'); ?></strong> <?php echo esc_html($product->get_sku()); ?></span>
                        <?php endif; ?>
                        <?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="meta-item"><strong>' . __('Category:', 'owizotech') . '</strong> ', '</span>'); ?>
                        <?php echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="meta-item"><strong>' . __('Tags:', 'owizotech') . '</strong> ', '</span>'); ?>
                    </div>

                </div>
            </div>

        </div><!-- .product-detail-layout -->
    </div><!-- .container -->

    <!-- PRODUCT TABS (Description, Specs, Reviews) -->
    <div class="container">
        <div class="product-tabs-section">
            <?php woocommerce_output_product_data_tabs(); ?>
        </div>
    </div>

    <!-- RELATED PRODUCTS -->
    <div class="container">
        <div class="related-products-section">
            <?php woocommerce_output_related_products(); ?>
        </div>
    </div>

    <!-- UPSELLS -->
    <div class="container">
        <?php woocommerce_upsell_display(); ?>
    </div>

</div><!-- .single-product-page -->

<?php endwhile; get_footer(); ?>
