<?php
/**
 * My Account Page - OwizoTech
 */
defined('ABSPATH') || exit;
?>

<div class="owizo-account-wrap">

    <!-- Account Hero Header -->
    <div class="account-hero">
        <div class="container">
            <div class="account-hero-inner">
                <?php if ( is_user_logged_in() ) :
                    $user = wp_get_current_user();
                    $initials = strtoupper( substr($user->display_name, 0, 1) );
                ?>
                <div class="account-avatar">
                    <?php if ( function_exists('get_avatar') ) :
                        $avatar = get_avatar( $user->ID, 80, '', '', ['class' => 'avatar-img'] );
                        if ( strpos($avatar, 'gravatar') !== false && strpos($avatar, 'default') !== false ) :
                            echo '<span class="avatar-initials">' . esc_html($initials) . '</span>';
                        else :
                            echo $avatar;
                        endif;
                    else : ?>
                        <span class="avatar-initials"><?php echo esc_html($initials); ?></span>
                    <?php endif; ?>
                </div>
                <div class="account-hero-info">
                    <h1 class="account-greeting"><?php printf( __('Welcome, %s', 'owizotech'), '<span class="accent">' . esc_html($user->display_name) . '</span>' ); ?></h1>
                    <p class="account-email"><?php echo esc_html($user->user_email); ?></p>
                </div>
                <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="btn btn-ghost btn-sm account-logout-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <?php _e('Logout', 'owizotech'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="account-layout">

            <!-- Sidebar Navigation -->
            <aside class="account-sidebar">
                <nav class="account-nav">
                    <?php
                    $current = WC()->query->get_current_endpoint();
                    $menu_items = wc_get_account_menu_items();
                    foreach ( $menu_items as $endpoint => $label ) :
                        $url   = wc_get_account_endpoint_url( $endpoint );
                        $is_active = ( $current === $endpoint ) || ( empty($current) && $endpoint === 'dashboard' );
                        $icons = [
                            'dashboard'       => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
                            'orders'          => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                            'downloads'       => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
                            'edit-address'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>',
                            'my-wallet'       => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><circle cx="16" cy="12" r="2"/></svg>',
                            'my-license'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M5 11V7a7 7 0 0 1 14 0v4"/><circle cx="12" cy="16" r="1"/></svg>',
                            'payment-methods' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
                            'edit-account'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
                            'customer-logout' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
                        ];
                        $icon = $icons[$endpoint] ?? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>';
                    ?>
                    <a href="<?php echo esc_url($url); ?>"
                       class="account-nav-item <?php echo $is_active ? 'active' : ''; ?> <?php echo $endpoint === 'customer-logout' ? 'logout-item' : ''; ?>">
                        <span class="nav-icon"><?php echo $icon; ?></span>
                        <span class="nav-label"><?php echo esc_html($label); ?></span>
                        <span class="nav-arrow">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="account-content card">
                <?php
                do_action('woocommerce_account_content');
                ?>
            </div>

        </div><!-- .account-layout -->
    </div><!-- .container -->

</div><!-- .owizo-account-wrap -->
