<?php
/**
 * search.php — Search Results Template
 * يعرض نتائج البحث للمنتجات والمقالات معاً.
 */
get_header(); ?>

<div class="search-results-page">

    <!-- Search Header -->
    <div class="inner-page-header">
        <div class="container">
            <?php owizo_breadcrumbs(); ?>
            <div class="search-results-header">
                <h1 class="inner-page-title">
                    <?php
                    if ( get_search_query() ) {
                        printf(
                            __('Results for: <span class="search-query">"%s"</span>', 'owizotech'),
                            esc_html( get_search_query() )
                        );
                    } else {
                        _e('Search Results', 'owizotech');
                    }
                    ?>
                </h1>
                <?php if ( have_posts() ) : ?>
                <p class="search-count">
                    <?php printf(
                        _n('Found %d result', 'Found %d results', $wp_query->found_posts, 'owizotech'),
                        $wp_query->found_posts
                    ); ?>
                </p>
                <?php endif; ?>
            </div>

            <!-- New Search Form -->
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-results-form">
                <div class="search-results-input-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input
                        type="search"
                        name="s"
                        value="<?php echo esc_attr(get_search_query()); ?>"
                        placeholder="<?php esc_attr_e('Search products, articles...', 'owizotech'); ?>"
                        class="search-results-input"
                        autofocus
                    >
                    <button type="submit" class="btn btn-primary btn-sm">
                        <?php _e('Search', 'owizotech'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="container section">

        <?php if ( have_posts() ) : ?>

        <!-- Separate Products from Posts -->
        <?php
        $product_results = [];
        $post_results    = [];

        while ( have_posts() ) : the_post();
            if ( get_post_type() === 'product' ) {
                $product_results[] = get_the_ID();
            } else {
                $post_results[] = get_the_ID();
            }
        endwhile;
        ?>

        <!-- Products Section -->
        <?php if ( ! empty($product_results) ) : ?>
        <div class="search-section">
            <div class="section-header flex-between">
                <div>
                    <span class="section-label"><?php _e('Products', 'owizotech'); ?></span>
                    <h2 class="section-title"><?php printf( __('%d Products Found', 'owizotech'), count($product_results) ); ?></h2>
                </div>
                <a href="<?php echo esc_url( add_query_arg(['s' => get_search_query(), 'post_type' => 'product'], home_url('/')) ); ?>" class="btn btn-secondary btn-sm">
                    <?php _e('View All Products', 'owizotech'); ?>
                </a>
            </div>
            <div class="products-grid grid grid-4">
                <?php foreach ( $product_results as $pid ) :
                    $product = wc_get_product($pid);
                    if ($product) :
                        owizo_part('product-card', '', ['product' => $product]);
                    endif;
                endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Blog Posts Section -->
        <?php if ( ! empty($post_results) ) : ?>
        <div class="search-section" style="margin-top: var(--space-2xl);">
            <div class="section-header">
                <span class="section-label"><?php _e('Articles', 'owizotech'); ?></span>
                <h2 class="section-title"><?php printf( __('%d Articles Found', 'owizotech'), count($post_results) ); ?></h2>
            </div>
            <div class="blog-grid grid grid-3">
                <?php foreach ( $post_results as $pid ) :
                    $post = get_post($pid);
                    setup_postdata($post); ?>
                <article class="blog-card card">
                    <?php if ( has_post_thumbnail($pid) ) : ?>
                    <a href="<?php echo esc_url(get_permalink($pid)); ?>" class="blog-card-img">
                        <?php echo get_the_post_thumbnail($pid, 'owizo-thumb', ['loading' => 'lazy']); ?>
                    </a>
                    <?php endif; ?>
                    <div class="blog-card-body">
                        <div class="blog-meta">
                            <time datetime="<?php echo get_the_date('Y-m-d', $post); ?>"><?php echo get_the_date('', $post); ?></time>
                        </div>
                        <h3><a href="<?php echo esc_url(get_permalink($pid)); ?>"><?php echo esc_html(get_the_title($pid)); ?></a></h3>
                        <p><?php echo wp_trim_words(get_the_excerpt(), 16); ?></p>
                        <a href="<?php echo esc_url(get_permalink($pid)); ?>" class="blog-read-more">
                            <?php _e('Read More', 'owizotech'); ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </article>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php endif; ?>

        <?php else : ?>
        <!-- No Results -->
        <div class="search-no-results">
            <div class="empty-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </div>
            <h2><?php _e('No results found', 'owizotech'); ?></h2>
            <p>
                <?php printf(
                    __('No results for <strong>"%s"</strong>. Try different keywords or browse our shop.', 'owizotech'),
                    esc_html( get_search_query() )
                ); ?>
            </p>
            <div class="no-results-actions">
                <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-primary">
                    <?php _e('Browse All Products', 'owizotech'); ?>
                </a>
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-secondary">
                    <?php _e('Back to Home', 'owizotech'); ?>
                </a>
            </div>

            <!-- Search Suggestions -->
            <div class="search-suggestions-static">
                <p class="suggestions-label"><?php _e('Popular Searches:', 'owizotech'); ?></p>
                <?php
                $suggestions = ['iPhone', 'Laptop', 'Samsung', 'Headphones', 'MacBook', 'Gaming'];
                foreach ($suggestions as $s) :
                    $url = add_query_arg('s', urlencode($s), home_url('/'));
                ?>
                <a href="<?php echo esc_url($url); ?>" class="search-suggestion-tag"><?php echo esc_html($s); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
