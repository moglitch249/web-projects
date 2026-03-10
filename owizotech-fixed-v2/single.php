<?php
/**
 * single.php — Single Blog Post Template
 */
get_header(); ?>

<div class="single-post-page">

    <?php while ( have_posts() ) : the_post(); ?>

    <!-- ══ POST HERO ══ -->
    <div class="post-hero-section">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-hero-bg">
            <?php the_post_thumbnail('owizo-hero', ['class' => 'post-hero-bg-img', 'loading' => 'eager']); ?>
            <div class="post-hero-overlay"></div>
        </div>
        <?php endif; ?>

        <div class="container">
            <div class="post-hero-content">
                <?php owizo_breadcrumbs(); ?>

                <!-- Categories -->
                <?php $cats = get_the_category();
                if ( $cats ) : ?>
                <div class="post-categories">
                    <?php foreach ( $cats as $cat ) : ?>
                    <a href="<?php echo esc_url(get_category_link($cat)); ?>" class="badge badge-accent">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Title -->
                <h1 class="post-title"><?php the_title(); ?></h1>

                <!-- Meta Bar -->
                <div class="post-meta-bar">
                    <div class="post-author">
                        <?php echo get_avatar( get_the_author_meta('ID'), 44, '', '', ['class' => 'author-avatar'] ); ?>
                        <div>
                            <span class="author-name"><?php the_author(); ?></span>
                            <time class="post-date" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </div>
                    </div>
                    <div class="post-read-time">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                        <?php
                        $word_count = str_word_count( strip_tags( get_the_content() ) );
                        echo ceil( $word_count / 200 ) . ' ' . __('min read', 'owizotech');
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ══ MAIN CONTENT ══ -->
    <div class="container">
        <div class="single-post-layout">

            <!-- ARTICLE BODY -->
            <main class="single-post-main">
                <article <?php post_class('single-post-article'); ?> id="post-<?php the_ID(); ?>">

                    <!-- Post Body -->
                    <div class="post-body entry-content">
                        <?php the_content(); ?>
                        <?php
                        wp_link_pages([
                            'before'      => '<div class="page-links">' . __('Pages:', 'owizotech'),
                            'after'       => '</div>',
                            'link_before' => '<span>',
                            'link_after'  => '</span>',
                        ]);
                        ?>
                    </div>

                    <!-- Tags -->
                    <?php $tags = get_the_tags();
                    if ( $tags ) : ?>
                    <div class="post-tags">
                        <span class="tags-label"><?php _e('Tags:', 'owizotech'); ?></span>
                        <?php foreach ( $tags as $tag ) : ?>
                        <a href="<?php echo esc_url(get_tag_link($tag)); ?>" class="post-tag">
                            #<?php echo esc_html($tag->name); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Share -->
                    <div class="post-share">
                        <span><?php _e('Share:', 'owizotech'); ?></span>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="share-btn share-twitter">X</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-facebook">Facebook</a>
                        <a href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-linkedin">LinkedIn</a>
                        <button class="share-btn share-copy" onclick="navigator.clipboard.writeText('<?php echo esc_js(get_permalink()); ?>').then(function(){window.owizoShowToast&&window.owizoShowToast('<?php echo esc_js(__('Link copied!', 'owizotech')); ?>','success')})">
                            <?php _e('Copy Link', 'owizotech'); ?>
                        </button>
                    </div>

                    <!-- Post Navigation -->
                    <?php
                    $prev = get_previous_post();
                    $next = get_next_post();
                    if ( $prev || $next ) : ?>
                    <nav class="post-navigation">
                        <?php if ( $prev ) : ?>
                        <a href="<?php echo esc_url(get_permalink($prev)); ?>" class="post-nav-link post-nav-prev">
                            <span class="nav-direction">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                                <?php _e('Previous', 'owizotech'); ?>
                            </span>
                            <span class="nav-title"><?php echo get_the_title($prev); ?></span>
                        </a>
                        <?php else : ?>
                        <div></div>
                        <?php endif; ?>
                        <?php if ( $next ) : ?>
                        <a href="<?php echo esc_url(get_permalink($next)); ?>" class="post-nav-link post-nav-next">
                            <span class="nav-direction">
                                <?php _e('Next', 'owizotech'); ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                            </span>
                            <span class="nav-title"><?php echo get_the_title($next); ?></span>
                        </a>
                        <?php endif; ?>
                    </nav>
                    <?php endif; ?>

                    <!-- Comments -->
                    <?php if ( comments_open() || get_comments_number() ) : ?>
                    <div class="post-comments">
                        <?php comments_template(); ?>
                    </div>
                    <?php endif; ?>

                </article>
            </main>

            <!-- SIDEBAR -->
            <aside class="post-sidebar">
                <?php if ( is_active_sidebar('blog-sidebar') ) : ?>
                    <?php dynamic_sidebar('blog-sidebar'); ?>
                <?php else : ?>
                    <!-- Author Box -->
                    <div class="sidebar-widget author-widget">
                        <h4 class="widget-title"><?php _e('About the Author', 'owizotech'); ?></h4>
                        <div class="author-box">
                            <?php echo get_avatar( get_the_author_meta('ID'), 64, '', '', ['class' => 'author-box-avatar'] ); ?>
                            <div>
                                <strong class="author-box-name"><?php the_author(); ?></strong>
                                <p class="author-box-bio"><?php echo esc_html( get_the_author_meta('description') ?: __('Content creator at OwizoTech.', 'owizotech') ); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Posts -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title"><?php _e('Related Posts', 'owizotech'); ?></h4>
                        <?php
                        $related = get_posts([
                            'numberposts'  => 4,
                            'category__in' => wp_get_post_categories(get_the_ID()),
                            'post__not_in' => [get_the_ID()],
                        ]);
                        if ( $related ) : ?>
                        <ul class="related-posts-list">
                            <?php foreach ( $related as $rp ) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($rp)); ?>" class="related-post-link">
                                    <?php if ( has_post_thumbnail($rp) ) : ?>
                                    <div class="related-post-img">
                                        <?php echo get_the_post_thumbnail($rp, [60,60]); ?>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <span class="related-post-title"><?php echo esc_html(get_the_title($rp)); ?></span>
                                        <span class="related-post-date"><?php echo get_the_date('', $rp); ?></span>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else : ?>
                        <p style="font-size:0.85rem;color:var(--clr-text-muted)"><?php _e('No related posts.', 'owizotech'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </aside>

        </div><!-- .single-post-layout -->
    </div>

    <?php endwhile; ?>

</div>

<?php get_footer(); ?>
