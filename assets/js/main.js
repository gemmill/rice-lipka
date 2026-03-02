/**
 * Main JavaScript file for Rice+Lipka Architects theme
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Mobile menu toggle
     */
    function initMobileMenu() {
        const menuToggle = $('.menu-toggle');
        const primaryMenu = $('.primary-menu');

        menuToggle.on('click', function() {
            const isExpanded = $(this).attr('aria-expanded') === 'true';
            
            $(this).attr('aria-expanded', !isExpanded);
            primaryMenu.toggleClass('toggled');
        });

        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation').length) {
                menuToggle.attr('aria-expanded', 'false');
                primaryMenu.removeClass('toggled');
            }
        });

        // Close menu on window resize if desktop
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                menuToggle.attr('aria-expanded', 'false');
                primaryMenu.removeClass('toggled');
            }
        });
    }

    /**
     * Smooth scrolling for anchor links
     */
    function initSmoothScrolling() {
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                location.hostname === this.hostname) {
                
                const target = $(this.hash);
                const targetElement = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                
                if (targetElement.length) {
                    $('html, body').animate({
                        scrollTop: targetElement.offset().top - 100
                    }, 1000);
                    return false;
                }
            }
        });
    }

    /**
     * Image lazy loading fallback for older browsers
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Form enhancements
     */
    function initFormEnhancements() {
        // Add focus classes to form fields
        $('input, textarea, select').on('focus', function() {
            $(this).closest('.form-field, .field-group').addClass('focused');
        }).on('blur', function() {
            $(this).closest('.form-field, .field-group').removeClass('focused');
        });

        // Form validation feedback
        $('form').on('submit', function(e) {
            const form = $(this);
            let isValid = true;

            // Check required fields
            form.find('[required]').each(function() {
                const field = $(this);
                if (!field.val().trim()) {
                    field.addClass('error');
                    isValid = false;
                } else {
                    field.removeClass('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                form.find('.error').first().focus();
            }
        });
    }

    /**
     * Category filter functionality with AJAX support
     */
    function initCategoryFilters() {
        // Handle primary category filters
        $('.category-filter, .filter-btn').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const filter = $button.data('filter');
            
            // Update active filter
            $button.siblings('.filter-btn').removeClass('active');
            $button.addClass('active');
            
            // Handle primary filter
            handlePrimaryFilter(filter, $button);
        });
    }
    
    /**
     * Handle primary category filtering
     */
    function handlePrimaryFilter(filter, $button) {
        const posts = $('.post-item');
        
        if (filter === 'all') {
            posts.fadeIn(300);
        } else {
            posts.fadeOut(300);
            $(`.post-item[data-${$button.closest('.filter-container').data('filter-type')}="${filter}"]`).fadeIn(300);
        }
    }
    
    /**
     * Update pagination after filtering
     */
    function updatePagination(maxPages, currentPage) {
        const $pagination = $('.pagination');
        
        if (maxPages <= 1) {
            $pagination.hide();
        } else {
            $pagination.show();
            // Update pagination links if needed
        }
    }

    /**
     * Search functionality enhancements
     */
    function initSearchEnhancements() {
        const searchForm = $('.search-form');
        const searchInput = searchForm.find('input[type="search"]');
        
        // Add search suggestions (if implemented)
        searchInput.on('input', function() {
            const query = $(this).val();
            
            if (query.length > 2) {
                // Implement search suggestions via AJAX if needed
                // This is a placeholder for future enhancement
            }
        });
    }

    /**
     * Accessibility enhancements
     */
    function initAccessibility() {
        // Skip link functionality
        $('.skip-link').on('click', function(e) {
            const target = $($(this).attr('href'));
            if (target.length) {
                target.focus();
                if (target.is(':focus')) {
                    return false;
                }
                target.attr('tabindex', '-1');
                target.focus();
            }
        });

        // Keyboard navigation for menus
        $('.primary-menu a').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                $(this)[0].click();
            }
        });
    }

    /**
     * Performance optimizations
     */
    function initPerformanceOptimizations() {
        // Debounce scroll events
        let scrollTimeout;
        $(window).on('scroll', function() {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(function() {
                // Handle scroll events here if needed
            }, 100);
        });

        // Preload critical images
        const criticalImages = $('img[data-preload]');
        criticalImages.each(function() {
            const img = new Image();
            img.src = $(this).attr('src');
        });
    }

    /**
     * Initialize all functionality when document is ready
     */
    $(document).ready(function() {
        initMobileMenu();
        initSmoothScrolling();
        initLazyLoading();
        initFormEnhancements();
        initCategoryFilters();
        initSearchEnhancements();
        initAccessibility();
        initPerformanceOptimizations();
    });

    /**
     * Handle window load events
     */
    $(window).on('load', function() {
        // Remove loading classes
        $('body').removeClass('loading');
        
        // Initialize any load-dependent functionality
    });

})(jQuery);
    /**
     * Project filtering functionality
     */
    function initProjectFilters() {
        $('.project-filters .filter-btn').on('click', function(e) {
            e.preventDefault();
            
            const filter = $(this).data('filter');
            const projects = $('.project-item');
            
            // Update active filter
            $('.project-filters .filter-btn').removeClass('active');
            $(this).addClass('active');
            
            // Filter projects with animation
            if (filter === 'all') {
                projects.fadeIn(300);
            } else {
                projects.fadeOut(300);
                $(`.project-item[data-project-type="${filter}"]`).fadeIn(300);
            }
        });
    }

    /**
     * Event filtering functionality
     */
    function initEventFilters() {
        $('.event-filters .filter-btn').on('click', function(e) {
            e.preventDefault();
            
            const filter = $(this).data('filter');
            const events = $('.event-item');
            
            // Update active filter
            $('.event-filters .filter-btn').removeClass('active');
            $(this).addClass('active');
            
            // Filter events with animation
            if (filter === 'all') {
                events.fadeIn(300);
            } else {
                events.fadeOut(300);
                $(`.event-item[data-event-status="${filter}"]`).fadeIn(300);
            }
        });
    }

    /**
     * Gallery lightbox functionality for projects
     */
    function initProjectGallery() {
        $('.project-thumbnail a').on('click', function(e) {
            // Check if this is a gallery item
            const galleryCount = $(this).siblings('.gallery-count');
            if (galleryCount.length > 0) {
                e.preventDefault();
                // Implement lightbox functionality here
                // This is a placeholder for future gallery enhancement
                console.log('Gallery lightbox would open here');
            }
        });
    }

    /**
     * Event countdown functionality
     */
    function initEventCountdown() {
        $('.event-date.upcoming-date').each(function() {
            const eventDate = new Date($(this).attr('datetime'));
            const now = new Date();
            const timeDiff = eventDate.getTime() - now.getTime();
            
            if (timeDiff > 0) {
                const daysLeft = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if (daysLeft <= 7) {
                    $(this).after(`<span class="countdown-notice">In ${daysLeft} day${daysLeft !== 1 ? 's' : ''}</span>`);
                }
            }
        });
    }

    /**
     * Award project linking functionality
     */
    function initAwardProjectLinks() {
        $('.project-link, .view-project').on('click', function(e) {
            // Add smooth transition effect
            $(this).addClass('loading');
            
            // Remove loading class after a short delay (visual feedback)
            setTimeout(() => {
                $(this).removeClass('loading');
            }, 500);
        });
    }

    /**
     * Category-specific enhancements
     */
    function initCategoryEnhancements() {
        // Initialize based on current page
        if ($('body').hasClass('category-projects') || $('.projects-archive').length) {
            initProjectFilters();
            initProjectGallery();
        }
        
        if ($('body').hasClass('category-events') || $('.events-archive').length) {
            initEventFilters();
            initEventCountdown();
        }
        
        if ($('body').hasClass('category-awards') || $('.awards-archive').length) {
            initAwardProjectLinks();
        }
    }

    /**
     * Update the document ready function to include new functionality
     */
    $(document).ready(function() {
        initMobileMenu();
        initSmoothScrolling();
        initLazyLoading();
        initFormEnhancements();
        initCategoryFilters();
        initSearchEnhancements();
        initAccessibility();
        initPerformanceOptimizations();
        
        // Add category-specific functionality
        initCategoryEnhancements();
    });