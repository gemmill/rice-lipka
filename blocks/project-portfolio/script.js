/**
 * Project Portfolio Block JavaScript
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initProjectPortfolio();
    });

    /**
     * Initialize all project portfolio blocks
     */
    function initProjectPortfolio() {
        const portfolioBlocks = document.querySelectorAll('.project-portfolio-block');
        
        portfolioBlocks.forEach(function(block) {
            initGalleryLightbox(block);
            initGalleryFiltering(block);
            initProgressAnimations(block);
            initAccessibility(block);
            initPerformanceOptimizations(block);
        });
    }

    /**
     * Initialize gallery lightbox functionality
     */
    function initGalleryLightbox(block) {
        const galleryItems = block.querySelectorAll('.gallery-item img');
        
        if (galleryItems.length === 0) return;

        // Create lightbox container
        const lightbox = createLightboxElement();
        document.body.appendChild(lightbox);

        // Add click handlers to gallery items
        galleryItems.forEach(function(img, imgIndex) {
            img.addEventListener('click', function(e) {
                e.preventDefault();
                openLightbox(lightbox, galleryItems, imgIndex);
            });

            // Add keyboard support
            img.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openLightbox(lightbox, galleryItems, imgIndex);
                }
            });

            // Make images focusable
            img.setAttribute('tabindex', '0');
            img.setAttribute('role', 'button');
            img.setAttribute('aria-label', 'Open image in lightbox: ' + (img.alt || 'Project image'));
        });
    }

    /**
     * Create lightbox DOM element
     */
    function createLightboxElement() {
        const lightbox = document.createElement('div');
        lightbox.className = 'project-lightbox';
        lightbox.setAttribute('role', 'dialog');
        lightbox.setAttribute('aria-modal', 'true');
        lightbox.setAttribute('aria-label', 'Image gallery lightbox');

        lightbox.innerHTML = `
            <div class="lightbox-content">
                <img class="lightbox-image" src="" alt="" />
                <button class="lightbox-close" aria-label="Close lightbox">&times;</button>
                <button class="lightbox-nav lightbox-prev" aria-label="Previous image">&#8249;</button>
                <button class="lightbox-nav lightbox-next" aria-label="Next image">&#8250;</button>
            </div>
        `;

        // Add event listeners
        const closeBtn = lightbox.querySelector('.lightbox-close');
        const prevBtn = lightbox.querySelector('.lightbox-prev');
        const nextBtn = lightbox.querySelector('.lightbox-next');

        closeBtn.addEventListener('click', function() {
            closeLightbox(lightbox);
        });

        prevBtn.addEventListener('click', function() {
            navigateLightbox(lightbox, -1);
        });

        nextBtn.addEventListener('click', function() {
            navigateLightbox(lightbox, 1);
        });

        // Close on background click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox(lightbox);
            }
        });

        // Keyboard navigation
        lightbox.addEventListener('keydown', function(e) {
            switch(e.key) {
                case 'Escape':
                    closeLightbox(lightbox);
                    break;
                case 'ArrowLeft':
                    navigateLightbox(lightbox, -1);
                    break;
                case 'ArrowRight':
                    navigateLightbox(lightbox, 1);
                    break;
            }
        });

        return lightbox;
    }

    /**
     * Open lightbox with specific image
     */
    function openLightbox(lightbox, images, index) {
        const lightboxImg = lightbox.querySelector('.lightbox-image');
        const currentImg = images[index];
        
        // Set image source and alt text
        lightboxImg.src = currentImg.dataset.full || currentImg.src;
        lightboxImg.alt = currentImg.alt || 'Project image';
        
        // Store current index and images array
        lightbox.currentIndex = index;
        lightbox.images = images;
        
        // Show lightbox
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Focus management
        const closeBtn = lightbox.querySelector('.lightbox-close');
        closeBtn.focus();
        
        // Update navigation buttons visibility
        updateNavigationButtons(lightbox);
        
        // Announce to screen readers
        announceToScreenReader('Image ' + (index + 1) + ' of ' + images.length + ' opened in lightbox');
    }

    /**
     * Close lightbox
     */
    function closeLightbox(lightbox) {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
        
        // Return focus to the image that opened the lightbox
        if (lightbox.images && lightbox.images[lightbox.currentIndex]) {
            lightbox.images[lightbox.currentIndex].focus();
        }
        
        announceToScreenReader('Lightbox closed');
    }

    /**
     * Navigate lightbox (previous/next)
     */
    function navigateLightbox(lightbox, direction) {
        if (!lightbox.images) return;
        
        const newIndex = lightbox.currentIndex + direction;
        const maxIndex = lightbox.images.length - 1;
        
        // Wrap around navigation
        let targetIndex;
        if (newIndex < 0) {
            targetIndex = maxIndex;
        } else if (newIndex > maxIndex) {
            targetIndex = 0;
        } else {
            targetIndex = newIndex;
        }
        
        // Update lightbox
        const lightboxImg = lightbox.querySelector('.lightbox-image');
        const targetImg = lightbox.images[targetIndex];
        
        lightboxImg.src = targetImg.dataset.full || targetImg.src;
        lightboxImg.alt = targetImg.alt || 'Project image';
        lightbox.currentIndex = targetIndex;
        
        updateNavigationButtons(lightbox);
        
        // Announce to screen readers
        announceToScreenReader('Image ' + (targetIndex + 1) + ' of ' + lightbox.images.length);
    }

    /**
     * Update navigation button states
     */
    function updateNavigationButtons(lightbox) {
        const prevBtn = lightbox.querySelector('.lightbox-prev');
        const nextBtn = lightbox.querySelector('.lightbox-next');
        
        // Show/hide navigation buttons based on image count
        if (lightbox.images.length <= 1) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
        }
    }

    /**
     * Initialize gallery filtering
     */
    function initGalleryFiltering(block) {
        const gallery = block.querySelector('.project-gallery');
        if (!gallery) return;

        // Create filter buttons based on image data attributes or categories
        const filterContainer = createFilterButtons(block);
        if (filterContainer) {
            gallery.insertBefore(filterContainer, gallery.firstChild);
            setupFilterFunctionality(block, filterContainer);
        }
    }

    /**
     * Create filter buttons
     */
    function createFilterButtons(block) {
        const galleryItems = block.querySelectorAll('.gallery-item img');
        if (galleryItems.length <= 3) return null; // Don't show filters for small galleries

        const categories = new Set(['all']);
        
        // Extract categories from image alt text or data attributes
        galleryItems.forEach(function(img) {
            const category = img.dataset.category || extractCategoryFromAlt(img.alt);
            if (category) {
                categories.add(category);
            }
        });

        if (categories.size <= 2) return null; // Only 'all' and one other category

        const filterContainer = document.createElement('div');
        filterContainer.className = 'gallery-filter';
        filterContainer.setAttribute('role', 'group');
        filterContainer.setAttribute('aria-label', 'Filter gallery images');

        categories.forEach(function(category) {
            const button = document.createElement('button');
            button.className = 'filter-button';
            button.textContent = formatCategoryName(category);
            button.dataset.filter = category;
            button.setAttribute('aria-pressed', category === 'all' ? 'true' : 'false');
            
            if (category === 'all') {
                button.classList.add('active');
            }
            
            filterContainer.appendChild(button);
        });

        return filterContainer;
    }

    /**
     * Setup filter functionality
     */
    function setupFilterFunctionality(block, filterContainer) {
        const filterButtons = filterContainer.querySelectorAll('.filter-button');
        const galleryItems = block.querySelectorAll('.gallery-item');

        filterButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Update active button
                filterButtons.forEach(function(btn) {
                    btn.classList.remove('active');
                    btn.setAttribute('aria-pressed', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-pressed', 'true');
                
                // Filter gallery items
                filterGalleryItems(galleryItems, filter);
                
                announceToScreenReader('Gallery filtered to show ' + this.textContent.toLowerCase() + ' images');
            });
        });
    }

    /**
     * Filter gallery items
     */
    function filterGalleryItems(items, filter) {
        items.forEach(function(item) {
            const img = item.querySelector('img');
            const category = img.dataset.category || extractCategoryFromAlt(img.alt) || 'all';
            
            if (filter === 'all' || category === filter) {
                item.style.display = 'block';
                item.setAttribute('aria-hidden', 'false');
            } else {
                item.style.display = 'none';
                item.setAttribute('aria-hidden', 'true');
            }
        });
    }

    /**
     * Extract category from alt text
     */
    function extractCategoryFromAlt(alt) {
        if (!alt) return null;
        
        const keywords = {
            'exterior': ['exterior', 'outside', 'facade', 'building'],
            'interior': ['interior', 'inside', 'room', 'lobby'],
            'detail': ['detail', 'close-up', 'closeup', 'feature'],
            'construction': ['construction', 'progress', 'building', 'work']
        };
        
        const lowerAlt = alt.toLowerCase();
        
        for (const [category, words] of Object.entries(keywords)) {
            if (words.some(word => lowerAlt.includes(word))) {
                return category;
            }
        }
        
        return null;
    }

    /**
     * Format category name for display
     */
    function formatCategoryName(category) {
        return category.charAt(0).toUpperCase() + category.slice(1).replace(/[-_]/g, ' ');
    }

    /**
     * Initialize progress bar animations
     */
    function initProgressAnimations(block) {
        const progressBars = block.querySelectorAll('.progress-fill');
        
        progressBars.forEach(function(bar) {
            const targetWidth = bar.style.width;
            bar.style.width = '0%';
            
            // Animate when in viewport
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        setTimeout(function() {
                            bar.style.width = targetWidth;
                        }, 300);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(bar);
        });
    }

    /**
     * Initialize accessibility features
     */
    function initAccessibility(block) {
        // Add ARIA labels to interactive elements
        const galleryItems = block.querySelectorAll('.gallery-item');
        galleryItems.forEach(function(item, index) {
            const img = item.querySelector('img');
            if (img) {
                img.setAttribute('aria-describedby', 'gallery-instructions');
            }
        });

        // Add instructions for screen readers
        if (galleryItems.length > 0 && !document.getElementById('gallery-instructions')) {
            const instructions = document.createElement('div');
            instructions.id = 'gallery-instructions';
            instructions.className = 'sr-only';
            instructions.textContent = 'Press Enter or Space to open image in lightbox. Use arrow keys to navigate between images.';
            document.body.appendChild(instructions);
        }

        // Ensure proper heading hierarchy
        const headings = block.querySelectorAll('h1, h2, h3, h4, h5, h6');
        headings.forEach(function(heading) {
            if (!heading.id) {
                heading.id = 'heading-' + Math.random().toString(36).substring(2, 11);
            }
        });
    }

    /**
     * Announce to screen readers
     */
    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;
        
        document.body.appendChild(announcement);
        
        setTimeout(function() {
            document.body.removeChild(announcement);
        }, 1000);
    }

    /**
     * Initialize performance optimizations
     */
    function initPerformanceOptimizations(block) {
        // Initialize lazy loading for gallery images
        initLazyLoadingForGallery(block);
        
        // Optimize image loading priority
        optimizeImageLoadingPriority(block);
        
        // Add intersection observer for gallery visibility
        initGalleryVisibilityObserver(block);
        
        // Preload critical images
        preloadCriticalImages(block);
        
        // Add performance monitoring
        monitorGalleryPerformance(block);
    }

    /**
     * Initialize lazy loading for gallery images
     */
    function initLazyLoadingForGallery(block) {
        const lazyImages = block.querySelectorAll('img[data-lazy]');
        
        if (!('IntersectionObserver' in window)) {
            // Fallback for older browsers
            lazyImages.forEach(function(img) {
                loadLazyImage(img);
            });
            return;
        }
        
        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    loadLazyImage(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });
        
        lazyImages.forEach(function(img) {
            imageObserver.observe(img);
        });
    }

    /**
     * Load lazy image with performance optimizations
     */
    function loadLazyImage(img) {
        // Create image loader for preloading
        const imageLoader = new Image();
        
        imageLoader.onload = function() {
            // Update src and srcset
            if (img.dataset.src) {
                img.src = img.dataset.src;
            }
            if (img.dataset.srcset) {
                img.srcset = img.dataset.srcset;
            }
            
            // Handle WebP if supported
            if (img.dataset.webp && supportsWebP()) {
                img.src = img.dataset.webp;
                if (img.dataset.webpSrcset) {
                    img.srcset = img.dataset.webpSrcset;
                }
            }
            
            // Add loaded class and remove placeholder
            img.classList.add('lazy-loaded');
            img.classList.remove('loading-placeholder');
            
            // Trigger custom event
            img.dispatchEvent(new CustomEvent('imageLoaded', {
                detail: { src: img.src, loadTime: performance.now() }
            }));
        };
        
        imageLoader.onerror = function() {
            img.classList.add('lazy-error');
            img.alt = 'Failed to load image';
        };
        
        // Start loading
        imageLoader.src = img.dataset.src || img.src;
    }

    /**
     * Check WebP support
     */
    function supportsWebP() {
        return document.documentElement.classList.contains('webp-supported');
    }

    /**
     * Optimize image loading priority
     */
    function optimizeImageLoadingPriority(block) {
        const images = block.querySelectorAll('img');
        
        images.forEach(function(img, index) {
            // First 2 images get high priority
            if (index < 2) {
                img.loading = 'eager';
                if ('fetchPriority' in HTMLImageElement.prototype) {
                    img.fetchPriority = 'high';
                }
            } else {
                img.loading = 'lazy';
                img.fetchPriority = 'low';
            }
        });
    }

    /**
     * Initialize gallery visibility observer
     */
    function initGalleryVisibilityObserver(block) {
        const gallery = block.querySelector('.gallery-grid');
        if (!gallery) return;
        
        const galleryObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    // Add visible class for animations
                    gallery.classList.add('gallery-visible');
                    
                    // Stagger image loading
                    const images = gallery.querySelectorAll('img[data-lazy]');
                    images.forEach(function(img, index) {
                        setTimeout(function() {
                            loadLazyImage(img);
                            img.classList.add('gallery-image-loaded');
                        }, index * 100); // 100ms stagger
                    });
                    
                    // Stop observing
                    galleryObserver.unobserve(gallery);
                    
                    // Trigger gallery loaded event
                    gallery.dispatchEvent(new CustomEvent('galleryLoaded', {
                        detail: { imageCount: images.length }
                    }));
                }
            });
        }, {
            rootMargin: '100px 0px',
            threshold: 0.1
        });
        
        galleryObserver.observe(gallery);
    }

    /**
     * Preload critical images
     */
    function preloadCriticalImages(block) {
        const criticalImages = block.querySelectorAll('img[loading="eager"]');
        
        criticalImages.forEach(function(img) {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = img.src || img.dataset.src;
            
            // Add to head
            document.head.appendChild(link);
        });
    }

    /**
     * Monitor gallery performance
     */
    function monitorGalleryPerformance(block) {
        const gallery = block.querySelector('.gallery-grid');
        if (!gallery) return;
        
        let imageLoadCount = 0;
        let totalLoadTime = 0;
        const startTime = performance.now();
        
        // Monitor individual image loads
        gallery.addEventListener('imageLoaded', function(e) {
            imageLoadCount++;
            totalLoadTime += e.detail.loadTime - startTime;
            
            // Calculate average load time
            const avgLoadTime = totalLoadTime / imageLoadCount;
            
            // Log performance metrics (development only)
            if (window.console && window.console.log) {
                console.log('Gallery Performance:', {
                    imagesLoaded: imageLoadCount,
                    averageLoadTime: Math.round(avgLoadTime) + 'ms',
                    totalImages: gallery.querySelectorAll('img').length
                });
            }
        });
        
        // Monitor gallery load completion
        gallery.addEventListener('galleryLoaded', function(e) {
            const loadTime = performance.now() - startTime;
            
            // Trigger custom performance event
            if (window.RiceLipkaPerformance) {
                document.dispatchEvent(new CustomEvent('ricelipka:performance:gallery', {
                    detail: { loadTime: loadTime, imageCount: e.detail.imageCount }
                }));
            }
        });
    }

    // Handle block editor preview updates
    if (window.wp && window.wp.data) {
        const { subscribe } = window.wp.data;
        
        subscribe(function() {
            // Re-initialize when blocks are updated in editor
            setTimeout(function() {
                const newBlocks = document.querySelectorAll('.project-portfolio-block:not([data-initialized])');
                newBlocks.forEach(function(block) {
                    block.setAttribute('data-initialized', 'true');
                    initGalleryLightbox(block);
                    initGalleryFiltering(block);
                    initProgressAnimations(block);
                    initAccessibility(block);
                    initPerformanceOptimizations(block);
                });
            }, 100);
        });
    }

    /**
     * Initialize performance optimizations
     */
    function initPerformanceOptimizations(block) {
        // Initialize lazy loading for gallery images
        initLazyLoadingForGallery(block);
        
        // Optimize image loading priority
        optimizeImageLoadingPriority(block);
        
        // Add intersection observer for gallery visibility
        initGalleryVisibilityObserver(block);
        
        // Preload critical images
        preloadCriticalImages(block);
        
        // Add performance monitoring
        monitorGalleryPerformance(block);
    }

    /**
     * Initialize lazy loading for gallery images
     */
    function initLazyLoadingForGallery(block) {
        const lazyImages = block.querySelectorAll('img[data-lazy]');
        
        if (!('IntersectionObserver' in window)) {
            // Fallback for older browsers
            lazyImages.forEach(function(img) {
                loadLazyImage(img);
            });
            return;
        }
        
        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    loadLazyImage(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });
        
        lazyImages.forEach(function(img) {
            imageObserver.observe(img);
        });
    }

    /**
     * Load lazy image with performance optimizations
     */
    function loadLazyImage(img) {
        // Create image loader for preloading
        const imageLoader = new Image();
        
        imageLoader.onload = function() {
            // Update src and srcset
            if (img.dataset.src) {
                img.src = img.dataset.src;
            }
            if (img.dataset.srcset) {
                img.srcset = img.dataset.srcset;
            }
            
            // Handle WebP if supported
            if (img.dataset.webp && supportsWebP()) {
                img.src = img.dataset.webp;
                if (img.dataset.webpSrcset) {
                    img.srcset = img.dataset.webpSrcset;
                }
            }
            
            // Add loaded class and remove placeholder
            img.classList.add('lazy-loaded');
            img.classList.remove('loading-placeholder');
            
            // Trigger custom event
            img.dispatchEvent(new CustomEvent('imageLoaded', {
                detail: { src: img.src, loadTime: performance.now() }
            }));
        };
        
        imageLoader.onerror = function() {
            img.classList.add('lazy-error');
            img.alt = 'Failed to load image';
        };
        
        // Start loading
        imageLoader.src = img.dataset.src || img.src;
    }

    /**
     * Check WebP support
     */
    function supportsWebP() {
        return document.documentElement.classList.contains('webp-supported');
    }

    /**
     * Optimize image loading priority
     */
    function optimizeImageLoadingPriority(block) {
        const images = block.querySelectorAll('img');
        
        images.forEach(function(img, index) {
            // First 2 images get high priority
            if (index < 2) {
                img.loading = 'eager';
                if ('fetchPriority' in HTMLImageElement.prototype) {
                    img.fetchPriority = 'high';
                }
            } else {
                img.loading = 'lazy';
                img.fetchPriority = 'low';
            }
        });
    }

    /**
     * Initialize gallery visibility observer
     */
    function initGalleryVisibilityObserver(block) {
        const gallery = block.querySelector('.gallery-grid');
        if (!gallery) return;
        
        const galleryObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    // Add visible class for animations
                    gallery.classList.add('gallery-visible');
                    
                    // Stagger image loading
                    const images = gallery.querySelectorAll('img[data-lazy]');
                    images.forEach(function(img, index) {
                        setTimeout(function() {
                            loadLazyImage(img);
                            img.classList.add('gallery-image-loaded');
                        }, index * 100); // 100ms stagger
                    });
                    
                    // Stop observing
                    galleryObserver.unobserve(gallery);
                    
                    // Trigger gallery loaded event
                    gallery.dispatchEvent(new CustomEvent('galleryLoaded', {
                        detail: { imageCount: images.length }
                    }));
                }
            });
        }, {
            rootMargin: '100px 0px',
            threshold: 0.1
        });
        
        galleryObserver.observe(gallery);
    }

    /**
     * Preload critical images
     */
    function preloadCriticalImages(block) {
        const criticalImages = block.querySelectorAll('img[loading="eager"]');
        
        criticalImages.forEach(function(img) {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = img.src || img.dataset.src;
            
            // Add to head
            document.head.appendChild(link);
        });
    }

    /**
     * Monitor gallery performance
     */
    function monitorGalleryPerformance(block) {
        const gallery = block.querySelector('.gallery-grid');
        if (!gallery) return;
        
        let imageLoadCount = 0;
        let totalLoadTime = 0;
        const startTime = performance.now();
        
        // Monitor individual image loads
        gallery.addEventListener('imageLoaded', function(e) {
            imageLoadCount++;
            totalLoadTime += e.detail.loadTime - startTime;
            
            // Calculate average load time
            const avgLoadTime = totalLoadTime / imageLoadCount;
            
            // Log performance metrics (development only)
            if (window.console && window.console.log) {
                console.log('Gallery Performance:', {
                    imagesLoaded: imageLoadCount,
                    averageLoadTime: Math.round(avgLoadTime) + 'ms',
                    totalImages: gallery.querySelectorAll('img').length
                });
            }
        });
        
        // Monitor gallery load completion
        gallery.addEventListener('galleryLoaded', function(e) {
            const loadTime = performance.now() - startTime;
            
            // Trigger custom performance event
            if (window.RiceLipkaPerformance) {
                document.dispatchEvent(new CustomEvent('ricelipka:performance:gallery', {
                    detail: { loadTime: loadTime, imageCount: e.detail.imageCount }
                }));
            }
        });
    }

})();