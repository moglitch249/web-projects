</main><!-- #main-content -->

<!-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ -->
<footer class="site-footer" id="owizo-footer">

    <!-- Newsletter Band -->
    <div class="footer-newsletter">
        <div class="container">
            <div class="newsletter-inner">
                <div class="newsletter-content">
                    <h3 class="newsletter-title"><?php _e('Stay Ahead of Tech', 'owizotech'); ?></h3>
                    <p><?php _e('Get exclusive deals, new arrivals & tech news straight to your inbox.', 'owizotech'); ?></p>
                </div>
                <form class="newsletter-form" id="newsletter-form" novalidate>
                    <div class="newsletter-fields">
                        <input
                            type="email"
                            name="email"
                            placeholder="<?php esc_attr_e('your@email.com', 'owizotech'); ?>"
                            required
                            class="newsletter-input"
                            aria-label="<?php esc_attr_e('Email address', 'owizotech'); ?>"
                        >
                        <button type="submit" class="btn btn-primary">
                            <?php _e('Subscribe', 'owizotech'); ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 14 0M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                    <p class="newsletter-note"><?php _e('No spam. Unsubscribe anytime.', 'owizotech'); ?></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Main -->
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">

                <!-- Brand Column -->
                <div class="footer-brand-col">
                    <a href="<?php echo esc_url( home_url('/') ); ?>" class="footer-logo">
                        <span class="brand-name">Owizo<span class="brand-accent">Tech</span></span>
                    </a>
                    <p class="footer-tagline"><?php _e('Your trusted destination for premium electronics & cutting-edge technology.', 'owizotech'); ?></p>

                    <!-- Social Links -->
                    <div class="social-links">
                        <a href="#" class="social-btn" aria-label="Facebook" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="#" class="social-btn" aria-label="Instagram" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="#" class="social-btn" aria-label="Twitter/X" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="social-btn" aria-label="YouTube" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#000"/></svg>
                        </a>
                        <a href="#" class="social-btn" aria-label="TikTok" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.3 6.3 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.67a8.18 8.18 0 0 0 4.78 1.52V6.7a4.85 4.85 0 0 1-1.01-.01z"/></svg>
                        </a>
                    </div>

                    <!-- App Badges -->
                    <div class="app-badges">
                        <a href="#" class="app-badge">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            App Store
                        </a>
                        <a href="#" class="app-badge">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M3.18 23.76c.2.12.43.17.66.14.24-.03.47-.14.66-.32L15.3 12 4.5.42C4.31.24 4.08.13 3.84.1c-.23-.03-.46.02-.66.14-.41.23-.67.66-.67 1.14v21.24c0 .48.26.91.67 1.14z"/><path d="m18.16 9.5-2.85-1.64L12.04 12l3.27 4.14 2.85-1.64A2.01 2.01 0 0 0 19.17 12a2.01 2.01 0 0 0-1.01-2.5z"/><path d="M4.5 23.58c-.2.18-.43.29-.66.32l11.46-11.9L4.5.42Z"/></svg>
                            Google Play
                        </a>
                    </div>
                </div>

                <!-- Widget Columns -->
                <div class="footer-widgets">
                    <?php if ( is_active_sidebar('footer-1') ) : ?>
                    <div class="footer-col">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ( is_active_sidebar('footer-2') ) : ?>
                    <div class="footer-col">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ( is_active_sidebar('footer-3') ) : ?>
                    <div class="footer-col">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <!-- Trust Badges -->
    <div class="footer-trust">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-item">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    <div>
                        <strong><?php _e('Free Shipping', 'owizotech'); ?></strong>
                        <span><?php _e('On orders over $99', 'owizotech'); ?></span>
                    </div>
                </div>
                <div class="trust-item">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <div>
                        <strong><?php _e('Secure Payment', 'owizotech'); ?></strong>
                        <span><?php _e('SSL encrypted checkout', 'owizotech'); ?></span>
                    </div>
                </div>
                <div class="trust-item">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
                    <div>
                        <strong><?php _e('Easy Returns', 'owizotech'); ?></strong>
                        <span><?php _e('30-day return policy', 'owizotech'); ?></span>
                    </div>
                </div>
                <div class="trust-item">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.29 6.29l1.17-.87a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <div>
                        <strong><?php _e('24/7 Support', 'owizotech'); ?></strong>
                        <span><?php _e('Expert tech assistance', 'owizotech'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <p class="copyright">
                    <?php echo esc_html( owizo_option('owizo_footer_copyright', '© ' . date('Y') . ' OwizoTech. ' . __('All rights reserved.', 'owizotech')) ); ?>
                </p>

                <div class="footer-legal">
                    <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php _e('Privacy Policy', 'owizotech'); ?></a>
                    <a href="<?php echo esc_url( home_url('/terms') ); ?>"><?php _e('Terms of Use', 'owizotech'); ?></a>
                    <a href="<?php echo esc_url( home_url('/cookies') ); ?>"><?php _e('Cookie Policy', 'owizotech'); ?></a>
                    <a href="<?php echo esc_url( home_url('/sitemap.xml') ); ?>"><?php _e('Sitemap', 'owizotech'); ?></a>
                </div>

                <div class="payment-methods" aria-label="<?php _e('Accepted payment methods', 'owizotech'); ?>">
                    <img src="<?php echo OWIZO_ASSETS; ?>/images/payment/visa.svg"       alt="Visa"       width="40" height="26" loading="lazy">
                    <img src="<?php echo OWIZO_ASSETS; ?>/images/payment/mastercard.svg" alt="Mastercard" width="40" height="26" loading="lazy">
                    <img src="<?php echo OWIZO_ASSETS; ?>/images/payment/paypal.svg"     alt="PayPal"     width="40" height="26" loading="lazy">
                    <img src="<?php echo OWIZO_ASSETS; ?>/images/payment/apple-pay.svg"  alt="Apple Pay"  width="40" height="26" loading="lazy">
                    <img src="<?php echo OWIZO_ASSETS; ?>/images/payment/google-pay.svg" alt="Google Pay" width="40" height="26" loading="lazy">
                </div>
            </div>
        </div>
    </div>

</footer>

<!-- BACK TO TOP -->
<button class="back-to-top" id="back-to-top" aria-label="<?php _e('Back to top', 'owizotech'); ?>">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 15-6-6-6 6"/></svg>
</button>

<!-- CART DRAWER (side panel) -->
<div class="cart-drawer" id="cart-drawer" aria-hidden="true" role="dialog" aria-label="<?php _e('Shopping Cart', 'owizotech'); ?>">
    <div class="cart-drawer-inner">
        <div class="cart-drawer-header">
            <h3><?php _e('Your Cart', 'owizotech'); ?></h3>
            <button class="cart-drawer-close" id="cart-drawer-close" aria-label="<?php _e('Close cart', 'owizotech'); ?>">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="cart-drawer-content" id="cart-drawer-content">
            <?php woocommerce_mini_cart(); ?>
        </div>
    </div>
</div>

</div><!-- #owizo-page -->

<?php wp_footer(); ?>
</body>
</html>
