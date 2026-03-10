<?php
/**
 * Template Part: Hero Section
 * Full-screen homepage hero with animated gradient, CTA, and product showcase.
 */
?>
<section class="hero-section mesh-bg" aria-label="<?php esc_attr_e('Hero Banner', 'owizotech'); ?>">
    <div class="hero-bg-effects">
        <div class="hero-orb hero-orb--1" aria-hidden="true"></div>
        <div class="hero-orb hero-orb--2" aria-hidden="true"></div>
        <div class="hero-grid-lines" aria-hidden="true"></div>
    </div>

    <div class="container">
        <div class="hero-inner">

            <!-- Hero Content -->
            <div class="hero-content animate-fade-up">
                <div class="hero-badge">
                    <span class="glow-dot"></span>
                    <?php _e('New Collection 2025', 'owizotech'); ?>
                </div>

                <h1 class="hero-title">
                    <?php _e('Power Up Your', 'owizotech'); ?>
                    <span class="text-gradient hero-title-highlight"><?php _e('Digital Life', 'owizotech'); ?></span>
                </h1>

                <p class="hero-subtitle">
                    <?php _e('Discover premium tech — from flagship smartphones to pro laptops. Authentic, fast delivery, expert support.', 'owizotech'); ?>
                </p>

                <div class="hero-actions">
                    <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-primary btn-lg">
                        <?php _e('Shop Now', 'owizotech'); ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                    </a>
                    <a href="<?php echo esc_url( home_url('/deals') ); ?>" class="btn btn-secondary btn-lg">
                        <?php _e("Today's Deals", 'owizotech'); ?>
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="hero-stats">
                    <div class="hero-stat">
                        <strong>50K+</strong>
                        <span><?php _e('Products', 'owizotech'); ?></span>
                    </div>
                    <div class="hero-stat-sep"></div>
                    <div class="hero-stat">
                        <strong>4.9★</strong>
                        <span><?php _e('Rating', 'owizotech'); ?></span>
                    </div>
                    <div class="hero-stat-sep"></div>
                    <div class="hero-stat">
                        <strong><?php _e('24h', 'owizotech'); ?></strong>
                        <span><?php _e('Delivery', 'owizotech'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Hero Visual -->
            <div class="hero-visual animate-fade-up delay-2" aria-hidden="true">
                <div class="hero-device-frame animate-float">
                    <?php
                    $hero_img = get_theme_mod('owizo_hero_image', OWIZO_ASSETS . '/images/hero-device.png');
                    ?>
                    <img
                        src="<?php echo esc_url($hero_img); ?>"
                        alt="<?php esc_attr_e('Featured Product', 'owizotech'); ?>"
                        class="hero-device-img"
                        loading="eager"
                        fetchpriority="high"
                    >
                    <!-- Floating product tags -->
                    <div class="floating-tag floating-tag--1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/></svg>
                        <?php _e('Authentic', 'owizotech'); ?>
                    </div>
                    <div class="floating-tag floating-tag--2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        <?php _e('Fast Delivery', 'owizotech'); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="hero-scroll-hint" aria-hidden="true">
        <div class="scroll-dot"></div>
    </div>
</section>
