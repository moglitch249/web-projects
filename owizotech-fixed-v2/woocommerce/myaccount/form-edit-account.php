<?php
/**
 * My Account - Edit Account - OwizoTech
 */
defined('ABSPATH') || exit;
?>

<div class="account-edit-page">
    <div class="account-section-header">
        <h2 class="account-section-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <?php _e('Account Details', 'owizotech'); ?>
        </h2>
    </div>

    <form class="owizo-account-form" action="" method="post" <?php do_action('woocommerce_save_account_details_form_tag'); ?>>

        <!-- Personal Info -->
        <div class="form-section">
            <h3 class="form-section-title"><?php _e('Personal Information', 'owizotech'); ?></h3>
            <div class="form-row-grid">
                <div class="form-group">
                    <label for="account_first_name"><?php _e('First name', 'owizotech'); ?> <span class="required">*</span></label>
                    <input type="text" class="input-text" name="account_first_name" id="account_first_name"
                           value="<?php echo esc_attr( $user->first_name ); ?>" autocomplete="given-name" required />
                </div>
                <div class="form-group">
                    <label for="account_last_name"><?php _e('Last name', 'owizotech'); ?> <span class="required">*</span></label>
                    <input type="text" class="input-text" name="account_last_name" id="account_last_name"
                           value="<?php echo esc_attr( $user->last_name ); ?>" autocomplete="family-name" required />
                </div>
            </div>
            <div class="form-group">
                <label for="account_display_name"><?php _e('Display name', 'owizotech'); ?> <span class="required">*</span></label>
                <input type="text" class="input-text" name="account_display_name" id="account_display_name"
                       value="<?php echo esc_attr( $user->display_name ); ?>" required />
                <span class="form-hint"><?php _e('This will be displayed on the site.', 'owizotech'); ?></span>
            </div>
            <div class="form-group">
                <label for="account_email"><?php _e('Email address', 'owizotech'); ?> <span class="required">*</span></label>
                <input type="email" class="input-text" name="account_email" id="account_email"
                       value="<?php echo esc_attr( $user->user_email ); ?>" autocomplete="email" required />
            </div>
        </div>

        <!-- Password Change -->
        <div class="form-section">
            <h3 class="form-section-title"><?php _e('Password Change', 'owizotech'); ?></h3>
            <p class="form-section-desc"><?php _e('Leave blank to keep current password.', 'owizotech'); ?></p>

            <div class="form-group">
                <label for="password_current"><?php _e('Current password', 'owizotech'); ?></label>
                <div class="input-password-wrap">
                    <input type="password" class="input-text" name="password_current" id="password_current" autocomplete="off" />
                    <button type="button" class="toggle-password" aria-label="<?php _e('Show password', 'owizotech'); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>
            <div class="form-row-grid">
                <div class="form-group">
                    <label for="password_1"><?php _e('New password', 'owizotech'); ?></label>
                    <div class="input-password-wrap">
                        <input type="password" class="input-text" name="password_1" id="password_1" autocomplete="new-password" />
                        <button type="button" class="toggle-password" aria-label="<?php _e('Show password', 'owizotech'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_2"><?php _e('Confirm new password', 'owizotech'); ?></label>
                    <div class="input-password-wrap">
                        <input type="password" class="input-text" name="password_2" id="password_2" autocomplete="new-password" />
                        <button type="button" class="toggle-password" aria-label="<?php _e('Show password', 'owizotech'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php do_action('woocommerce_save_account_details_form', $user); ?>

        <div class="form-submit-row">
            <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
            <input type="hidden" name="action" value="save_account_details" />
            <button type="submit" class="btn btn-primary btn-lg" name="save_account_details" value="<?php esc_attr_e('Save changes', 'owizotech'); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17,21 17,13 7,13 7,21"/><polyline points="7,3 7,8 15,8"/></svg>
                <?php _e('Save Changes', 'owizotech'); ?>
            </button>
        </div>

    </form>
</div>
