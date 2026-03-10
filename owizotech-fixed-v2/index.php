<?php
/**
 * index.php — Blog / Posts Listing Template
 */
get_header(); ?>

<div class="archive-page">

    <!-- Blog Hero Header -->
    <div class="archive-hero">
        <div class="container">
            <?php owizo_breadcrumbs(); ?>
            <div class="archive-hero-content">
                <span class="section-label">
                    <?php if ( is_search() ) : ?>
                    <?php _e('Search', 'owizotech'); ?>
                    <?php else : ?>
                    <?php _e('Blog', 'owizotech'); ?>
                    <?php endif; ?>
                </span>
                <h1 class="archive-hero-title">
                    <?php
                    if ( is_home() && ! is_front_page() ) {
                        single_post_title();
                    } elseif ( is_search() ) {
                        printf( __('Results for: &ldquo;%s&rdquo;', 'owizotech'), get_search_query() );
                    } elseif ( is_archive() ) {
                        echo get_the_archive_title();
                    } else {
                        _e('Latest Posts', 'owizotech');
                    }
                    ?>
                </h1>
                <?php if ( is_archive() ) :
                    $desc = get_the_archive_description();
                    if ( $desc ) : ?>
                <p class="archive-hero-desc"><?php echo wp_kses_post($desc); ?></p>
                <?php endif; endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="archive-content-wrap">

            <?php if ( have_posts() ) : ?>

            <?php if ( $wp_query->found_posts ) : ?>
            <div class="archive-count">
                <?php printf( _n('%d post', '%d posts', $wp_query->found_posts, 'owizotech'), $wp_query->found_posts ); ?>
            </div>
            <?php endif; ?>

            <div class="blog-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class('blog-card card'); ?>>

                    <?php
                    $default_img = owizo_option('owizo_blog_default_image', '');
                    if ( has_post_thumbnail() || $default_img ) : ?>
                    <a href="<?php the_permalink(); ?>" class="blog-card-img">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail('owizo-thumb', ['loading' => 'lazy']); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url($default_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                        <?php endif; ?>
                        <?php
                        $cats = get_the_category();
                        if ( $cats ) :
                            echo '<span class="badge badge-accent blog-cat">' . esc_html($cats[0]->name) . '</span>';
                        endif;
                        ?>
                    </a>
                    <?php endif; ?>

                    <div class="blog-card-body">
                        <div class="blog-meta">
                            <time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date(); ?></time>
                            <span class="dot">·</span>
                            <span class="blog-read-time">
                                <?php echo ceil( str_word_count( strip_tags( get_the_content() ) ) / 200 ); ?>
                                <?php _e('min read', 'owizotech'); ?>
                            </span>
                        </div>

                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>

                        <p class="blog-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>

                        <div class="blog-card-footer">
                            <div class="post-author-mini">
                                <?php echo get_avatar( get_the_author_meta('ID'), 28, '', '', ['class' => 'author-avatar-sm'] ); ?>
                                <span><?php the_author(); ?></span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="blog-read-more">
                                <?php _e('Read More', 'owizotech'); ?>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php
                the_posts_pagination([
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                    'mid_size'  => 2,
                ]);
                ?>
            </div>

            <?php else : ?>
            <div class="shop-empty" style="margin:var(--space-3xl) auto;text-align:center;max-width:400px">
                <div class="empty-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <h3><?php _e('Nothing found.', 'owizotech'); ?></h3>
                <p><?php _e('Try searching or browsing our categories.', 'owizotech'); ?></p>
                <?php get_search_form(); ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php get_footer(); ?>
