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
                e.preventDefault();
                const $submenu = $(this).siblings('.submenu');
                const $parentItem = $(this).parent();
                
                // Toggle current submenu
                $submenu.toggleClass('show');
                $parentItem.toggleClass('expanded');
                
                // Close other submenus
                $menuItems.not($parentItem).find('.submenu').removeClass('show');
                $menuItems.not($parentItem).removeClass('expanded');
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
     * Gallery lightbox functionality for projects
     */
    function initProjectGallery() {
        // Initialize lightbox for project galleries
        if ($('.project-gallery').length > 0) {
            console.log('Initializing project gallery lightbox');
            initLightbox();
        }
    }

    /**
     * Advanced lightbox with swipe and keyboard navigation
     */
    function initLightbox() {
        console.log('Setting up lightbox functionality');
        let currentIndex = 0;
        let images = [];
        let isOpen = false;
        let startX = 0;
        let startY = 0;
        let isDragging = false;

        // Create lightbox HTML structure
        const lightboxHTML = `
            <div id="project-lightbox" class="lightbox-overlay">
                <div class="lightbox-container">
                    <button class="lightbox-close" aria-label="Close lightbox">&times;</button>
                    <button class="lightbox-prev" aria-label="Previous image">&#8249;</button>
                    <button class="lightbox-next" aria-label="Next image">&#8250;</button>
                    <div class="lightbox-content">
                        <img class="lightbox-image" src="" alt="" />
                        <div class="lightbox-caption"></div>
                    </div>
                    <div class="lightbox-counter">
                        <span class="current">1</span> / <span class="total">1</span>
                    </div>
                </div>
            </div>
        `;

        // Add lightbox to body if it doesn't exist
        if ($('#project-lightbox').length === 0) {
            $('body').append(lightboxHTML);
        }

        const $lightbox = $('#project-lightbox');
        const $lightboxImage = $('.lightbox-image');
        const $lightboxCaption = $('.lightbox-caption');
        const $currentCounter = $('.lightbox-counter .current');
        const $totalCounter = $('.lightbox-counter .total');

        // Handle gallery clicks
        $('.gallery-item a, .project-gallery a').on('click', function(e) {
            e.preventDefault();
            
            // Get all images in the current gallery
            const $gallery = $(this).closest('.project-gallery, .gallery-grid');
            images = [];
            
            $gallery.find('a').each(function(index) {
                const $img = $(this).find('img');
                const $caption = $(this).siblings('.gallery-caption');
                
                images.push({
                    src: $(this).attr('href'),
                    alt: $img.attr('alt') || '',
                    caption: $caption.length ? $caption.text() : ''
                });
                
                // Set current index if this is the clicked image
                if (this === e.currentTarget) {
                    currentIndex = index;
                }
            });

            if (images.length > 0) {
                openLightbox();
            }
        });

        // Open lightbox
        function openLightbox() {
            isOpen = true;
            $lightbox.addClass('active');
            $('body').addClass('lightbox-open');
            $totalCounter.text(images.length);
            showImage(currentIndex);
            
            // Focus management for accessibility
            $lightbox.focus();
        }

        // Close lightbox
        function closeLightbox() {
            isOpen = false;
            $lightbox.removeClass('active');
            $('body').removeClass('lightbox-open');
            
            // Return focus to the trigger element
            setTimeout(() => {
                $('.gallery-item a, .project-gallery a').eq(currentIndex).focus();
            }, 100);
        }

        // Show specific image
        function showImage(index) {
            if (index < 0 || index >= images.length) return;
            
            currentIndex = index;
            const image = images[currentIndex];
            
            // Update image
            $lightboxImage.attr('src', image.src).attr('alt', image.alt);
            
            // Update caption
            if (image.caption) {
                $lightboxCaption.text(image.caption).show();
            } else {
                $lightboxCaption.hide();
            }
            
            // Update counter
            $currentCounter.text(currentIndex + 1);
            
            // Update navigation button states
            $('.lightbox-prev').toggleClass('disabled', currentIndex === 0);
            $('.lightbox-next').toggleClass('disabled', currentIndex === images.length - 1);
        }

        // Navigate to previous image
        function prevImage() {
            if (currentIndex > 0) {
                showImage(currentIndex - 1);
            }
        }

        // Navigate to next image
        function nextImage() {
            if (currentIndex < images.length - 1) {
                showImage(currentIndex + 1);
            }
        }

        // Click event handlers
        $('.lightbox-close').on('click', closeLightbox);
        $('.lightbox-prev').on('click', prevImage);
        $('.lightbox-next').on('click', nextImage);

        // Click outside to close
        $lightbox.on('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });

        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if (!isOpen) return;
            
            switch(e.key) {
                case 'Escape':
                    closeLightbox();
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    prevImage();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    nextImage();
                    break;
            }
        });

        // Touch/swipe support
        let touchStartX = 0;
        let touchStartY = 0;
        let touchEndX = 0;
        let touchEndY = 0;

        $lightbox.on('touchstart', function(e) {
            touchStartX = e.originalEvent.touches[0].clientX;
            touchStartY = e.originalEvent.touches[0].clientY;
        });

        $lightbox.on('touchend', function(e) {
            touchEndX = e.originalEvent.changedTouches[0].clientX;
            touchEndY = e.originalEvent.changedTouches[0].clientY;
            
            const deltaX = touchEndX - touchStartX;
            const deltaY = touchEndY - touchStartY;
            const minSwipeDistance = 50;
            
            // Horizontal swipe
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > minSwipeDistance) {
                if (deltaX > 0) {
                    // Swipe right - previous image
                    prevImage();
                } else {
                    // Swipe left - next image
                    nextImage();
                }
            }
            
            // Vertical swipe down to close
            if (deltaY > minSwipeDistance && Math.abs(deltaX) < minSwipeDistance) {
                closeLightbox();
            }
        });

        // Mouse drag support for desktop
        let mouseStartX = 0;
        let isDragging = false;

        $lightbox.on('mousedown', function(e) {
            if (e.target.classList.contains('lightbox-image')) {
                mouseStartX = e.clientX;
                isDragging = true;
                e.preventDefault();
            }
        });

        $(document).on('mousemove', function(e) {
            if (isDragging) {
                const deltaX = e.clientX - mouseStartX;
                // Add visual feedback for drag
                $lightboxImage.css('transform', `translateX(${deltaX * 0.3}px)`);
            }
        });

        $(document).on('mouseup', function(e) {
            if (isDragging) {
                const deltaX = e.clientX - mouseStartX;
                const minDragDistance = 100;
                
                // Reset image position
                $lightboxImage.css('transform', '');
                
                if (Math.abs(deltaX) > minDragDistance) {
                    if (deltaX > 0) {
                        prevImage();
                    } else {
                        nextImage();
                    }
                }
                
                isDragging = false;
            }
        });

        // Prevent image dragging
        $lightboxImage.on('dragstart', function(e) {
            e.preventDefault();
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
            initProjectGallery();
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
        
        // Always initialize project gallery if it exists on the page
        if ($('.project-gallery').length > 0) {
            initProjectGallery();
        }
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