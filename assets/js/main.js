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
        const menu = $('.menu');
        const menuToggle = menu.find('.menu-toggle');
        const mainNavigation = menu.find('.main-navigation');
        const menuToggleText = menuToggle.find('.menu-toggle-text');

        if (!menuToggle.length || !mainNavigation.length) {
            return;
        }

        function closeMenu() {
            menuToggle.attr('aria-expanded', 'false');
            menuToggle.attr('aria-label', 'Open menu');
            menuToggleText.text('Menu');
            mainNavigation.removeClass('is-open');
            mainNavigation.attr('aria-hidden', 'true');
            $('body').removeClass('menu-open');
        }

        function openMenu() {
            menuToggle.attr('aria-expanded', 'true');
            menuToggle.attr('aria-label', 'Close menu');
            menuToggleText.text('Close');
            mainNavigation.addClass('is-open');
            mainNavigation.attr('aria-hidden', 'false');
            $('body').addClass('menu-open');
        }

        function syncMenuForViewport() {
            if ($(window).width() <= 768) {
                closeMenu();
                return;
            }

            // Desktop: menu should always be available.
            menuToggle.attr('aria-expanded', 'false');
            menuToggle.attr('aria-label', 'Open menu');
            menuToggleText.text('Menu');
            mainNavigation.removeClass('is-open');
            mainNavigation.attr('aria-hidden', 'false');
            $('body').removeClass('menu-open');
        }

        syncMenuForViewport();

        menuToggle.on('click', function(e) {
            e.preventDefault();
            const isExpanded = $(this).attr('aria-expanded') === 'true';

            if (isExpanded) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.menu').length && $(window).width() <= 768) {
                closeMenu();
            }
        });

        // Close menu on escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMenu();
            }
        });

        // Close menu on window resize if desktop
        $(window).on('resize', function() {
            syncMenuForViewport();
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
     * Enhanced navigation functionality for 4-column layout
     */
    function initNavigationEnhancements() {
        const $menuItems = $('.menu-item.has-submenu');
        const $submenus = $('.submenu');
        
        // Handle hover states for desktop
        if ($(window).width() > 768) {
            $menuItems.on('mouseenter', function() {
                $(this).find('.submenu').addClass('show');
            }).on('mouseleave', function() {
                $(this).find('.submenu').removeClass('show');
            });
        }
        
        // Handle click states for mobile and accessibility
        $menuItems.find('> a').on('click', function(e) {
            if ($(window).width() <= 768) {
                return;
            }
        });
        
        // Handle keyboard navigation
        $('.primary-menu a').on('keydown', function(e) {
            const $currentItem = $(this).parent();
            const $currentMenu = $currentItem.parent();
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if ($currentItem.hasClass('has-submenu') && !$currentItem.find('.submenu').hasClass('show')) {
                        $currentItem.find('.submenu').addClass('show');
                        $currentItem.find('.submenu a').first().focus();
                    } else {
                        const $nextItem = $currentItem.next();
                        if ($nextItem.length) {
                            $nextItem.find('> a').focus();
                        }
                    }
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    const $prevItem = $currentItem.prev();
                    if ($prevItem.length) {
                        $prevItem.find('> a').focus();
                    }
                    break;
                    
                case 'ArrowRight':
                    e.preventDefault();
                    if ($currentItem.hasClass('has-submenu')) {
                        $currentItem.find('.submenu').addClass('show');
                        $currentItem.find('.submenu a').first().focus();
                    }
                    break;
                    
                case 'ArrowLeft':
                    e.preventDefault();
                    if ($currentMenu.hasClass('submenu')) {
                        $currentMenu.removeClass('show');
                        $currentMenu.siblings('a').focus();
                    }
                    break;
                    
                case 'Escape':
                    e.preventDefault();
                    $submenus.removeClass('show');
                    $menuItems.removeClass('expanded');
                    break;
            }
        });
        
        // Close submenus when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation').length) {
                $submenus.removeClass('show');
                $menuItems.removeClass('expanded');
            }
        });
        
        // Handle window resize
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                $submenus.removeClass('show');
                $menuItems.removeClass('expanded');
            }
        });
    }

    /**
     * Work category menu hover effects
     */
    function initWorkCategoryHover() {
        // Only run on projects archive page
        if (!$('body').hasClass('post-type-archive-projects') && !$('#projects-masonry').length) {
            return;
        }
        
        // Handle work submenu hover for category items
        $('.menu .submenu .submenu-item[class*="category-"]').on('mouseenter', function() {
            // Extract category class (e.g., "category-cultural")
            const categoryClass = $(this).attr('class').match(/category-([^\s]+)/);
            if (categoryClass && categoryClass[1]) {
                const category = categoryClass[1];
                
                // Add hover effect to matching projects with same category class
                $(`.project-item.${category}`).addClass('menu-hover');
            }
        }).on('mouseleave', function() {
            // Remove hover effect from all projects
            $('.project-item').removeClass('menu-hover');
        });
    }

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
     * People filtering functionality
     */
    function initPeopleFilters() {
        $('.people-filters .filter-btn').on('click', function(e) {
            e.preventDefault();
            
            const filter = $(this).data('filter');
            const people = $('.person-item');
            
            // Update active filter
            $('.people-filters .filter-btn').removeClass('active');
            $(this).addClass('active');
            
            // Filter people with animation
            if (filter === 'all') {
                people.fadeIn(300);
            } else {
                people.fadeOut(300);
                $(`.person-item[data-person-role="${filter}"]`).fadeIn(300);
            }
        });
    }

    /**
     * Category-specific enhancements
     */
    function initCategoryEnhancements() {
        // Initialize based on current page
        if ($('body').hasClass('category-projects') || $('.projects-archive').length || $('.single-project').length || $('body').hasClass('single-projects')) {
            initProjectFilters();
        }
        
        if ($('body').hasClass('category-awards') || $('.awards-archive').length) {
            initAwardProjectLinks();
        }
        
        if ($('body').hasClass('category-people') || $('.people-archive').length) {
            initPeopleFilters();
        }
    }

    /**
     * Initialize all functionality when document is ready
     */
    $(document).ready(function() {
        initMobileMenu();
        initNavigationEnhancements();
        initSmoothScrolling();
        initLazyLoading();
        initFormEnhancements();
        initCategoryFilters();
        initSearchEnhancements();
        initAccessibility();
        initPerformanceOptimizations();
        initWorkCategoryHover();
        
        // Add category-specific functionality
        initCategoryEnhancements();
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

// End of jQuery wrapper - no additional code should be added here