/**
 * Responsive Enhancements for Rice+Lipka WordPress Theme
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Responsive breakpoints
    const breakpoints = {
        mobile: 767,
        tablet: 1024,
        desktop: 1025
    };

    // Get current breakpoint
    function getCurrentBreakpoint() {
        const width = window.innerWidth;
        if (width <= breakpoints.mobile) return 'mobile';
        if (width <= breakpoints.tablet) return 'tablet';
        return 'desktop';
    }

    // Mobile navigation toggle
    function initMobileNavigation() {
        const $toggle = $('.mobile-menu-toggle');
        const $nav = $('.main-navigation');
        
        if ($toggle.length && $nav.length) {
            $toggle.on('click', function(e) {
                e.preventDefault();
                $nav.toggleClass('active');
                $(this).toggleClass('active');
                
                // Update aria attributes for accessibility
                const isExpanded = $nav.hasClass('active');
                $(this).attr('aria-expanded', isExpanded);
                $nav.attr('aria-hidden', !isExpanded);
            });
            
            // Close menu on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $nav.hasClass('active')) {
                    $nav.removeClass('active');
                    $toggle.removeClass('active').attr('aria-expanded', 'false');
                    $nav.attr('aria-hidden', 'true');
                }
            });
        }
    }

    // Touch-friendly interactions for mobile
    function initTouchEnhancements() {
        if ('ontouchstart' in window) {
            // Add touch class to body
            $('body').addClass('touch-device');
            
            // Enhanced touch targets for buttons
            $('.news-read-more, .view-project-btn, .action-btn').each(function() {
                const $btn = $(this);
                const minTouchTarget = 44; // 44px minimum touch target
                
                if ($btn.outerHeight() < minTouchTarget) {
                    $btn.css('min-height', minTouchTarget + 'px');
                }
            });
            
            // Touch-friendly gallery navigation
            $('.gallery-item').on('touchstart', function() {
                $(this).addClass('touch-active');
            }).on('touchend', function() {
                $(this).removeClass('touch-active');
            });
        }
    }

    // Responsive image loading
    function initResponsiveImages() {
        const $images = $('img[data-src-mobile], img[data-src-tablet], img[data-src-desktop]');
        
        function loadAppropriateImage() {
            const currentBreakpoint = getCurrentBreakpoint();
            
            $images.each(function() {
                const $img = $(this);
                let newSrc = '';
                
                switch (currentBreakpoint) {
                    case 'mobile':
                        newSrc = $img.data('src-mobile') || $img.data('src-tablet') || $img.data('src-desktop');
                        break;
                    case 'tablet':
                        newSrc = $img.data('src-tablet') || $img.data('src-desktop') || $img.data('src-mobile');
                        break;
                    case 'desktop':
                        newSrc = $img.data('src-desktop') || $img.data('src-tablet') || $img.data('src-mobile');
                        break;
                }
                
                if (newSrc && $img.attr('src') !== newSrc) {
                    $img.attr('src', newSrc);
                }
            });
        }
        
        // Load appropriate images on resize
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(loadAppropriateImage, 250);
        });
        
        // Initial load
        loadAppropriateImage();
    }

    // Responsive typography adjustments
    function initResponsiveTypography() {
        function adjustTypography() {
            const currentBreakpoint = getCurrentBreakpoint();
            const $body = $('body');
            
            // Remove existing breakpoint classes
            $body.removeClass('breakpoint-mobile breakpoint-tablet breakpoint-desktop');
            
            // Add current breakpoint class
            $body.addClass('breakpoint-' + currentBreakpoint);
            
            // Adjust line heights for better readability on mobile
            if (currentBreakpoint === 'mobile') {
                $('.news-body, .project-description, .event-description, .award-description').css('line-height', '1.7');
            } else {
                $('.news-body, .project-description, .event-description, .award-description').css('line-height', '');
            }
        }
        
        // Adjust on resize
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(adjustTypography, 100);
        });
        
        // Initial adjustment
        adjustTypography();
    }

    // Responsive modal handling
    function initResponsiveModals() {
        const $modals = $('.certificate-modal, .share-modal, .calendar-modal');
        
        function adjustModalSize() {
            const currentBreakpoint = getCurrentBreakpoint();
            
            $modals.each(function() {
                const $modal = $(this);
                const $content = $modal.find('.modal-content, .certificate-modal-content, .share-modal-content, .calendar-modal-content');
                
                if (currentBreakpoint === 'mobile') {
                    $content.css({
                        'max-width': 'calc(100vw - 2rem)',
                        'max-height': 'calc(100vh - 2rem)',
                        'margin': '1rem'
                    });
                } else {
                    $content.css({
                        'max-width': '',
                        'max-height': '',
                        'margin': ''
                    });
                }
            });
        }
        
        // Adjust on modal open
        $modals.on('show', adjustModalSize);
        
        // Adjust on resize
        $(window).on('resize', adjustModalSize);
    }

    // Responsive countdown timer
    function initResponsiveCountdown() {
        const $countdownTimers = $('.countdown-timer');
        
        function adjustCountdownLayout() {
            const currentBreakpoint = getCurrentBreakpoint();
            
            $countdownTimers.each(function() {
                const $timer = $(this);
                const $units = $timer.find('.countdown-unit');
                
                if (currentBreakpoint === 'mobile') {
                    // Stack countdown units vertically on mobile
                    $timer.css('grid-template-columns', '1fr');
                    $units.css({
                        'display': 'flex',
                        'justify-content': 'space-between',
                        'align-items': 'center'
                    });
                } else if (currentBreakpoint === 'tablet') {
                    // 2x2 grid on tablet
                    $timer.css('grid-template-columns', 'repeat(2, 1fr)');
                    $units.css({
                        'display': 'flex',
                        'flex-direction': 'column',
                        'text-align': 'center'
                    });
                } else {
                    // 4 columns on desktop
                    $timer.css('grid-template-columns', 'repeat(4, 1fr)');
                    $units.css({
                        'display': 'flex',
                        'flex-direction': 'column',
                        'text-align': 'center'
                    });
                }
            });
        }
        
        // Adjust on resize
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(adjustCountdownLayout, 100);
        });
        
        // Initial adjustment
        adjustCountdownLayout();
    }

    // Responsive gallery handling
    function initResponsiveGallery() {
        const $galleries = $('.gallery-grid, .awards-grid');
        
        function adjustGalleryColumns() {
            const currentBreakpoint = getCurrentBreakpoint();
            
            $galleries.each(function() {
                const $gallery = $(this);
                
                switch (currentBreakpoint) {
                    case 'mobile':
                        $gallery.css('grid-template-columns', '1fr');
                        break;
                    case 'tablet':
                        $gallery.css('grid-template-columns', 'repeat(2, 1fr)');
                        break;
                    case 'desktop':
                        $gallery.css('grid-template-columns', 'repeat(3, 1fr)');
                        break;
                }
            });
        }
        
        // Adjust on resize
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(adjustGalleryColumns, 100);
        });
        
        // Initial adjustment
        adjustGalleryColumns();
    }

    // Responsive form enhancements
    function initResponsiveForms() {
        const $forms = $('form');
        
        $forms.each(function() {
            const $form = $(this);
            const $inputs = $form.find('input, textarea, select');
            
            // Add touch-friendly styling for mobile
            if ('ontouchstart' in window) {
                $inputs.addClass('touch-input');
            }
            
            // Adjust input sizes for mobile
            function adjustInputSizes() {
                const currentBreakpoint = getCurrentBreakpoint();
                
                if (currentBreakpoint === 'mobile') {
                    $inputs.css({
                        'font-size': '16px', // Prevent zoom on iOS
                        'min-height': '44px'  // Touch-friendly height
                    });
                } else {
                    $inputs.css({
                        'font-size': '',
                        'min-height': ''
                    });
                }
            }
            
            $(window).on('resize', adjustInputSizes);
            adjustInputSizes();
        });
    }

    // Responsive table handling
    function initResponsiveTables() {
        const $tables = $('table');
        
        $tables.each(function() {
            const $table = $(this);
            
            // Wrap tables for horizontal scrolling on mobile
            if (!$table.parent().hasClass('table-responsive')) {
                $table.wrap('<div class="table-responsive"></div>');
            }
        });
        
        // Add responsive table styles
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                .table-responsive {
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }
                
                @media (max-width: 767px) {
                    .table-responsive table {
                        min-width: 600px;
                        font-size: 0.875rem;
                    }
                }
            `)
            .appendTo('head');
    }

    // Initialize all responsive enhancements
    function init() {
        initMobileNavigation();
        initTouchEnhancements();
        initResponsiveImages();
        initResponsiveTypography();
        initResponsiveModals();
        initResponsiveCountdown();
        initResponsiveGallery();
        initResponsiveForms();
        initResponsiveTables();
        
        // Trigger custom event when responsive enhancements are ready
        $(document).trigger('ricelipka:responsive:ready');
    }

    // Initialize when document is ready
    $(document).ready(init);

    // Expose utilities for other scripts
    window.RiceLipkaResponsive = {
        getCurrentBreakpoint: getCurrentBreakpoint,
        breakpoints: breakpoints
    };

})(jQuery);