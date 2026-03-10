/**
 * OwizoTech — main.js
 * Core JavaScript: header, search, mobile drawer, cart drawer,
 * back to top, scroll effects, toast notifications, AJAX add-to-cart.
 */
(function($) {
    'use strict';

    /* ═══════════════════════════════════════
       DOM CACHE
    ═══════════════════════════════════════ */
    const $body          = $('body');
    const $header        = $('#owizo-header');
    const $searchToggle  = $('#search-toggle');
    const $searchOverlay = $('#search-overlay');
    const $searchClose   = $('#search-close');
    const $searchInput   = $('#site-search');
    const $menuToggle    = $('#menu-toggle');
    const $mobileDrawer  = $('#mobile-drawer');
    const $drawerClose   = $('#drawer-close');
    const $cartTrigger   = $('#cart-trigger');
    const $cartDrawer    = $('#cart-drawer');
    const $cartDrawerClose = $('#cart-drawer-close');
    const $backdrop      = $('#backdrop');
    const $backToTop     = $('#back-to-top');

    /* ═══════════════════════════════════════
       STICKY HEADER
    ═══════════════════════════════════════ */
    let lastScroll = 0;
    $(window).on('scroll.header', function() {
        const scroll = $(this).scrollTop();
        if (scroll > 80) {
            $header.addClass('scrolled');
        } else {
            $header.removeClass('scrolled');
        }
        lastScroll = scroll;
    });

    /* ═══════════════════════════════════════
       SEARCH OVERLAY
    ═══════════════════════════════════════ */
    function openSearch() {
        $searchOverlay.addClass('open').attr('aria-hidden', 'false');
        $searchToggle.attr('aria-expanded', 'true');
        setTimeout(() => $searchInput.focus(), 200);
    }
    function closeSearch() {
        $searchOverlay.removeClass('open').attr('aria-hidden', 'true');
        $searchToggle.attr('aria-expanded', 'false');
    }

    $searchToggle.on('click', openSearch);
    $searchClose.on('click', closeSearch);
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') { closeSearch(); closeMobileDrawer(); closeCartDrawer(); }
    });

    // Live search suggestions
    let searchDebounce;
    $searchInput.on('input', function() {
        clearTimeout(searchDebounce);
        const query = $(this).val().trim();
        const $suggestions = $('#search-suggestions');

        if (query.length < 2) { $suggestions.removeClass('open').empty(); return; }

        searchDebounce = setTimeout(function() {
            $.ajax({
                url: owizoData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'owizo_live_search',
                    query: query,
                    nonce: owizoData.nonce,
                },
                success: function(response) {
                    if (response.success && response.data.length) {
                        let html = '<ul class="search-suggestion-list">';
                        response.data.forEach(function(item) {
                            html += `
                                <li>
                                    <a href="${item.url}" class="suggestion-item">
                                        <img src="${item.image}" alt="${item.name}" width="40" height="40">
                                        <span class="suggestion-name">${item.name}</span>
                                        <span class="suggestion-price">${item.price}</span>
                                    </a>
                                </li>`;
                        });
                        html += '</ul>';
                        $suggestions.html(html).addClass('open');
                    } else {
                        $suggestions.removeClass('open').empty();
                    }
                },
            });
        }, 300);
    });

    /* ═══════════════════════════════════════
       MOBILE DRAWER
    ═══════════════════════════════════════ */
    function openMobileDrawer() {
        $mobileDrawer.addClass('open').attr('aria-hidden', 'false');
        $backdrop.addClass('show');
        $menuToggle.addClass('open').attr('aria-expanded', 'true');
        $body.css('overflow', 'hidden');
    }
    function closeMobileDrawer() {
        $mobileDrawer.removeClass('open').attr('aria-hidden', 'true');
        $backdrop.removeClass('show');
        $menuToggle.removeClass('open').attr('aria-expanded', 'false');
        $body.css('overflow', '');
    }

    $menuToggle.on('click', openMobileDrawer);
    $drawerClose.on('click', closeMobileDrawer);
    $backdrop.on('click', function() { closeMobileDrawer(); closeCartDrawer(); });

    /* ═══════════════════════════════════════
       CART DRAWER
    ═══════════════════════════════════════ */
    function openCartDrawer() {
        $cartDrawer.addClass('open').attr('aria-hidden', 'false');
        $backdrop.addClass('show');
        $body.css('overflow', 'hidden');
    }
    function closeCartDrawer() {
        $cartDrawer.removeClass('open').attr('aria-hidden', 'true');
        $backdrop.removeClass('show');
        $body.css('overflow', '');
    }

    $cartTrigger.on('click', function(e) {
        // If on cart page, follow link. Otherwise open drawer.
        if (!$('body').hasClass('woocommerce-cart') && !$('body').hasClass('woocommerce-checkout')) {
            e.preventDefault();
            openCartDrawer();
        }
    });
    $cartDrawerClose.on('click', closeCartDrawer);

    /**
     * updateAllCartCounts — تحديث كل عناصر .cart-count في الصفحة دفعة واحدة
     */
    function updateAllCartCounts(n) {
        n = parseInt(n, 10) || 0;
        $('.cart-count').each(function() {
            $(this).text(n).toggleClass('has-items', n > 0);
        });
    }
    window.owizoUpdateCartCount = updateAllCartCounts;

    // WooCommerce native: تطبيق الـ fragments بعد أي تحديث
    $(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function() {
        try {
            var key = (typeof wc_cart_fragments_params !== 'undefined' && wc_cart_fragments_params.fragment_name)
                      ? wc_cart_fragments_params.fragment_name : 'wc_fragments';
            var raw = sessionStorage.getItem(key);
            if (raw) {
                var frags = JSON.parse(raw);
                if (frags) {
                    $.each(frags, function(selector, html) { $(selector).replaceWith(html); });
                }
            }
        } catch(e) {}
    });

    // WooCommerce native: بعد add_to_cart ناجح
    $(document.body).on('added_to_cart', function(e, fragments) {
        if (fragments) {
            $.each(fragments, function(selector, html) {
                // استخدام html() بدل replaceWith() لتحديث كل العناصر المطابقة
                var $els = $(selector);
                if ($els.length > 1) {
                    // أكثر من عنصر — نحدث كل واحد بشكل مستقل
                    $els.each(function() {
                        var $new = $(html);
                        $(this).replaceWith($new.clone());
                    });
                } else {
                    $els.replaceWith(html);
                }
            });
        }
    });

    /* ═══════════════════════════════════════
       AJAX ADD TO CART (Product Cards)
    ═══════════════════════════════════════ */
    $body.on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const productId = $btn.data('product-id');

        if ($btn.hasClass('loading')) return;

        $btn.addClass('loading').html(`
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 0.6s linear infinite">
                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
            </svg>
        `);

        $.ajax({
            url: owizoData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'owizo_add_to_cart',
                product_id: productId,
                quantity: 1,
                nonce: owizoData.nonce,
            },
            success: function(response) {
                // ── Reset button icon ──
                const resetBtn = function() {
                    $btn.removeClass('loading added').html(`
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        <span>${owizoData.i18n.addToCart}</span>
                    `);
                };

                // ── Error: response.error OR response.success===false ──
                var errorMsg = response.error
                             || (response.data && response.data.error)
                             || (!response.success ? owizoData.i18n.error : null);

                if ( !response.success || errorMsg ) {
                    showToast(errorMsg || owizoData.i18n.error, 'error');
                    resetBtn();
                    return;
                }

                // ── Success ──
                $btn.html(`
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    <span>${owizoData.i18n.addedToCart}</span>
                `).addClass('added');

                showToast(owizoData.i18n.addedToCart, 'success');

                // ── تحديث فوري لعداد السلة من الـ response بدون أي page reload ──
                if (response.cart_count !== undefined) {
                    updateAllCartCounts(response.cart_count);
                }

                // تطبيق الـ fragments لو موجودة (تحديث mini cart, etc.)
                if (response.fragments) {
                    $.each(response.fragments, function(selector, html) {
                        $(selector).replaceWith(html);
                    });
                }

                // إطلاق الـ WooCommerce events للـ plugins الأخرى
                $(document.body).trigger('added_to_cart', [response.fragments || {}, '', $btn]);

                setTimeout(resetBtn, 2500);
            },
            error: function() {
                showToast(owizoData.i18n.error, 'error');
                $btn.removeClass('loading').html(`<span>${owizoData.i18n.addToCart}</span>`);
            },
        });
    });

    /* ═══════════════════════════════════════
       TOAST NOTIFICATIONS
    ═══════════════════════════════════════ */
    let toastTimer;
    function showToast(message, type = 'success') {
        $('.owizo-toast').remove();
        clearTimeout(toastTimer);

        const icon = type === 'success'
            ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/></svg>`
            : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;

        const $toast = $(`<div class="owizo-toast ${type}" role="alert" aria-live="polite">${icon}${message}</div>`);
        $body.append($toast);

        requestAnimationFrame(() => {
            requestAnimationFrame(() => $toast.addClass('show'));
        });

        toastTimer = setTimeout(function() {
            $toast.removeClass('show');
            setTimeout(() => $toast.remove(), 400);
        }, 3000);
    }

    // Expose globally
    window.owizoShowToast = showToast;

    /* ═══════════════════════════════════════
       BACK TO TOP
    ═══════════════════════════════════════ */
    $(window).on('scroll.btt', function() {
        if ($(this).scrollTop() > 400) {
            $backToTop.addClass('show');
        } else {
            $backToTop.removeClass('show');
        }
    });
    $backToTop.on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 500, 'swing');
    });

    /* ═══════════════════════════════════════
       INTERSECTION OBSERVER — ANIMATE ON SCROLL
    ═══════════════════════════════════════ */
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        document.querySelectorAll('.product-card, .category-card, .stat-item, .testimonial-card, .blog-card').forEach(function(el) {
            el.classList.add('observe-animate');
            observer.observe(el);
        });
    }

    /* ═══════════════════════════════════════
       PRODUCT TABS FILTER (Homepage)
    ═══════════════════════════════════════ */
    $body.on('click', '.product-tab', function() {
        const $tab = $(this);
        const filter = $tab.data('filter');

        $('.product-tab').removeClass('active').attr('aria-selected', 'false');
        $tab.addClass('active').attr('aria-selected', 'true');

        const $grid = $('#featured-products-grid');
        $grid.css('opacity', 0.5);

        $.ajax({
            url: owizoData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'owizo_filter_products',
                filter: filter,
                nonce: owizoData.nonce,
            },
            success: function(response) {
                if (response.success) {
                    $grid.html(response.data).css('opacity', 1);
                } else {
                    $grid.css('opacity', 1);
                }
            },
            error: function() {
                $grid.css('opacity', 1);
            },
        });
    });

    /* ═══════════════════════════════════════
       WISHLIST (YITH WCW integration)
    ═══════════════════════════════════════ */
    $body.on('click', '.wishlist-btn', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const productId = $btn.data('product-id');

        $.ajax({
            url: owizoData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'owizo_toggle_wishlist',
                product_id: productId,
                nonce: owizoData.nonce,
            },
            success: function(response) {
                if (response.success) {
                    const added = response.data.added;
                    $btn.attr('aria-pressed', added);
                    $btn.toggleClass('in-wishlist', added);
                    showToast(
                        added ? 'Added to wishlist!' : 'Removed from wishlist',
                        'success'
                    );
                }
            },
        });
    });

    /* ═══════════════════════════════════════
       NEWSLETTER FORM
    ═══════════════════════════════════════ */
    $('#newsletter-form').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const email = $form.find('.newsletter-input').val().trim();

        if (!email) return;

        $.ajax({
            url: owizoData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'owizo_newsletter_subscribe',
                email: email,
                nonce: owizoData.nonce,
            },
            success: function(response) {
                if (response.success) {
                    showToast('Thank you for subscribing!', 'success');
                    $form.find('.newsletter-input').val('');
                } else {
                    showToast(response.data || 'Something went wrong.', 'error');
                }
            },
        });
    });

    /* ═══════════════════════════════════════
       PRODUCT SHARE BUTTON
    ═══════════════════════════════════════ */
    $('#share-product').on('click', function() {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: window.location.href,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href)
                .then(() => showToast('Link copied to clipboard!', 'success'))
                .catch(() => {});
        }
    });

})(jQuery);
