<?php
/**
 * My Account - Login / Register - OwizoTech
 */
defined('ABSPATH') || exit;
?>

<div class="owizo-auth-page">
    <div class="auth-bg-effects">
        <div class="auth-orb auth-orb--1"></div>
        <div class="auth-orb auth-orb--2"></div>
    </div>

    <div class="container">
        <div class="auth-wrap">

            <!-- Login Form -->
            <div class="auth-panel card">
                <div class="auth-panel-header">
                    <div class="auth-icon auth-icon--login">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10,17 15,12 10,7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    </div>
                    <h2 class="auth-title"><?php _e('Sign In', 'owizotech'); ?></h2>
                    <p class="auth-subtitle"><?php _e('Welcome back! Login to your account.', 'owizotech'); ?></p>
                </div>

                <form class="owizo-auth-form" method="post" <?php do_action('woocommerce_login_form_tag'); ?>>
                    <?php do_action('woocommerce_login_form_start'); ?>

                    <div class="form-group">
                        <label for="username"><?php _e('Username or email address', 'owizotech'); ?> <span class="required">*</span></label>
                        <div class="input-icon-wrap">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" class="input-text" name="username" id="username" autocomplete="username" required value="<?php echo ( ! empty($_POST['username']) ) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password"><?php _e('Password', 'owizotech'); ?> <span class="required">*</span></label>
                        <div class="input-icon-wrap input-password-wrap">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" class="input-text" name="password" id="password" autocomplete="current-password" required />
                            <button type="button" class="toggle-password" aria-label="<?php _e('Show password', 'owizotech'); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-row-inline">
                        <label class="checkbox-label" for="rememberme">
                            <input type="checkbox" class="owizo-checkbox" name="rememberme" id="rememberme" value="forever" />
                            <span class="checkbox-custom"></span>
                            <?php _e('Remember me', 'owizotech'); ?>
                        </label>
                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="forgot-link">
                            <?php _e('Forgot password?', 'owizotech'); ?>
                        </a>
                    </div>

                    <?php do_action('woocommerce_login_form'); ?>

                    <div class="form-submit">
                        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                        <input type="hidden" name="redirect" value="<?php echo esc_url( apply_filters( 'woocommerce_login_redirect', wc_get_page_permalink('myaccount'), null ) ); ?>" />
                        <button type="submit" class="btn btn-primary btn-lg form-btn" name="login" value="<?php esc_attr_e('Sign in', 'owizotech'); ?>">
                            <?php _e('Sign In', 'owizotech'); ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <?php do_action('woocommerce_login_form_end'); ?>
                </form>
            </div>

            <?php if ( 'yes' === get_option('woocommerce_enable_myaccount_registration') ) : ?>
            <!-- Register Form -->
            <div class="auth-panel card">
                <div class="auth-panel-header">
                    <div class="auth-icon auth-icon--register">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    </div>
                    <h2 class="auth-title"><?php _e('Create Account', 'owizotech'); ?></h2>
                    <p class="auth-subtitle"><?php _e('Join us and start shopping smarter.', 'owizotech'); ?></p>
                </div>

                <form class="owizo-auth-form" method="post" <?php do_action('woocommerce_register_form_tag'); ?>>
                    <?php do_action('woocommerce_register_form_start'); ?>

                    <?php if ( 'no' === get_option('woocommerce_registration_generate_username') ) : ?>
                    <div class="form-group">
                        <label for="reg_username"><?php _e('Username', 'owizotech'); ?> <span class="required">*</span></label>
                        <div class="input-icon-wrap">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" class="input-text" name="username" id="reg_username" autocomplete="username" required value="<?php echo ( ! empty($_POST['username']) ) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="reg_email"><?php _e('Email address', 'owizotech'); ?> <span class="required">*</span></label>
                        <div class="input-icon-wrap">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <input type="email" class="input-text" name="email" id="reg_email" autocomplete="email" required value="<?php echo ( ! empty($_POST['email']) ) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
                        </div>
                    </div>

                    <?php if ( 'no' === get_option('woocommerce_registration_generate_password') ) : ?>
                    <div class="form-group">
                        <label for="reg_password"><?php _e('Password', 'owizotech'); ?> <span class="required">*</span></label>
                        <div class="input-icon-wrap input-password-wrap">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" class="input-text" name="password" id="reg_password" autocomplete="new-password" required />
                            <button type="button" class="toggle-password" aria-label="<?php _e('Show password', 'owizotech'); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                    <?php else : ?>
                    <div class="auth-auto-password-note">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <?php _e('A password will be sent to your email address.', 'owizotech'); ?>
                    </div>
                    <?php endif; ?>

                    <?php do_action('woocommerce_register_form'); ?>

                    <div class="form-submit">
                        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                        <button type="submit" class="btn btn-secondary btn-lg form-btn" name="register" value="<?php esc_attr_e('Register', 'owizotech'); ?>">
                            <?php _e('Create Account', 'owizotech'); ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                        </button>
                    </div>

                    <?php do_action('woocommerce_register_form_end'); ?>
                </form>
            </div>
            <?php endif; ?>

        </div><!-- .auth-wrap -->
    </div><!-- .container -->
</div><!-- .owizo-auth-page -->
