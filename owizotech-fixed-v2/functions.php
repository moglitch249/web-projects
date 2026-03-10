<?php
/**
 * OwizoTech Theme — functions.php
 * Core theme setup, enqueue scripts/styles, WooCommerce integration, hooks & helpers.
 */

defined('ABSPATH') || exit;

/* =========================================================
   CONSTANTS
   ========================================================= */
define('OWIZO_VERSION',   '1.0.0');
define('OWIZO_DIR',       get_template_directory());
define('OWIZO_URI',       get_template_directory_uri());
define('OWIZO_ASSETS',    OWIZO_URI . '/assets');
define('OWIZO_INC',       OWIZO_DIR . '/inc');

/* =========================================================
   1. THEME SETUP
   ========================================================= */
if ( ! function_exists('owizotech_setup') ) :
function owizotech_setup() {

    load_theme_textdomain( 'owizotech', OWIZO_DIR . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ]);
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );

    // Post formats
    add_theme_support( 'post-formats', ['aside','image','gallery','video','quote','link'] );

    // Custom logo
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => ['site-title','site-description'],
    ]);

    // Custom background
    add_theme_support( 'custom-background', [
        'default-color' => '0A0C10',
    ]);

    // WooCommerce
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width'      => 400,
        'single_image_width'         => 700,
        'product_grid'               => [
            'default_rows'    => 4,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ],
    ]);
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Image sizes
    add_image_size( 'owizo-product-card',   400, 400, true );
    add_image_size( 'owizo-product-single', 700, 700, false );
    add_image_size( 'owizo-hero',           1920, 780, true );
    add_image_size( 'owizo-thumb',          600, 400, true );
    add_image_size( 'owizo-square',         500, 500, true );

    // Navigation menus
    register_nav_menus([
        'primary'    => __( 'Primary Menu',        'owizotech' ),
        'secondary'  => __( 'Secondary Menu',      'owizotech' ),
        'footer-1'   => __( 'Footer Column 1',     'owizotech' ),
        'footer-2'   => __( 'Footer Column 2',     'owizotech' ),
        'footer-3'   => __( 'Footer Column 3',     'owizotech' ),
        'mobile'     => __( 'Mobile Menu',         'owizotech' ),
        'categories' => __( 'Shop Categories',     'owizotech' ),
    ]);
}
endif;
add_action( 'after_setup_theme', 'owizotech_setup' );

/* =========================================================
   1.5. EARLY REST API COMPATIBILITY CHECK
   ========================================================= */

/**
 * Bail out of ALL frontend hooks when a REST API request is being made.
 * This prevents WC conditional functions (is_woocommerce, is_cart, etc.)
 * from running in a context where WP_Query hasn't been set up, which
 * causes PHP notices that corrupt the JSON response.
 */
function owizo_is_rest_request() {
    if ( defined('REST_REQUEST') && REST_REQUEST ) return true;
    // Gutenberg / block editor also checks this path
    $rest_prefix = trailingslashit( rest_get_url_prefix() );
    if ( isset($_SERVER['REQUEST_URI']) && strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) !== false ) return true;
    return false;
}

/* =========================================================
   2. ENQUEUE SCRIPTS & STYLES
   ========================================================= */
function owizotech_enqueue() {
    if ( owizo_is_rest_request() ) return;

    // Google Fonts
    wp_enqueue_style(
        'owizo-fonts',
        'https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=Cairo:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap',
        [], null
    );

    // Theme Style
    wp_enqueue_style( 'owizotech-style', get_stylesheet_uri(), ['owizo-fonts'], OWIZO_VERSION );

    // Main CSS
    wp_enqueue_style( 'owizo-main', OWIZO_ASSETS . '/css/main.css', ['owizotech-style'], OWIZO_VERSION );

    // Shop CSS (only on shop pages)
    if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
        wp_enqueue_style( 'owizo-shop', OWIZO_ASSETS . '/css/shop.css', ['owizo-main'], OWIZO_VERSION );
    }

    // Account CSS (all WooCommerce pages + single posts for comment form fix)
    if ( is_account_page() || is_checkout() || is_cart() || is_woocommerce() || is_singular('post') ) {
        wp_enqueue_style( 'owizo-account', OWIZO_ASSETS . '/css/account.css', ['owizo-shop'], OWIZO_VERSION );
    }

    // Main JS
    wp_enqueue_script( 'owizo-main', OWIZO_ASSETS . '/js/main.js', ['jquery'], OWIZO_VERSION, true );

    // Shop JS
    if ( is_woocommerce() || is_cart() || is_checkout() ) {
        wp_enqueue_script( 'owizo-shop', OWIZO_ASSETS . '/js/shop.js', ['owizo-main'], OWIZO_VERSION, true );
    }

    // Localize
    wp_localize_script( 'owizo-main', 'owizoData', [
        'ajaxUrl'    => admin_url('admin-ajax.php'),
        'nonce'      => wp_create_nonce('owizo_nonce'),
        'siteUrl'    => get_site_url(),
        'isRTL'      => is_rtl(),
        'currency'   => get_woocommerce_currency_symbol(),
        'cartUrl'    => wc_get_cart_url(),
        'cartCount'  => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
        'i18n'       => [
            'addedToCart'  => __( 'Added to cart!',    'owizotech' ),
            'addToCart'    => __( 'Add to Cart',       'owizotech' ),
            'loading'      => __( 'Loading...',        'owizotech' ),
            'error'        => __( 'Something went wrong.', 'owizotech' ),
        ],
    ]);

    // Comments script
    if ( is_singular() && comments_open() && get_option('thread_comments') ) {
        wp_enqueue_script('comment-reply');
    }
}
add_action( 'wp_enqueue_scripts', 'owizotech_enqueue' );

/* =========================================================
   3. WIDGETS & SIDEBARS
   ========================================================= */
function owizotech_widgets_init() {
    $shared_args = [
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
    ];

    register_sidebar( array_merge( $shared_args, [
        'name' => __( 'Shop Sidebar',    'owizotech' ),
        'id'   => 'shop-sidebar',
        'description' => __( 'WooCommerce shop sidebar filters & widgets.', 'owizotech' ),
    ]));
    register_sidebar( array_merge( $shared_args, [
        'name' => __( 'Blog Sidebar',    'owizotech' ),
        'id'   => 'blog-sidebar',
    ]));
    register_sidebar( array_merge( $shared_args, [
        'name' => __( 'Footer Column 1', 'owizotech' ),
        'id'   => 'footer-1',
    ]));
    register_sidebar( array_merge( $shared_args, [
        'name' => __( 'Footer Column 2', 'owizotech' ),
        'id'   => 'footer-2',
    ]));
    register_sidebar( array_merge( $shared_args, [
        'name' => __( 'Footer Column 3', 'owizotech' ),
        'id'   => 'footer-3',
    ]));
    register_sidebar( array_merge( $shared_args, [
        'name' => __( 'Footer Column 4', 'owizotech' ),
        'id'   => 'footer-4',
    ]));
}
add_action( 'widgets_init', 'owizotech_widgets_init' );

/* =========================================================
   4. WOOCOMMERCE CUSTOMIZATIONS
   ========================================================= */

// Remove default WooCommerce styles
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Remove default wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );

// Custom wrappers
if ( ! owizo_is_rest_request() ) {
    add_action( 'woocommerce_before_main_content', 'owizotech_woo_wrapper_start', 10 );
    add_action( 'woocommerce_after_main_content',  'owizotech_woo_wrapper_end',   10 );
}

function owizotech_woo_wrapper_start() {
    echo '<div class="owizo-shop-wrapper"><div class="container">';
}
function owizotech_woo_wrapper_end() {
    echo '</div></div>';
}

// Product columns
add_filter( 'loop_shop_columns', function() { return 4; } );
add_filter( 'loop_shop_per_page', function() { return 16; } );

// Sale badge
add_filter( 'woocommerce_sale_flash', 'owizotech_sale_badge', 10, 3 );
function owizotech_sale_badge( $html, $post, $product ) {
    if ( $product->is_on_sale() ) {
        $percentage = '';
        if ( $product->get_regular_price() ) {
            $percentage = round( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() * 100 );
            return '<span class="badge badge-sale owizo-sale-badge">-' . $percentage . '%</span>';
        }
    }
    return $html;
}

// Remove breadcrumbs (custom ones used)
add_filter( 'woocommerce_breadcrumb_defaults', function( $args ) {
    $args['wrap_before'] = '<nav class="woocommerce-breadcrumb breadcrumbs">';
    $args['wrap_after']  = '</nav>';
    $args['delimiter']   = '<span class="sep">›</span>';
    return $args;
});

// Related products limit
add_filter( 'woocommerce_output_related_products_args', function( $args ) {
    $args['posts_per_page'] = 4;
    $args['columns']        = 4;
    return $args;
});

/* =========================================================
   5. AJAX — MINI CART COUNT UPDATE
   ========================================================= */
add_filter( 'woocommerce_add_to_cart_fragments', 'owizotech_cart_count_fragment' );
function owizotech_cart_count_fragment( $fragments ) {
    $count     = WC()->cart->get_cart_contents_count();
    $has_items = $count > 0 ? ' has-items' : '';
    // نستخدم نفس الـ HTML الموجود في header.php لكي replaceWith تشتغل صح
    $html = '<span class="cart-count' . $has_items . '">' . $count . '</span>';
    $fragments['.cart-count'] = $html;
    return $fragments;
}

/* ─── Mini Cart: Replace "View basket" with "Continue Shopping" ─── */
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
add_action( 'woocommerce_widget_shopping_cart_buttons', 'owizo_mini_cart_continue_shopping', 10 );
function owizo_mini_cart_continue_shopping() {
    if ( ! function_exists('wc_get_page_permalink') ) return;
    echo '<a href="' . esc_url( wc_get_page_permalink('shop') ) . '" class="button wc-forward owizo-continue-btn">'
        . '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg> '
        . esc_html__( 'Continue Shopping', 'owizotech' )
        . '</a>';
}

/* =========================================================
   6. CUSTOM POST TYPES & TAXONOMIES
   ========================================================= */

// Tech Tips / Blog Categories
add_action( 'init', 'owizotech_register_taxonomies' );
function owizotech_register_taxonomies() {
    register_taxonomy( 'owizo_brand', 'product', [
        'label'             => __( 'Brand', 'owizotech' ),
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,
        'rewrite'           => ['slug' => 'brand'],
        'show_admin_column' => true,
    ]);
}

/* =========================================================
   7. CUSTOMIZER SETTINGS
   ========================================================= */
add_action( 'customize_register', 'owizotech_customizer' );
function owizotech_customizer( $wp_customize ) {

    // Panel: OwizoTech Settings
    $wp_customize->add_panel( 'owizo_panel', [
        'title'    => __( 'OwizoTech Settings', 'owizotech' ),
        'priority' => 30,
    ]);

    // Section: Header
    $wp_customize->add_section( 'owizo_header', [
        'title'    => __( 'Header', 'owizotech' ),
        'panel'    => 'owizo_panel',
    ]);

    // ── Logo Image (custom upload — alternative to WP custom logo) ──
    $wp_customize->add_setting( 'owizo_logo_image', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'owizo_logo_image', [
        'label'       => __( 'Site Logo (Upload)', 'owizotech' ),
        'description' => __( 'Upload your logo. Recommended: PNG/SVG, max height 60px. If set, overrides WordPress default logo.', 'owizotech' ),
        'section'     => 'owizo_header',
        'priority'    => 5,
    ]));

    // ── Logo Max Height ──
    $wp_customize->add_setting( 'owizo_logo_height', [
        'default'           => '48',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control( 'owizo_logo_height', [
        'label'       => __( 'Logo Height (px)', 'owizotech' ),
        'section'     => 'owizo_header',
        'type'        => 'number',
        'input_attrs' => [ 'min' => 20, 'max' => 120, 'step' => 1 ],
        'priority'    => 6,
    ]);

    // ── Site Name (shown next to logo) ──
    $wp_customize->add_setting( 'owizo_show_site_name', [
        'default'           => '1',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'owizo_show_site_name', [
        'label'   => __( 'Show Site Name Next to Logo', 'owizotech' ),
        'section' => 'owizo_header',
        'type'    => 'checkbox',
        'priority'=> 7,
    ]);

    // Topbar text
    $wp_customize->add_setting( 'owizo_topbar_text', [
        'default'           => __( 'Free shipping on orders over $99 | 24/7 Support', 'owizotech' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control( 'owizo_topbar_text', [
        'label'   => __( 'Topbar Message', 'owizotech' ),
        'section' => 'owizo_header',
        'type'    => 'text',
    ]);

    // Section: Blog
    $wp_customize->add_section( 'owizo_blog', [
        'title'    => __( 'Blog Settings', 'owizotech' ),
        'panel'    => 'owizo_panel',
    ]);

    // ── Default Blog Post Image ──
    $wp_customize->add_setting( 'owizo_blog_default_image', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'owizo_blog_default_image', [
        'label'       => __( 'Default Blog Image', 'owizotech' ),
        'description' => __( 'Shown when a post has no featured image. Recommended: 1200×630px.', 'owizotech' ),
        'section'     => 'owizo_blog',
    ]));

    // ── Posts per page on homepage ──
    $wp_customize->add_setting( 'owizo_blog_count', [
        'default'           => '3',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'owizo_blog_count', [
        'label'       => __( 'Posts shown on Homepage', 'owizotech' ),
        'section'     => 'owizo_blog',
        'type'        => 'number',
        'input_attrs' => [ 'min' => 1, 'max' => 6 ],
    ]);

    // Section: Colors
    $wp_customize->add_section( 'owizo_colors', [
        'title' => __( 'Brand Colors', 'owizotech' ),
        'panel' => 'owizo_panel',
    ]);
    $wp_customize->add_setting( 'owizo_accent_color', [
        'default'           => '#00C8FF',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'owizo_accent_color', [
        'label'   => __( 'Accent Color', 'owizotech' ),
        'section' => 'owizo_colors',
    ]));

    // Section: Footer
    $wp_customize->add_section( 'owizo_footer', [
        'title' => __( 'Footer', 'owizotech' ),
        'panel' => 'owizo_panel',
    ]);
    $wp_customize->add_setting( 'owizo_footer_copyright', [
        'default'           => '© ' . date('Y') . ' OwizoTech. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control( 'owizo_footer_copyright', [
        'label'   => __( 'Copyright Text', 'owizotech' ),
        'section' => 'owizo_footer',
        'type'    => 'text',
    ]);
}

/* =========================================================
   8. HELPER FUNCTIONS
   ========================================================= */

/**
 * Get customizer value with fallback
 */
function owizo_option( $key, $default = '' ) {
    return get_theme_mod( $key, $default );
}

/**
 * Render SVG icon from assets/images/icons/
 */
function owizo_icon( $name, $class = '', $size = 20 ) {
    $file = OWIZO_DIR . '/assets/images/icons/' . $name . '.svg';
    if ( file_exists($file) ) {
        $svg = file_get_contents( $file );
        if ( $class ) {
            $svg = str_replace( '<svg', '<svg class="owizo-icon ' . esc_attr($class) . '"', $svg );
        }
        return $svg;
    }
    return '';
}

/**
 * Template part loader with data
 */
function owizo_part( $slug, $name = '', $args = [] ) {
    get_template_part( 'template-parts/' . $slug, $name, $args );
}

/**
 * Output breadcrumbs (non-WooCommerce pages)
 */
function owizo_breadcrumbs() {
    if ( function_exists('woocommerce_breadcrumb') && ( is_woocommerce() || is_cart() || is_checkout() ) ) {
        woocommerce_breadcrumb();
        return;
    }

    $sep = '<span class="sep">›</span>';
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'owizotech') . '">';
    echo '<a href="' . esc_url(home_url('/')) . '">' . __('Home', 'owizotech') . '</a>' . $sep;

    if ( is_category() ) {
        echo '<span class="current">' . single_cat_title('', false) . '</span>';
    } elseif ( is_single() ) {
        the_category(' &bull; ');
        echo $sep;
        echo '<span class="current">' . get_the_title() . '</span>';
    } elseif ( is_page() ) {
        echo '<span class="current">' . get_the_title() . '</span>';
    } elseif ( is_search() ) {
        echo '<span class="current">' . sprintf( __('Search: %s', 'owizotech'), get_search_query() ) . '</span>';
    } elseif ( is_archive() ) {
        echo '<span class="current">' . get_the_archive_title() . '</span>';
    }
    echo '</nav>';
}

/**
 * Star rating HTML
 */
function owizo_stars( $rating = 5, $max = 5 ) {
    $html = '<div class="star-rating" aria-label="' . sprintf( __('%s out of %s stars', 'owizotech'), $rating, $max ) . '">';
    for ( $i = 1; $i <= $max; $i++ ) {
        $class = $i <= $rating ? 'star filled' : 'star';
        $html .= '<span class="' . $class . '">★</span>';
    }
    $html .= '</div>';
    return $html;
}

/**
 * Format price with currency
 */
function owizo_price( $price ) {
    return wc_price( $price );
}

/* =========================================================
   9. PERFORMANCE OPTIMIZATIONS
   ========================================================= */

// Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

// Remove WP emoji
remove_action( 'wp_head',       'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Remove oEmbed
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

// Body classes
add_filter( 'body_class', 'owizo_body_classes' );
function owizo_body_classes( $classes ) {
    if ( is_rtl() )        $classes[] = 'rtl';
    if ( function_exists('is_woocommerce') && is_woocommerce() ) $classes[] = 'owizo-shop';
    if ( is_front_page() ) $classes[] = 'owizo-home';
    $classes[] = 'owizo-theme';
    return $classes;
}

/* =========================================================
   10. SECURITY HARDENING
   ========================================================= */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

// Login error messages
add_filter( 'login_errors', function() {
    return __( 'Invalid credentials. Please try again.', 'owizotech' );
});

/* =========================================================
   11. AJAX HANDLERS
   ========================================================= */

/**
 * AJAX: Add to Cart (uses WC native endpoint)
 */
add_action( 'wp_ajax_owizo_add_to_cart',        'owizo_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_owizo_add_to_cart', 'owizo_ajax_add_to_cart' );
function owizo_ajax_add_to_cart() {
    check_ajax_referer( 'owizo_nonce', 'nonce' );

    $product_id = absint( $_POST['product_id'] ?? 0 );
    $quantity   = absint( $_POST['quantity']   ?? 1 );

    if ( ! $product_id ) {
        wp_send_json([ 'success' => false, 'error' => __('Invalid product.', 'owizotech') ]);
        return;
    }

    // مسح أي notices قديمة
    wc_clear_notices();

    // محاولة الإضافة — الـ plugin سيضع رسالة block في transient لو عنده لايسنس
    $added = WC()->cart->add_to_cart( $product_id, $quantity );

    // ── قراءة transient اللي خلّفه WPLM plugin ──
    $user_id       = get_current_user_id();
    $t_key         = 'wplm_blocked_' . $user_id . '_' . $product_id;
    $wplm_message  = get_transient( $t_key );
    if ( $wplm_message ) {
        delete_transient( $t_key );
        wc_clear_notices();
        wp_send_json([
            'success'    => false,
            'error'      => wp_strip_all_tags( $wplm_message ),
            'error_html' => $wplm_message,
        ]);
        return;
    }

    // ── fallback: أي error notices أضافتها WC ──
    $err_notices = wc_get_notices('error');
    if ( ! empty($err_notices) ) {
        $messages = [];
        foreach ( $err_notices as $n ) {
            $msg = is_array($n) ? ( $n['notice'] ?? '' ) : $n;
            if ( $msg ) $messages[] = wp_strip_all_tags( $msg );
        }
        wc_clear_notices();
        wp_send_json([
            'success' => false,
            'error'   => implode(' ', $messages),
        ]);
        return;
    }

    if ( $added ) {
        WC()->cart->calculate_totals();
        wc_clear_notices();

        // Build fragments so JS can update cart count without reload
        $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', [] );

        wp_send_json([
            'success'    => true,
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'fragments'  => $fragments,
            'message'    => __('Added to cart!', 'owizotech'),
        ]);
    } else {
        wc_clear_notices();
        wp_send_json([
            'success' => false,
            'error'   => __('Could not add to cart. Please try again.', 'owizotech'),
        ]);
    }
}

/**
 * AJAX: Live Search
 */
add_action( 'wp_ajax_owizo_live_search',        'owizo_ajax_live_search' );
add_action( 'wp_ajax_nopriv_owizo_live_search', 'owizo_ajax_live_search' );
function owizo_ajax_live_search() {
    check_ajax_referer( 'owizo_nonce', 'nonce' );

    $query = sanitize_text_field( $_POST['query'] ?? '' );
    if ( strlen( $query ) < 2 ) {
        wp_send_json_success( [] );
    }

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        's'              => $query,
    ];
    $results = get_posts( $args );
    $data    = [];

    foreach ( $results as $post ) {
        $product = wc_get_product( $post->ID );
        if ( ! $product ) continue;
        $data[] = [
            'id'    => $post->ID,
            'name'  => $product->get_name(),
            'url'   => get_permalink( $post->ID ),
            'price' => $product->get_price_html(),
            'image' => wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) ?: wc_placeholder_img_src(),
        ];
    }

    wp_send_json_success( $data );
}

/**
 * AJAX: Filter Products (homepage tabs)
 */
add_action( 'wp_ajax_owizo_filter_products',        'owizo_ajax_filter_products' );
add_action( 'wp_ajax_nopriv_owizo_filter_products', 'owizo_ajax_filter_products' );
function owizo_ajax_filter_products() {
    check_ajax_referer( 'owizo_nonce', 'nonce' );

    $filter = sanitize_key( $_POST['filter'] ?? 'featured' );
    $args   = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 8,
    ];

    switch ( $filter ) {
        case 'new':
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
        case 'sale':
            $args['post__in'] = array_slice( wc_get_product_ids_on_sale(), 0, 8 );
            if ( empty( $args['post__in'] ) ) {
                wp_send_json_success( '<p class="no-results">' . __( 'No sale products right now.', 'owizotech' ) . '</p>' );
                return;
            }
            break;
        default: // featured
            $args['tax_query'] = [[
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ]];
            break;
    }

    $q    = new WP_Query( $args );
    $html = '';

    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            $product = wc_get_product( get_the_ID() );
            if ( $product ) {
                ob_start();
                get_template_part( 'template-parts/product-card', '', [ 'product' => $product ] );
                $html .= ob_get_clean();
            }
        }
        wp_reset_postdata();
    }

    wp_send_json_success( $html );
}

/**
 * AJAX: Newsletter Subscribe
 */
add_action( 'wp_ajax_owizo_newsletter_subscribe',        'owizo_ajax_newsletter' );
add_action( 'wp_ajax_nopriv_owizo_newsletter_subscribe', 'owizo_ajax_newsletter' );
function owizo_ajax_newsletter() {
    check_ajax_referer( 'owizo_nonce', 'nonce' );

    $email = sanitize_email( $_POST['email'] ?? '' );
    if ( ! is_email( $email ) ) {
        wp_send_json_error( __( 'Please enter a valid email.', 'owizotech' ) );
    }

    // Hook: allow integration with Mailchimp / Klaviyo / etc.
    do_action( 'owizo_newsletter_subscribed', $email );

    wp_send_json_success( __( 'Thank you for subscribing!', 'owizotech' ) );
}

/**
 * AJAX: Toggle Wishlist (thin wrapper — requires YITH WCWL)
 */
add_action( 'wp_ajax_owizo_toggle_wishlist', 'owizo_ajax_toggle_wishlist' );
function owizo_ajax_toggle_wishlist() {
    check_ajax_referer( 'owizo_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( __( 'Please log in to use the wishlist.', 'owizotech' ) );
    }
    if ( ! class_exists('YITH_WCWL') ) {
        wp_send_json_error( __( 'Wishlist plugin not active.', 'owizotech' ) );
    }

    $product_id = absint( $_POST['product_id'] ?? 0 );
    if ( ! $product_id ) {
        wp_send_json_error( __( 'Invalid product.', 'owizotech' ) );
    }

    $wishlist_id = YITH_WCWL()->get_wishlist_id_from_token();
    $in_list     = YITH_WCWL()->is_product_in_wishlist( $product_id, $wishlist_id );

    if ( $in_list ) {
        YITH_WCWL()->remove( $product_id, $wishlist_id );
        wp_send_json_success([ 'added' => false ]);
    } else {
        YITH_WCWL()->add( $product_id );
        wp_send_json_success([ 'added' => true ]);
    }
}

/* =========================================================
   12. WOOCOMMERCE TEMPLATE OVERRIDES
   ========================================================= */

/**
 * Use theme's myaccount templates (wraps WC content in our layout)
 */
add_filter( 'woocommerce_locate_template', 'owizo_locate_wc_template', 10, 3 );
function owizo_locate_wc_template( $template, $template_name, $template_path ) {
    $theme_template = get_template_directory() . '/woocommerce/' . $template_name;
    if ( file_exists( $theme_template ) ) {
        return $theme_template;
    }
    return $template;
}

/**
 * My Account: wrap content area header
 */
add_action( 'woocommerce_account_content', 'owizo_before_account_content', 1 );
function owizo_before_account_content() {
    // section-specific header titles injected by templates themselves
}

/**
 * Password toggle JS inline (tiny, account pages only)
 */
add_action( 'wp_footer', 'owizo_account_scripts' );
function owizo_account_scripts() {
    if ( owizo_is_rest_request() ) return;
    if ( ! function_exists('is_account_page') ) return;
    if ( ! is_account_page() && ! is_checkout() ) return;
    ?>
    <script>
    (function(){
        document.querySelectorAll('.toggle-password').forEach(function(btn){
            btn.addEventListener('click', function(){
                var wrap  = btn.closest('.input-password-wrap');
                var input = wrap ? wrap.querySelector('input') : null;
                if (!input) return;
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });
    })();
    </script>
    <?php
}

/**
 * Change My Account "woocommerce" wrapper to add our class
 */
add_action( 'woocommerce_before_account_navigation', 'owizo_before_account_nav' );
function owizo_before_account_nav() {
    // handled inside our my-account.php template
}


/* =========================================================
   13. SHOP SIDEBAR — FILTER NON-WC WIDGETS
   ========================================================= */
add_filter( 'sidebars_widgets', 'owizo_filter_shop_sidebar_widgets' );
function owizo_filter_shop_sidebar_widgets( $sidebars_widgets ) {
    if ( owizo_is_rest_request() ) return $sidebars_widgets;
    if ( is_admin() ) return $sidebars_widgets;
    if ( ! did_action('wp') ) return $sidebars_widgets;
    if ( ! function_exists('is_woocommerce') ) return $sidebars_widgets;
    if ( ! is_woocommerce() && ! is_product_category() && ! is_product_tag() ) {
        return $sidebars_widgets;
    }
    if ( empty( $sidebars_widgets['shop-sidebar'] ) ) {
        return $sidebars_widgets;
    }
    $allowed_bases = [
        'woocommerce_product_search',
        'woocommerce_product_categories',
        'woocommerce_price_filter',
        'woocommerce_layered_nav',
        'woocommerce_layered_nav_filters',
        'woocommerce_rating_filter',
    ];
    $filtered = [];
    global $wp_registered_widgets;
    foreach ( $sidebars_widgets['shop-sidebar'] as $widget_id ) {
        $base = preg_replace( '/-\d+$/', '', $widget_id );
        if ( in_array( $base, $allowed_bases ) ) {
            $filtered[] = $widget_id;
        }
    }
    $sidebars_widgets['shop-sidebar'] = $filtered;
    return $sidebars_widgets;
}


/* =========================================================
   14. MY LICENSE — Account Endpoint
   ========================================================= */

/**
 * Register rewrite endpoint for My License page
 */
add_action( 'init', 'owizo_register_license_endpoint' );
function owizo_register_license_endpoint() {
    add_rewrite_endpoint( 'my-license', EP_ROOT | EP_PAGES );
}

/**
 * Add "My License" to WooCommerce account menu
 */
add_filter( 'woocommerce_account_menu_items', 'owizo_add_license_menu_item' );
function owizo_add_license_menu_item( $items ) {
    // Insert before logout
    $logout = $items['customer-logout'] ?? null;
    unset( $items['customer-logout'] );

    $items['my-license'] = __( 'My Licenses', 'owizotech' );

    if ( $logout ) {
        $items['customer-logout'] = $logout;
    }
    return $items;
}

/**
 * Output content for the My License endpoint
 */
add_action( 'woocommerce_account_my-license_endpoint', 'owizo_my_license_content' );
function owizo_my_license_content() {
    if ( ! is_user_logged_in() ) return;

    global $wpdb;
    $table   = $wpdb->prefix . 'licenses';
    $user_id = get_current_user_id();

    // Check if license table exists
    $table_exists = $wpdb->get_var( "SHOW TABLES LIKE '{$table}'" );
    if ( ! $table_exists ) {
        echo '<div class="woocommerce-message woocommerce-info">'
           . esc_html__( 'License system is not active on this site.', 'owizotech' )
           . '</div>';
        return;
    }

    $licenses = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT l.*, p.post_title as product_name
             FROM {$table} l
             LEFT JOIN {$wpdb->posts} p ON p.ID = l.product_id
             WHERE l.user_id = %d
             ORDER BY l.start_date DESC",
            $user_id
        )
    );

    ?>
    <div class="owizo-my-licenses">
        <div class="account-section-header">
            <h2 class="account-section-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="5" y="11" width="14" height="10" rx="2"/><path d="M5 11V7a7 7 0 0 1 14 0v4"/>
                    <circle cx="12" cy="16" r="1"/>
                </svg>
                <?php _e( 'My Licenses', 'owizotech' ); ?>
            </h2>
            <?php if ( $licenses ) : ?>
            <span class="account-section-count"><?php echo count($licenses); ?> <?php _e('licenses', 'owizotech'); ?></span>
            <?php endif; ?>
        </div>

        <?php if ( ! $licenses ) : ?>
        <div class="account-empty-state">
            <div class="empty-icon">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <rect x="5" y="11" width="14" height="10" rx="2"/><path d="M5 11V7a7 7 0 0 1 14 0v4"/>
                </svg>
            </div>
            <h3><?php _e( 'No licenses yet', 'owizotech' ); ?></h3>
            <p><?php _e( 'Purchase a product with a license to see it here.', 'owizotech' ); ?></p>
            <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-primary">
                <?php _e( 'Browse Products', 'owizotech' ); ?>
            </a>
        </div>
        <?php else : ?>
        <div class="licenses-grid">
            <?php foreach ( $licenses as $lic ) :
                $now        = current_time('timestamp');
                $end_ts     = strtotime( $lic->end_date );
                $start_ts   = strtotime( $lic->start_date );
                $days_left  = max(0, ceil( ($end_ts - $now) / DAY_IN_SECONDS ));
                $days_total = max(1, ceil( ($end_ts - $start_ts) / DAY_IN_SECONDS ));
                $pct_used   = min(100, round( (($now - $start_ts) / max(1, $end_ts - $start_ts)) * 100 ));

                $status = $lic->status;
                if ( $status === 'active' && $end_ts < $now ) $status = 'expired';

                $status_labels = [
                    'active'   => __('Active',   'owizotech'),
                    'expired'  => __('Expired',  'owizotech'),
                    'inactive' => __('Inactive', 'owizotech'),
                ];
            ?>
            <div class="license-card card <?php echo esc_attr('license--' . $status); ?>">
                <!-- Card Header -->
                <div class="license-card-header">
                    <div class="license-product-name">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        </svg>
                        <?php echo esc_html( $lic->product_name ?? __('Product', 'owizotech') ); ?>
                    </div>
                    <span class="license-status-badge license-status-<?php echo esc_attr($status); ?>">
                        <?php echo esc_html( $status_labels[$status] ?? $status ); ?>
                    </span>
                </div>

                <!-- License Key -->
                <div class="license-key-section">
                    <div class="license-key-label"><?php _e('License Key', 'owizotech'); ?></div>
                    <div class="license-key-wrap">
                        <code class="license-key-value" id="lk-<?php echo esc_attr($lic->id ?? uniqid()); ?>">
                            <?php echo esc_html($lic->license_key); ?>
                        </code>
                        <button type="button" class="license-copy-btn" data-key="<?php echo esc_attr($lic->license_key); ?>" title="<?php esc_attr_e('Copy key', 'owizotech'); ?>">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Dates -->
                <div class="license-dates-row">
                    <div class="license-date-item">
                        <span class="license-date-label"><?php _e('Start Date', 'owizotech'); ?></span>
                        <span class="license-date-value"><?php echo esc_html( date_i18n( get_option('date_format'), $start_ts ) ); ?></span>
                    </div>
                    <div class="license-date-sep">→</div>
                    <div class="license-date-item">
                        <span class="license-date-label"><?php _e('Expiry Date', 'owizotech'); ?></span>
                        <span class="license-date-value <?php echo $status === 'expired' ? 'text-danger' : ''; ?>">
                            <?php echo esc_html( date_i18n( get_option('date_format'), $end_ts ) ); ?>
                        </span>
                    </div>
                </div>

                <!-- Expiry Progress Bar -->
                <?php if ( $status === 'active' ) : ?>
                <div class="license-progress-section">
                    <div class="license-progress-info">
                        <span class="license-days-left">
                            <?php printf( _n('%d day left', '%d days left', $days_left, 'owizotech'), $days_left ); ?>
                        </span>
                        <span class="license-days-total"><?php printf( __('%d days total', 'owizotech'), $days_total ); ?></span>
                    </div>
                    <div class="license-progress-bar">
                        <div class="license-progress-fill" style="width:<?php echo esc_attr($pct_used); ?>%"
                             data-pct="<?php echo esc_attr($pct_used); ?>"></div>
                    </div>
                </div>
                <?php elseif ( $status === 'expired' ) : ?>
                <div class="license-expired-notice">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?php _e('This license has expired. Purchase again to renew.', 'owizotech'); ?>
                    <?php if ( $lic->product_id ) : ?>
                    <a href="<?php echo esc_url( get_permalink($lic->product_id) ); ?>" class="license-renew-link">
                        <?php _e('Renew →', 'owizotech'); ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Footer actions -->
                <?php if ( $lic->product_id ) : ?>
                <div class="license-card-footer">
                    <a href="<?php echo esc_url( get_permalink($lic->product_id) ); ?>" class="btn btn-ghost btn-sm">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                        <?php _e('View Product', 'owizotech'); ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
    (function(){
        document.querySelectorAll('.license-copy-btn').forEach(function(btn){
            btn.addEventListener('click', function(){
                var key = btn.dataset.key;
                if (!key) return;
                navigator.clipboard.writeText(key).then(function(){
                    var orig = btn.innerHTML;
                    btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20,6 9,17 4,12"/></svg>';
                    btn.style.color = 'var(--clr-success)';
                    if (window.owizoShowToast) owizoShowToast('<?php echo esc_js(__('License key copied!', 'owizotech')); ?>', 'success');
                    setTimeout(function(){ btn.innerHTML = orig; btn.style.color = ''; }, 2000);
                });
            });
        });
    })();
    </script>
    <?php
}

