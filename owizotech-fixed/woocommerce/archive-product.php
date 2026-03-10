<?php
/**
 * WooCommerce: Archive Product (Shop Page)
 * Full shop listing with sidebar filters, sorting, and product grid.
 */
get_header();
?>

<div class="shop-page">
    <!-- Page Header -->
    <div class="shop-page-header">
        <div class="container">
            <?php owizo_breadcrumbs(); ?>
            <div class="shop-header-content">
                <h1 class="shop-title">
                    <?php
                    if ( is_product_category() ) {
                        single_term_title();
                    } elseif ( is_product_tag() ) {
                        echo __('Tag: ', 'owizotech') . single_term_title('', false);
                    } elseif ( is_search() ) {
                        echo sprintf( __('Search: "%s"', 'owizotech'), get_search_query() );
                    } else {
                        echo __('All Products', 'owizotech');
                    }
                    ?>
                </h1>
                <?php
                if ( is_product_category() ) {
                    $cat = get_queried_object();
                    if ($cat && $cat->description) {
                        echo '<p class="shop-description">' . esc_html($cat->description) . '</p>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Main Shop Area -->
    <div class="container">
        <div class="shop-layout">

            <!-- SIDEBAR -->
            <aside class="shop-sidebar" id="shop-sidebar" aria-label="<?php esc_attr_e('Shop Filters', 'owizotech'); ?>">
                <div class="sidebar-header">
                    <h2 class="sidebar-title"><?php _e('Filter', 'owizotech'); ?></h2>
                    <button class="sidebar-reset" id="clear-filters" aria-label="<?php esc_attr_e('Clear all filters', 'owizotech'); ?>">
                        <?php _e('Clear All', 'owizotech'); ?>
                    </button>
                </div>

                <!-- Price Filter -->
                <?php the_widget('WC_Widget_Price_Filter'); ?>

                <!-- Category Filter -->
                <?php the_widget('WC_Widget_Product_Categories', ['title' => __('Categories', 'owizotech'), 'count' => 1, 'hierarchical' => 1]); ?>

                <!-- Attribute Filters (Brand, Color, etc.) -->
                <?php
                $attrs = wc_get_attribute_taxonomies();
                foreach ($attrs as $attr) {
                    the_widget('WC_Widget_Layered_Nav', [
                        'title'            => wc_attribute_label($attr->attribute_name),
                        'attribute'        => 'pa_' . $attr->attribute_name,
                        'query_type'       => 'or',
                        'display_type'     => 'list',
                    ]);
                }
                ?>

                <!-- Active Sidebar Widgets (WooCommerce widgets only) -->
                <?php if ( is_active_sidebar('shop-sidebar') ) : ?>
                    <?php dynamic_sidebar('shop-sidebar'); ?>
                <?php endif; ?>
            </aside>

            <!-- PRODUCTS AREA -->
            <div class="shop-products" id="shop-products">

                <!-- Toolbar -->
                <div class="shop-toolbar">
                    <div class="toolbar-left">
                        <button class="sidebar-toggle-btn" id="sidebar-toggle" aria-label="<?php esc_attr_e('Toggle filters', 'owizotech'); ?>" aria-expanded="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="14" y2="12"/><line x1="4" y1="18" x2="18" y2="18"/></svg>
                            <?php _e('Filters', 'owizotech'); ?>
                        </button>

                        <?php if ( woocommerce_result_count() ) : ?>
                        <div class="results-count">
                            <?php woocommerce_result_count(); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="toolbar-right">
                        <!-- View Toggle: grid / list -->
                        <div class="view-toggle" role="group" aria-label="<?php esc_attr_e('View mode', 'owizotech'); ?>">
                            <button class="view-btn active" data-view="grid" aria-label="<?php esc_attr_e('Grid view', 'owizotech'); ?>" aria-pressed="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                            </button>
                            <button class="view-btn" data-view="list" aria-label="<?php esc_attr_e('List view', 'owizotech'); ?>" aria-pressed="false">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            </button>
                        </div>

                        <!-- Sort -->
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                </div>

                <!-- Active Filters Tags -->
                <?php do_action('woocommerce_before_shop_loop'); ?>

                <?php if ( woocommerce_product_loop() ) : ?>
                <div class="products-grid grid grid-auto" id="products-list" aria-live="polite">
                    <?php
                    woocommerce_product_loop_start();

                    if ( wc_get_loop_prop('total') ) {
                        while ( have_posts() ) {
                            the_post();
                            do_action('woocommerce_shop_loop');

                            global $product;
                            get_template_part('template-parts/product-card', '', ['product' => $product]);
                        }
                    }

                    woocommerce_product_loop_end();
                    ?>
                </div>

                <?php do_action('woocommerce_after_shop_loop'); ?>

                <!-- Pagination -->
                <div class="shop-pagination">
                    <?php woocommerce_pagination(); ?>
                </div>

                <?php else : ?>
                <!-- Empty State -->
                <div class="shop-empty">
                    <div class="empty-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </div>
                    <h3><?php _e('No products found', 'owizotech'); ?></h3>
                    <p><?php _e('Try adjusting your filters or search term.', 'owizotech'); ?></p>
                    <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-primary">
                        <?php _e('Clear Filters', 'owizotech'); ?>
                    </a>
                </div>
                <?php endif; ?>

            </div><!-- .shop-products -->

        </div><!-- .shop-layout -->
    </div><!-- .container -->
</div><!-- .shop-page -->

<?php get_footer(); ?>
