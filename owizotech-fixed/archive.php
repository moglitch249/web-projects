<?php
/**
 * archive.php — Archive Template
 * Categories, Tags, Dates, Authors, Custom Taxonomies
 */
get_header(); ?>

<div class="archive-page">

    <!-- Archive Header -->
    <div class="inner-page-header">
        <div class="container">
            <?php owizo_breadcrumbs(); ?>
            <div class="archive-header-content">
                <?php
                $archive_title = get_the_archive_title();
                $archive_desc  = get_the_archive_description();
                ?>
                <h1 class="inner-page-title">
                    <?php echo wp_kses_post($archive_title); ?>
                </h1>
                <?php if ( $archive_desc ) : ?>
                <p class="archive-description"><?php echo wp_kses_post($archive_desc); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container section">

        <?php if ( have_posts() ) : ?>

        <div class="blog-grid grid grid-3">
            <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class('blog-card card'); ?>>

                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="blog-card-img">
                    <?php the_post_thumbnail('owizo-thumb', ['loading' => 'lazy']); ?>
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
                        <span class="blog-read-time">
                            <?php echo ceil(str_word_count(strip_tags(get_the_content())) / 200); ?>
                            <?php _e('min read', 'owizotech'); ?>
                        </span>
                    </div>

                    <h2 class="entry-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>

                    <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>

                    <div class="blog-card-footer">
                        <div class="post-author-mini">
                            <?php echo get_avatar(get_the_author_meta('ID'), 28, '', '', ['class' => 'author-avatar-sm']); ?>
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
                'prev_text' => '←',
                'next_text' => '→',
            ]);
            ?>
        </div>

        <?php else : ?>
        <div class="shop-empty">
            <div class="empty-icon">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h3><?php _e('No posts found.', 'owizotech'); ?></h3>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                <?php _e('Back to Home', 'owizotech'); ?>
            </a>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
