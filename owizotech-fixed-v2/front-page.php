<?php
/**
 * Front Page Template — Homepage Landing
 */
get_header(); ?>

<div class="front-page">

    <!-- ══════════ HERO SECTION ══════════ -->
    <?php owizo_part('hero'); ?>

    <!-- ══════════ BRANDS STRIP ══════════ -->
    <section class="brands-strip section--sm">
        <div class="container">
            <p class="brands-label"><?php _e('TRUSTED BRANDS', 'owizotech'); ?></p>
            <div class="brands-track" aria-label="<?php esc_attr_e('Trusted technology brands', 'owizotech'); ?>">
                <?php
                $brands = ['Apple','Samsung','Sony','Dell','HP','Lenovo','ASUS','LG','Xiaomi','Bose'];
                foreach ($brands as $brand) {
                    echo '<div class="brand-item">' . esc_html($brand) . '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- ══════════ FEATURED CATEGORIES ══════════ -->
    <section class="section featured-categories">
        <div class="container">
            <div class="section-header flex-between">
                <div>
                    <span class="section-label"><?php _e('Shop by Category', 'owizotech'); ?></span>
                    <h2 class="section-title"><?php _e('Find What You Need', 'owizotech'); ?></h2>
                </div>
                <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-secondary">
                    <?php _e('All Categories', 'owizotech'); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <?php
            $cats = get_terms([
                'taxonomy'   => 'product_cat',
                'number'     => 8,
                'hide_empty' => true,
                'exclude'    => [get_option('default_product_cat')],
                'orderby'    => 'count',
                'order'      => 'DESC',
            ]);

            if ( ! empty($cats) && ! is_wp_error($cats) ) : ?>
            <div class="categories-grid">
                <?php foreach ( $cats as $i => $cat ) :
                    $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                    $img_url      = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'owizo-square' ) : wc_placeholder_img_src('owizo-square');
                    $cat_url      = get_term_link($cat);
                    $is_large     = ($i === 0); // First item is large
                ?>
                <a href="<?php echo esc_url($cat_url); ?>" class="category-card <?php echo $is_large ? 'category-card--large' : ''; ?>">
                    <div class="category-card-img">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" loading="lazy">
                    </div>
                    <div class="category-card-overlay">
                        <div class="category-card-content">
                            <h3 class="category-name"><?php echo esc_html($cat->name); ?></h3>
                            <span class="category-count"><?php echo sprintf(_n('%d product', '%d products', $cat->count, 'owizotech'), $cat->count); ?></span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ══════════ PROMO BANNER ══════════ -->
    <section class="section--sm promo-banner-section">
        <div class="container">
            <div class="promo-banners-grid">
                <div class="promo-banner promo-banner--primary">
                    <div class="promo-content">
                        <span class="badge badge-sale"><?php _e('SALE', 'owizotech'); ?></span>
                        <h3><?php _e('Up to 40% off', 'owizotech'); ?></h3>
                        <p><?php _e('On all laptops & notebooks this week only.', 'owizotech'); ?></p>
                        <a href="<?php echo esc_url( wc_get_page_permalink('shop') . '?on_sale=1' ); ?>" class="btn btn-primary btn-sm">
                            <?php _e('Shop Now', 'owizotech'); ?>
                        </a>
                    </div>
                </div>
                <div class="promo-banner promo-banner--secondary">
                    <div class="promo-content">
                        <span class="badge badge-new"><?php _e('NEW', 'owizotech'); ?></span>
                        <h3><?php _e('Latest Smartphones', 'owizotech'); ?></h3>
                        <p><?php _e('Discover the newest flagships from top brands.', 'owizotech'); ?></p>
                        <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-secondary btn-sm">
                            <?php _e('Explore', 'owizotech'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════ FEATURED PRODUCTS ══════════ -->
    <section class="section featured-products-section">
        <div class="container">
            <div class="section-header flex-between">
                <div>
                    <span class="section-label"><?php _e('Featured', 'owizotech'); ?></span>
                    <h2 class="section-title"><?php _e('Top Picks This Week', 'owizotech'); ?></h2>
                </div>
                <!-- Product filter tabs -->
                <div class="product-tabs" role="tablist">
                    <button class="product-tab active" data-filter="featured" role="tab" aria-selected="true"><?php _e('Featured', 'owizotech'); ?></button>
                    <button class="product-tab" data-filter="new" role="tab" aria-selected="false"><?php _e('New', 'owizotech'); ?></button>
                    <button class="product-tab" data-filter="sale" role="tab" aria-selected="false"><?php _e('On Sale', 'owizotech'); ?></button>
                </div>
            </div>

            <?php
            $args = [
                'post_type'      => 'product',
                'posts_per_page' => 8,
                'post_status'    => 'publish',
                'tax_query'      => [
                    ['taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured'],
                ],
            ];
            $products = new WP_Query($args);
            ?>

            <div class="products-grid grid grid-4" id="featured-products-grid">
                <?php if ( $products->have_posts() ) :
                    while ( $products->have_posts() ) : $products->the_post();
                        global $product;
                        owizo_part('product-card', '', ['product' => $product]);
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback: recent products
                    $recent_args = ['post_type' => 'product', 'posts_per_page' => 8, 'post_status' => 'publish'];
                    $recent = new WP_Query($recent_args);
                    while ( $recent->have_posts() ) : $recent->the_post();
                        global $product;
                        owizo_part('product-card', '', ['product' => $product]);
                    endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>

            <div class="section-cta">
                <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-secondary btn-lg">
                    <?php _e('View All Products', 'owizotech'); ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ══════════ STATS BAND ══════════ -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <?php
                $stats = [
                    ['number' => '50K+',  'label' => __('Happy Customers', 'owizotech')],
                    ['number' => '5,000+','label' => __('Products Available', 'owizotech')],
                    ['number' => '120+',  'label' => __('Premium Brands', 'owizotech')],
                    ['number' => '24/7',  'label' => __('Expert Support', 'owizotech')],
                ];
                foreach ($stats as $stat) : ?>
                <div class="stat-item">
                    <span class="stat-number"><?php echo esc_html($stat['number']); ?></span>
                    <span class="stat-label"><?php echo esc_html($stat['label']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ══════════ NEW ARRIVALS ══════════ -->
    <section class="section new-arrivals-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label"><?php _e('Just In', 'owizotech'); ?></span>
                <h2 class="section-title"><?php _e('New Arrivals', 'owizotech'); ?></h2>
                <p class="section-subtitle"><?php _e('The latest tech just landed.', 'owizotech'); ?></p>
            </div>

            <?php
            $new_args = [
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'post_status'    => 'publish',
                'meta_key'       => 'total_sales',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ];
            $new_products = new WP_Query($new_args);
            ?>

            <div class="new-arrivals-layout">
                <?php if ( $new_products->have_posts() ) :
                    $i = 0;
                    while ( $new_products->have_posts() ) : $new_products->the_post();
                        global $product;
                        $is_hero = ($i === 0); ?>
                        <?php if ($is_hero) : ?>
                        <div class="arrival-hero">
                            <?php owizo_part('product-card', 'hero', ['product' => $product]); ?>
                        </div>
                        <?php else : ?>
                        <div class="arrival-item">
                            <?php owizo_part('product-card', '', ['product' => $product]); ?>
                        </div>
                        <?php endif;
                        $i++;
                    endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>
        </div>
    </section>

    <!-- ══════════ TESTIMONIALS ══════════ -->
    <section class="section testimonials-section">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-label"><?php _e('What Customers Say', 'owizotech'); ?></span>
                <h2 class="section-title"><?php _e('Trusted by Thousands', 'owizotech'); ?></h2>
            </div>

            <div class="testimonials-grid grid grid-3">
                <?php
                $testimonials = [
                    ['name' => 'Ahmed K.',    'rating' => 5, 'text' => __('Best tech store I\'ve used. Fast delivery, genuine products, and excellent support team.', 'owizotech'), 'product' => 'MacBook Pro 16"'],
                    ['name' => 'Sarah M.',    'rating' => 5, 'text' => __('The prices are unbeatable and everything arrived in perfect condition. Will definitely order again!', 'owizotech'), 'product' => 'Sony WH-1000XM5'],
                    ['name' => 'Omar Hassan', 'rating' => 5, 'text' => __('Professional service, authentic products, and lightning-fast shipping. Highly recommended!', 'owizotech'), 'product' => 'Samsung Galaxy S24'],
                ];
                foreach ($testimonials as $t) : ?>
                <div class="testimonial-card card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <?php echo esc_html( mb_substr($t['name'], 0, 1) ); ?>
                        </div>
                        <div>
                            <strong class="testimonial-name"><?php echo esc_html($t['name']); ?></strong>
                            <span class="testimonial-product"><?php echo esc_html($t['product']); ?></span>
                        </div>
                        <?php echo owizo_stars($t['rating']); ?>
                    </div>
                    <p class="testimonial-text"><?php echo esc_html($t['text']); ?></p>
                    <div class="testimonial-footer">
                        <span class="verified"><?php _e('✓ Verified Purchase', 'owizotech'); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ══════════ BLOG PREVIEW ══════════ -->
    <?php
    $blog_posts = get_posts(['numberposts' => (int) get_theme_mod('owizo_blog_count', 3), 'post_status' => 'publish']);
    if ( $blog_posts ) :
        $hero_post = $blog_posts[0];
        $side_posts = array_slice($blog_posts, 1);
    ?>
    <section class="section blog-preview-section">
        <div class="container">
            <div class="section-header flex-between">
                <div>
                    <span class="section-label"><?php _e('Tech Blog', 'owizotech'); ?></span>
                    <h2 class="section-title"><?php _e('Latest from Our Blog', 'owizotech'); ?></h2>
                </div>
                <a href="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ); ?>" class="btn btn-secondary">
                    <?php _e('All Posts', 'owizotech'); ?>
                </a>
            </div>

            <div class="blog-showcase">

                <!-- ── المقال الرئيسي بصورة كبيرة ── -->
                <article class="blog-hero-card card">
                    <a href="<?php echo esc_url( get_permalink($hero_post->ID) ); ?>" class="blog-hero-img-wrap">
                        <?php if ( has_post_thumbnail($hero_post->ID) ) : ?>
                            <?php echo get_the_post_thumbnail($hero_post->ID, 'full', ['loading' => 'lazy', 'class' => 'blog-hero-img']); ?>
                        <?php elseif ( $owizo_default_blog_img = get_theme_mod('owizo_blog_default_image', '') ) : ?>
                            <img src="<?php echo esc_url($owizo_default_blog_img); ?>" alt="<?php echo esc_attr(get_the_title($hero_post->ID)); ?>" class="blog-hero-img" loading="lazy">
                        <?php else : ?>
                            <div class="blog-hero-no-img">
                                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>
                            </div>
                        <?php endif; ?>
                        <!-- overlay gradient + category badge -->
                        <div class="blog-hero-overlay">
                            <?php
                            $cats = get_the_category($hero_post->ID);
                            if ($cats) echo '<span class="blog-hero-cat">' . esc_html($cats[0]->name) . '</span>';
                            ?>
                        </div>
                    </a>
                    <div class="blog-hero-body">
                        <div class="blog-meta">
                            <time datetime="<?php echo get_the_date('Y-m-d', $hero_post->ID); ?>"><?php echo get_the_date('', $hero_post->ID); ?></time>
                            <span class="dot">·</span>
                            <span><?php echo ceil(str_word_count(get_post_field('post_content', $hero_post->ID)) / 200) ?: 1; ?> <?php _e('min read', 'owizotech'); ?></span>
                        </div>
                        <h2 class="blog-hero-title">
                            <a href="<?php echo esc_url( get_permalink($hero_post->ID) ); ?>">
                                <?php echo esc_html( get_the_title($hero_post->ID) ); ?>
                            </a>
                        </h2>
                        <p class="blog-hero-excerpt">
                            <?php
                            $excerpt = get_post_field('post_excerpt', $hero_post->ID);
                            if ( ! $excerpt ) $excerpt = get_post_field('post_content', $hero_post->ID);
                            echo wp_trim_words($excerpt, 22);
                            ?>
                        </p>
                        <a href="<?php echo esc_url( get_permalink($hero_post->ID) ); ?>" class="btn btn-primary btn-sm blog-hero-btn">
                            <?php _e('Read Article', 'owizotech'); ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </article>

                <!-- ── المقالات الجانبية ── -->
                <?php if ( $side_posts ) : ?>
                <div class="blog-side-list">
                    <?php foreach ($side_posts as $sp) : ?>
                    <article class="blog-side-card card">
                        <?php if ( has_post_thumbnail($sp->ID) ) : ?>
                        <a href="<?php echo esc_url( get_permalink($sp->ID) ); ?>" class="blog-side-img-wrap">
                            <?php echo get_the_post_thumbnail($sp->ID, 'medium', ['loading' => 'lazy', 'class' => 'blog-side-img']); ?>
                            <?php
                            $sp_cats = get_the_category($sp->ID);
                            if ($sp_cats) echo '<span class="blog-side-cat">' . esc_html($sp_cats[0]->name) . '</span>';
                            ?>
                        </a>
                        <?php endif; ?>
                        <div class="blog-side-body">
                            <div class="blog-meta">
                                <time datetime="<?php echo get_the_date('Y-m-d', $sp->ID); ?>"><?php echo get_the_date('', $sp->ID); ?></time>
                            </div>
                            <h3 class="blog-side-title">
                                <a href="<?php echo esc_url( get_permalink($sp->ID) ); ?>">
                                    <?php echo esc_html( get_the_title($sp->ID) ); ?>
                                </a>
                            </h3>
                            <p class="blog-side-excerpt">
                                <?php
                                $sp_exc = get_post_field('post_excerpt', $sp->ID) ?: get_post_field('post_content', $sp->ID);
                                echo wp_trim_words($sp_exc, 14);
                                ?>
                            </p>
                            <a href="<?php echo esc_url( get_permalink($sp->ID) ); ?>" class="blog-read-more">
                                <?php _e('Read More', 'owizotech'); ?>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div><!-- .blog-showcase -->
        </div>
    </section>
    <?php endif; ?>

</div><!-- .front-page -->

<?php get_footer(); ?>
