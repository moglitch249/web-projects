<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="owizo-page" class="site-wrapper">

<!-- ══════════════════════════════════════
     TOPBAR
══════════════════════════════════════ -->
<div class="topbar" id="owizo-topbar">
    <div class="container">
        <div class="topbar-inner">

            <!-- Left: Promo message -->
            <div class="topbar-left">
                <div class="topbar-marquee">
                    <span class="glow-dot"></span>
                    <p class="topbar-text">
                        <?php echo esc_html( owizo_option('owizo_topbar_text', __('🚀 Free shipping on orders over $99 &nbsp;|&nbsp; 24/7 Tech Support &nbsp;|&nbsp; Free Returns within 30 days', 'owizotech')) ); ?>
                    </p>
                </div>
            </div>

            <!-- Right: Language / Currency / Links -->
            <div class="topbar-right">
                <a href="#" class="topbar-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    <span><?php _e('EN', 'owizotech'); ?></span>
                </a>
                <span class="topbar-sep">|</span>
                <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="topbar-link">
                    <?php _e('My Account', 'owizotech'); ?>
                </a>
                <span class="topbar-sep">|</span>
                <a href="tel:+1800000000" class="topbar-link">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.29 6.29l1.17-.87a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    +1 (800) 000-000
                </a>
            </div>

        </div>
    </div>
</div>

<!-- ══════════════════════════════════════
     MAIN HEADER
══════════════════════════════════════ -->
<header class="site-header" id="owizo-header" role="banner">
    <div class="container">
        <div class="header-inner">

            <!-- LOGO -->
            <div class="site-brand">
                <?php
                $owizo_logo     = get_theme_mod('owizo_logo_image', '');
                $owizo_height   = (int) get_theme_mod('owizo_logo_height', 48);
                $owizo_showname = get_theme_mod('owizo_show_site_name', '1');
                ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="brand-link" rel="home" aria-label="<?php bloginfo('name'); ?>">
                    <?php if ( $owizo_logo ) : ?>
                        <img src="<?php echo esc_url($owizo_logo); ?>"
                             alt="<?php bloginfo('name'); ?>"
                             height="<?php echo esc_attr($owizo_height); ?>"
                             style="height:<?php echo esc_attr($owizo_height); ?>px;width:auto;display:block;"
                             loading="eager">
                    <?php elseif ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <span class="brand-icon">
                            <svg width="32" height="32" viewBox="0 0 40 40" fill="none">
                                <rect width="40" height="40" rx="10" fill="url(#logo-grad)"/>
                                <path d="M12 20l5 5 11-11" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="logo-grad" x1="0" y1="0" x2="40" y2="40">
                                        <stop offset="0%" stop-color="#00C8FF"/>
                                        <stop offset="100%" stop-color="#7B5CF6"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </span>
                    <?php endif; ?>
                    <?php if ( $owizo_showname || ( ! $owizo_logo && ! has_custom_logo() ) ) : ?>
                    <span class="brand-text">
                        <span class="brand-name"><?php bloginfo('name'); ?></span>
                    </span>
                    <?php endif; ?>
                </a>
            </div>

            <!-- MAIN NAVIGATION -->
            <nav class="main-nav" id="site-navigation" aria-label="<?php _e('Primary Navigation', 'owizotech'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'nav-menu',
                    'fallback_cb'    => false,
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'walker'         => class_exists('Owizo_Walker_Nav_Menu') ? new Owizo_Walker_Nav_Menu() : null,
                ]);
                ?>
            </nav>

            <!-- HEADER ACTIONS -->
            <div class="header-actions">

                <!-- Search trigger -->
                <button class="action-btn" id="search-toggle" aria-label="<?php _e('Search', 'owizotech'); ?>" aria-expanded="false">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </button>

                <?php if ( is_user_logged_in() ) : ?>
                <!-- Account -->
                <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="action-btn" aria-label="<?php _e('My Account', 'owizotech'); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
                <?php else : ?>
                <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="action-btn" aria-label="<?php _e('Login', 'owizotech'); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10,17 15,12 10,7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                </a>
                <?php endif; ?>

                <!-- Wishlist -->
                <?php if ( class_exists('YITH_WCWL') ) : ?>
                <a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>" class="action-btn" aria-label="<?php _e('Wishlist', 'owizotech'); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </a>
                <?php endif; ?>

                <!-- Cart -->
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="action-btn cart-btn" id="cart-trigger" aria-label="<?php _e('Cart', 'owizotech'); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span class="cart-count <?php echo WC()->cart->get_cart_contents_count() > 0 ? 'has-items' : ''; ?>">
                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                    </span>
                </a>

                <!-- Mobile hamburger -->
                <button class="hamburger" id="menu-toggle" aria-label="<?php _e('Menu', 'owizotech'); ?>" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>

            </div>
        </div>
    </div>

    <!-- SEARCH OVERLAY -->
    <div class="search-overlay" id="search-overlay" role="dialog" aria-label="<?php _e('Search', 'owizotech'); ?>" aria-hidden="true">
        <div class="container">
            <div class="search-box">
                <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
                    <input
                        type="search"
                        name="s"
                        id="site-search"
                        placeholder="<?php esc_attr_e('Search for products, brands, categories...', 'owizotech'); ?>"
                        autocomplete="off"
                        class="search-input"
                    >
                    <input type="hidden" name="post_type" value="product">
                    <button type="submit" class="search-submit" aria-label="<?php _e('Search', 'owizotech'); ?>">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </button>
                    <button type="button" class="search-close" id="search-close" aria-label="<?php _e('Close search', 'owizotech'); ?>">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </form>
                <div class="search-suggestions" id="search-suggestions" aria-live="polite"></div>
            </div>
        </div>
    </div>

</header>

<!-- MOBILE DRAWER -->
<div class="mobile-drawer" id="mobile-drawer" aria-hidden="true" role="dialog">
    <div class="drawer-inner">
        <div class="drawer-header">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="brand-link">
                <span class="brand-name">Owizo<span class="brand-accent">Tech</span></span>
            </a>
            <button class="drawer-close" id="drawer-close" aria-label="<?php _e('Close menu', 'owizotech'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="drawer-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'mobile',
                'container'      => false,
                'menu_class'     => 'drawer-menu',
                'fallback_cb'    => false,
            ]);
            ?>
        </nav>

        <div class="drawer-footer">
            <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="btn btn-secondary btn-sm">
                <?php _e('My Account', 'owizotech'); ?>
            </a>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-primary btn-sm">
                <?php _e('Cart', 'owizotech'); ?>
                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
            </a>
        </div>
    </div>
</div>
<div class="overlay-backdrop" id="backdrop"></div>

<!-- MAIN CONTENT START -->
<main id="main-content" class="site-main" role="main">
