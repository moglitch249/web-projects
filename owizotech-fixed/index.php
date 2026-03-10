<?php
/**
 * index.php — Fallback template
 * WordPress يستخدم هذا الملف عند عدم وجود template أكثر تحديداً.
 */
get_header(); ?>

<div class="container section">
    <?php owizo_breadcrumbs(); ?>

    <div class="blog-archive-layout">

        <main class="blog-archive-main">
            <?php if ( have_posts() ) : ?>

            <div class="blog-grid grid grid-3">
                <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class('blog-card card'); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>" class="blog-card-img">
                        <?php the_post_thumbnail('owizo-thumb', ['loading' => 'lazy']); ?>
                    </a>
                    <?php endif; ?>
                    <div class="blog-card-body">
                        <div class="blog-meta">
                            <time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date(); ?></time>
                            <span><?php the_author(); ?></span>
                        </div>
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                        <a href="<?php the_permalink(); ?>" class="blog-read-more">
                            <?php _e('Read More', 'owizotech'); ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php
                the_posts_pagination([
                    'prev_text' => '←',
                    'next_text' => '→',
                    'class'     => 'pagination',
                ]);
                ?>
            </div>

            <?php else : ?>
            <div class="shop-empty">
                <div class="empty-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <h3><?php _e('Nothing found.', 'owizotech'); ?></h3>
                <p><?php _e('Try searching or browsing our categories.', 'owizotech'); ?></p>
                <?php get_search_form(); ?>
            </div>
            <?php endif; ?>
        </main>

    </div>
</div>

<?php get_footer(); ?>
