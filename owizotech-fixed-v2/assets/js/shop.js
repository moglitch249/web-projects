/**
 * OwizoTech — shop.js
 * WooCommerce shop interactions: gallery, quantity buttons,
 * sidebar toggle, view toggle, sticky add-to-cart bar, price slider.
 */
(function($) {
    'use strict';

    /* ═══════════════════════════════════════
       1. PRODUCT GALLERY — Single Product
    ═══════════════════════════════════════ */
    $(document).on('click', '.gallery-thumb', function() {
        const $thumb  = $(this);
        const fullSrc = $thumb.data('full');

        $('.gallery-thumb').removeClass('active');
        $thumb.addClass('active');

        const $mainImg = $('#gallery-main-img');
        $mainImg.css({ opacity: 0, transition: 'opacity 0.15s ease' });
        setTimeout(function() {
            $mainImg.attr('src', fullSrc).css('opacity', 1);
        }, 150);
    });

    /* Gallery Image Zoom on hover */
    $(document).on('mouseenter', '.gallery-main-wrap', function() {
        $(this).find('.gallery-zoom-hint').css('opacity', 1);
    }).on('mouseleave', '.gallery-main-wrap', function() {
        $(this).find('.gallery-zoom-hint').css('opacity', 0);
        // reset zoom if zoomed
        if ($(this).hasClass('zoomed')) {
            $(this).removeClass('zoomed').find('.gallery-main-img').css('transform', '');
        }
    });

    $(document).on('click', '.gallery-main-wrap', function(e) {
        const $wrap   = $(this);
        const $img    = $wrap.find('.gallery-main-img');
        const zoomed  = $wrap.hasClass('zoomed');

        if (!zoomed) {
            $wrap.addClass('zoomed');
            $img.css({ transform: 'scale(1.7)', cursor: 'zoom-out', transition: 'transform 0.3s ease' });
        } else {
            $wrap.removeClass('zoomed');
            $img.css({ transform: 'scale(1)', cursor: 'zoom-in' });
        }
    });

    $(document).on('mousemove', '.gallery-main-wrap.zoomed', function(e) {
        const rect = this.getBoundingClientRect();
        const xPct = (e.clientX - rect.left)  / rect.width;
        const yPct = (e.clientY - rect.top)   / rect.height;
        const xMove = (xPct - 0.5) * 60;
        const yMove = (yPct - 0.5) * 60;
        $(this).find('.gallery-main-img').css('transform', `scale(1.7) translate(${-xMove}%, ${-yMove}%)`);
    });

    /* ═══════════════════════════════════════
       2. QUANTITY BUTTONS
    ═══════════════════════════════════════ */
    function injectQtyButtons() {
        $('.quantity').each(function() {
            const $wrap = $(this);
            if ($wrap.find('.qty-btn').length) return; // already injected

            const $qty = $wrap.find('input.qty');
            $qty.before('<button type="button" class="qty-btn qty-btn-minus" aria-label="Decrease quantity">−</button>');
            $qty.after( '<button type="button" class="qty-btn qty-btn-plus"  aria-label="Increase quantity">+</button>');
        });
    }
    injectQtyButtons();
    $(document.body).on('updated_wc_div updated_cart_totals', injectQtyButtons);

    $(document).on('click', '.qty-btn-minus, .qty-btn-plus', function() {
        const $btn  = $(this);
        const $qty  = $btn.closest('.quantity').find('input.qty');
        let   val   = parseInt($qty.val(), 10) || 1;
        const min   = parseInt($qty.attr('min'), 10) || 1;
        const max   = parseInt($qty.attr('max'), 10) || 9999;

        val = $btn.hasClass('qty-btn-minus')
            ? Math.max(val - 1, min)
            : Math.min(val + 1, max);

        $qty.val(val).trigger('change');
    });

    /* ═══════════════════════════════════════
       3. SHOP SIDEBAR TOGGLE
    ═══════════════════════════════════════ */
    var $sidebar  = $('.shop-sidebar');
    var $layout   = $('.shop-layout');
    var $backdrop = $('#backdrop');

    $('#sidebar-toggle').on('click', function() {
        var $btn = $(this);
        var isMobile = $(window).width() <= 900;

        if (isMobile) {
            var open = !$sidebar.hasClass('open');
            $sidebar.toggleClass('open', open);
            $backdrop.toggleClass('show', open);
            $btn.attr('aria-expanded', open);
        } else {
            var collapsed = !$sidebar.hasClass('collapsed');
            $sidebar.toggleClass('collapsed', collapsed);
            $layout.toggleClass('no-sidebar', collapsed);
            $btn.attr('aria-expanded', !collapsed);
        }
    });

    $backdrop.on('click.sidebar', function() {
        if ($sidebar.hasClass('open')) {
            $sidebar.removeClass('open');
            $(this).removeClass('show');
            $('#sidebar-toggle').attr('aria-expanded', false);
        }
    });

    /* ═══════════════════════════════════════
       4. GRID / LIST VIEW TOGGLE
    ═══════════════════════════════════════ */
    $(document).on('click', '.view-btn', function() {
        var $btn  = $(this);
        var view  = $btn.data('view');
        var $grid = $('#products-list');

        $('.view-btn').removeClass('active').attr('aria-pressed', 'false');
        $btn.addClass('active').attr('aria-pressed', 'true');

        if (view === 'list') {
            $grid.removeClass('grid-auto').addClass('products-list-view');
        } else {
            $grid.removeClass('products-list-view').addClass('grid-auto');
        }

        // Save preference
        try { localStorage.setItem('owizo_shop_view', view); } catch(e) {}
    });

    // Restore preference
    try {
        var savedView = localStorage.getItem('owizo_shop_view');
        if (savedView === 'list') {
            $('.view-btn[data-view="list"]').trigger('click');
        }
    } catch(e) {}

    /* ═══════════════════════════════════════
       5. PRICE FILTER DEBOUNCE
       (WooCommerce price slider widget)
    ═══════════════════════════════════════ */
    var priceTimer;
    $(document).on('slide slidestop', '.price_slider', function() {
        clearTimeout(priceTimer);
        priceTimer = setTimeout(function() {
            $('.price_slider_amount button').first().trigger('click');
        }, 600);
    });

    /* ═══════════════════════════════════════
       6. ACTIVE FILTER TAGS — Clear individual
    ═══════════════════════════════════════ */
    $(document).on('click', '.woocommerce-widget-layered-nav-list__item--chosen a', function(e) {
        // WC handles this natively; just add visual feedback
        $(this).closest('li').css('opacity', 0.5);
    });

    $('#clear-filters').on('click', function() {
        window.location.href = window.location.pathname;
    });

    /* ═══════════════════════════════════════
       7. STICKY ADD-TO-CART BAR (Single Product)
    ═══════════════════════════════════════ */
    if ($('body').hasClass('single-product') && $('.product-add-cart').length) {

        var $addCartSection = $('.product-add-cart');
        var productName     = $('.product-detail-title').text().trim();
        var productPrice    = $('.product-detail-price').html();

        var $stickyBar = $([
            '<div class="sticky-atc-bar" id="sticky-atc-bar" aria-hidden="true">',
            '  <div class="container">',
            '    <div class="sticky-atc-inner">',
            '      <div class="sticky-product-info">',
            '        <span class="sticky-product-name">' + productName + '</span>',
            '        <div class="sticky-product-price">' + productPrice + '</div>',
            '      </div>',
            '      <button class="btn btn-primary" id="sticky-atc-btn">',
            '        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">',
            '          <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>',
            '          <line x1="3" y1="6" x2="21" y2="6"/>',
            '          <path d="M16 10a4 4 0 0 1-8 0"/>',
            '        </svg>',
            '        Add to Cart',
            '      </button>',
            '    </div>',
            '  </div>',
            '</div>'
        ].join('')).appendTo('body');

        $(window).on('scroll.stickyAtc', function() {
            var cartOffset = $addCartSection.offset().top + $addCartSection.outerHeight();
            if ($(this).scrollTop() > cartOffset) {
                $stickyBar.addClass('show').attr('aria-hidden', 'false');
            } else {
                $stickyBar.removeClass('show').attr('aria-hidden', 'true');
            }
        });

        $('#sticky-atc-btn').on('click', function() {
            $('.single_add_to_cart_button').first().trigger('click');
            $('html, body').animate({ scrollTop: $addCartSection.offset().top - 100 }, 400);
        });
    }

    /* ═══════════════════════════════════════
       8. VARIATION SWATCH ENHANCEMENT
       (Works alongside WC attribute dropdowns)
    ═══════════════════════════════════════ */
    $(document).on('change', '.variations select', function() {
        var $select  = $(this);
        var selected = $select.val();
        var attrName = $select.attr('name');

        // Highlight matching custom swatch if present
        $('.swatch-item[data-attr="' + attrName + '"]').each(function() {
            $(this).toggleClass('selected', $(this).data('value') === selected);
        });
    });

    $(document).on('click', '.swatch-item', function() {
        var $swatch  = $(this);
        var attrName = $swatch.data('attr');
        var value    = $swatch.data('value');

        $('.swatch-item[data-attr="' + attrName + '"]').removeClass('selected');
        $swatch.addClass('selected');
        $('select[name="' + attrName + '"]').val(value).trigger('change');
    });

    /* ═══════════════════════════════════════
       9. CART PAGE: UPDATE QUANTITY ON CHANGE
    ═══════════════════════════════════════ */
    if ($('body').hasClass('woocommerce-cart')) {
        var cartTimer;
        $(document).on('change', 'input.qty', function() {
            clearTimeout(cartTimer);
            cartTimer = setTimeout(function() {
                $('[name="update_cart"]').prop('disabled', false).trigger('click');
            }, 800);
        });
    }

    /* ═══════════════════════════════════════
       10. CHECKOUT: SHOW/HIDE SHIP TO FIELDS
    ═══════════════════════════════════════ */
    if ($('body').hasClass('woocommerce-checkout')) {
        var $shipToggle = $('#ship-to-different-address-checkbox');
        var $shipFields = $('#shipping_address_2, .woocommerce-shipping-fields__field-wrapper');

        function toggleShipFields() {
            if ($shipToggle.is(':checked')) {
                $shipFields.slideDown(300);
            } else {
                $shipFields.slideUp(300);
            }
        }
        toggleShipFields();
        $shipToggle.on('change', toggleShipFields);
    }

    /* ═══════════════════════════════════════
       11. PRODUCT CARD SKELETON LOADER
       (Shown while AJAX filter loads)
    ═══════════════════════════════════════ */
    function showSkeletons($grid, count) {
        var skeletonHTML = '';
        for (var i = 0; i < count; i++) {
            skeletonHTML += [
                '<div class="product-card card skeleton-card">',
                '  <div class="skeleton" style="aspect-ratio:1;width:100%;"></div>',
                '  <div style="padding:16px;display:flex;flex-direction:column;gap:8px;">',
                '    <div class="skeleton" style="height:12px;width:60%;border-radius:6px;"></div>',
                '    <div class="skeleton" style="height:16px;width:90%;border-radius:6px;"></div>',
                '    <div class="skeleton" style="height:12px;width:40%;border-radius:6px;"></div>',
                '    <div class="skeleton" style="height:36px;width:100%;border-radius:20px;margin-top:8px;"></div>',
                '  </div>',
                '</div>'
            ].join('');
        }
        $grid.html(skeletonHTML);
    }

    // Expose for use in main.js AJAX product filter
    window.owizoShowSkeletons = showSkeletons;

    /* ═══════════════════════════════════════
       12. CROSS-SELL / UPSELL SLIDER
       (Simple CSS scroll snap — no library needed)
    ═══════════════════════════════════════ */
    function initProductSlider($container) {
        if (!$container.length) return;

        var $track = $container.find('.slider-track');
        var $prev  = $container.find('.slider-prev');
        var $next  = $container.find('.slider-next');

        if (!$track.length) return;

        $next.on('click', function() {
            $track[0].scrollBy({ left: 300, behavior: 'smooth' });
        });
        $prev.on('click', function() {
            $track[0].scrollBy({ left: -300, behavior: 'smooth' });
        });

        // Show/hide arrows based on scroll position
        $track.on('scroll', function() {
            var el = this;
            $prev.toggleClass('hidden', el.scrollLeft <= 0);
            $next.toggleClass('hidden', el.scrollLeft + el.clientWidth >= el.scrollWidth - 5);
        }).trigger('scroll');
    }

    initProductSlider($('.related-products-section'));
    initProductSlider($('.upsells'));

    /* ═══════════════════════════════════════
       13. MINI CART FRAGMENT REFRESH
    ═══════════════════════════════════════ */
    $(document.body).on('added_to_cart wc_fragments_refreshed', function() {
        var $drawerContent = $('#cart-drawer-content');
        if ($drawerContent.length && $('.woocommerce-mini-cart').length) {
            $drawerContent.html($('.woocommerce-mini-cart__items').html());
        }
    });

    /* ═══════════════════════════════════════
       14. STICKY ATC BAR — CSS (injected once)
    ═══════════════════════════════════════ */
    if (!$('#sticky-atc-styles').length) {
        $('<style id="sticky-atc-styles">' +
            '.sticky-atc-bar{' +
                'position:fixed;bottom:0;left:0;right:0;z-index:300;' +
                'background:rgba(15,18,24,0.96);backdrop-filter:blur(16px);' +
                'border-top:1px solid var(--clr-border);' +
                'padding:12px 0;' +
                'transform:translateY(100%);transition:transform 0.3s cubic-bezier(0.25,0.46,0.45,0.94);' +
            '}' +
            '.sticky-atc-bar.show{transform:translateY(0);}' +
            '.sticky-atc-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;}' +
            '.sticky-product-info{display:flex;flex-direction:column;gap:2px;overflow:hidden;}' +
            '.sticky-product-name{font-weight:700;font-size:.9rem;color:var(--clr-text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}' +
            '.sticky-product-price .woocommerce-Price-amount{font-size:1.1rem;font-weight:800;color:var(--clr-text-primary);}' +
            '.qty-btn{' +
                'width:36px;height:36px;' +
                'display:flex;align-items:center;justify-content:center;' +
                'background:var(--clr-bg-elevated);' +
                'border:1px solid var(--clr-border);' +
                'border-radius:var(--radius-sm);' +
                'color:var(--clr-text-primary);font-size:1.2rem;' +
                'cursor:pointer;transition:all .15s ease;flex-shrink:0;' +
            '}' +
            '.qty-btn:hover{background:var(--clr-accent);border-color:var(--clr-accent);color:#000;}' +
            '.quantity{display:flex;align-items:center;gap:6px;}' +
            '.products-list-view{grid-template-columns:1fr!important;}' +
            '.products-list-view .product-card{display:grid;grid-template-columns:200px 1fr;}' +
            '.products-list-view .product-card-img-wrap{aspect-ratio:auto;height:100%;}' +
        '</style>').appendTo('head');
    }

})(jQuery);
