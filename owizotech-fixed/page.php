<?php
/**
 * page.php — Static Pages Template
 * يُستخدم لأي صفحة ثابتة (About, Contact, Policy...)
 */
get_header(); ?>

<div class="page-template">

    <!-- Page Header -->
    <div class="inner-page-header">
        <div class="container">
            <?php owizo_breadcrumbs(); ?>
            <h1 class="inner-page-title"><?php the_title(); ?></h1>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container section">
        <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('page-content'); ?> id="post-<?php the_ID(); ?>">

            <?php if ( has_post_thumbnail() ) : ?>
            <div class="page-featured-img">
                <?php the_post_thumbnail('owizo-hero', ['class' => 'page-hero-img', 'loading' => 'eager']); ?>
            </div>
            <?php endif; ?>

            <div class="page-body entry-content">
                <?php the_content(); ?>
                <?php
                wp_link_pages([
                    'before' => '<div class="page-links">' . __('Pages:', 'owizotech'),
                    'after'  => '</div>',
                ]);
                ?>
            </div>
        </article>
        <?php endwhile; ?>
    </div>

</div>

<?php get_footer(); ?>
