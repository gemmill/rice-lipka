/**
 * Performance Optimization JavaScript for Rice+Lipka WordPress Theme
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Performance optimization utilities
    const RiceLipkaPerformance = {
        
        // Lazy loading configuration
        lazyLoadConfig: {
            rootMargin: '50px 0px',
            threshold: 0.01,
            enableFadeIn: true,
            staggerDelay: 100
        },
        
        // Initialize all performance optimizations
        init: function() {
            this.initAdvancedLazyLoading();
            this.initImageOptimization();
            this.initGalleryLazyLoading();
            this.initPerformanceMonitoring();
            this.initResourceOptimization();
            this.initCriticalResourceLoading();
        },
        
        // Advanced lazy loading with Intersection Observer
        initAdvancedLazyLoading: function() {
            if (!('IntersectionObserver' in window)) {
                // Fallback for older browsers
                this.fallbackLazyLoading();
                return;
            }
            
            const config = this.lazyLoadConfig;
            
            // Create intersection observer for images
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadLazyImage(entry.target, observer);
                    }
                });
            }, {
                rootMargin: config.rootMargin,
                threshold: config.threshold
            });
            
            // Observe all lazy images
            document.querySelectorAll('img[data-lazy], img[loading="lazy"]').forEach(img => {
                imageObserver.observe(img);
            });
        },
        
        // Load lazy image with optimization
        loadLazyImage: function(img, observer) {
            // Create a new image to preload
            const imageLoader = new Image();
            
            imageLoader.onload = () => {
                // Replace src with data-src
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
                
                // Replace srcset with data-srcset
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                }
                
                // Add loaded class for CSS transitions
                img.classList.add('lazy-loaded');
                
                // Remove loading placeholder
                img.classList.remove('loading-placeholder');
                
                // Stop observing this image
                observer.unobserve(img);
                
                // Trigger custom event
                $(img).trigger('ricelipka:image:loaded');
            };
            
            imageLoader.onerror = () => {
                // Handle loading error
                img.classList.add('lazy-error');
                observer.unobserve(img);
            };
            
            // Start loading
            imageLoader.src = img.dataset.src || img.src;
        },
        
        // Fallback lazy loading for older browsers
        fallbackLazyLoading: function() {
            $('img[data-lazy]').each(function() {
                const $img = $(this);
                if ($img.data('src')) {
                    $img.attr('src', $img.data('src'));
                }
                if ($img.data('srcset')) {
                    $img.attr('srcset', $img.data('srcset'));
                }
                $img.addClass('lazy-loaded');
            });
        },
        
        // Gallery lazy loading with staggered animation
        initGalleryLazyLoading: function() {
            if (!('IntersectionObserver' in window)) {
                return;
            }
            
            const galleryObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadGalleryImages(entry.target);
                        galleryObserver.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '100px 0px',
                threshold: 0.1
            });
            
            // Observe galleries
            document.querySelectorAll('.gallery-grid, .awards-grid, .project-gallery').forEach(gallery => {
                galleryObserver.observe(gallery);
            });
        },
        
        // Load gallery images with staggered animation
        loadGalleryImages: function(gallery) {
            const $gallery = $(gallery);
            const $images = $gallery.find('img[data-lazy]');
            
            // Add visible class to gallery
            $gallery.addClass('gallery-visible');
            
            // Load images with staggered delay
            $images.each((index, img) => {
                setTimeout(() => {
                    const $img = $(img);
                    
                    if ($img.data('src')) {
                        $img.attr('src', $img.data('src'));
                    }
                    
                    $img.addClass('gallery-image-loaded');
                    $img.trigger('ricelipka:gallery:image:loaded');
                }, index * this.lazyLoadConfig.staggerDelay);
            });
            
            // Trigger gallery loaded event
            $gallery.trigger('ricelipka:gallery:loaded');
        },
        
        // Image optimization and WebP support
        initImageOptimization: function() {
            // Detect WebP support
            this.detectWebPSupport().then(supported => {
                if (supported) {
                    document.documentElement.classList.add('webp-supported');
                    this.replaceWithWebP();
                } else {
                    document.documentElement.classList.add('webp-not-supported');
                }
            });
            
            // Optimize image loading priority
            this.optimizeImagePriority();
        },
        
        // Detect WebP support
        detectWebPSupport: function() {
            return new Promise(resolve => {
                const webP = new Image();
                webP.onload = webP.onerror = function() {
                    resolve(webP.height === 2);
                };
                webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
            });
        },
        
        // Replace images with WebP versions
        replaceWithWebP: function() {
            $('img[data-webp]').each(function() {
                const $img = $(this);
                const webpSrc = $img.data('webp');
                const webpSrcset = $img.data('webp-srcset');
                
                if (webpSrc) {
                    $img.attr('src', webpSrc);
                }
                
                if (webpSrcset) {
                    $img.attr('srcset', webpSrcset);
                }
            });
        },
        
        // Optimize image loading priority
        optimizeImagePriority: function() {
            const $images = $('img');
            let aboveFoldCount = 0;
            
            $images.each(function() {
                const $img = $(this);
                const rect = this.getBoundingClientRect();
                
                // Check if image is above the fold
                if (rect.top < window.innerHeight && aboveFoldCount < 2) {
                    $img.attr('loading', 'eager');
                    if ('fetchPriority' in HTMLImageElement.prototype) {
                        $img.attr('fetchpriority', 'high');
                    }
                    aboveFoldCount++;
                } else {
                    $img.attr('loading', 'lazy');
                }
            });
        },
        
        // Performance monitoring
        initPerformanceMonitoring: function() {
            if (!window.performance || !console) {
                return;
            }
            
            // Monitor Core Web Vitals
            this.monitorCoreWebVitals();
            
            // Monitor resource loading
            this.monitorResourceLoading();
            
            // Monitor custom metrics
            this.monitorCustomMetrics();
        },
        
        // Monitor Core Web Vitals
        monitorCoreWebVitals: function() {
            // Largest Contentful Paint (LCP)
            if ('PerformanceObserver' in window) {
                try {
                    const lcpObserver = new PerformanceObserver(list => {
                        const entries = list.getEntries();
                        const lastEntry = entries[entries.length - 1];
                        
                        // Store LCP value
                        this.metrics = this.metrics || {};
                        this.metrics.lcp = lastEntry.startTime;
                        
                        // Trigger custom event
                        $(document).trigger('ricelipka:performance:lcp', [lastEntry.startTime]);
                    });
                    
                    lcpObserver.observe({entryTypes: ['largest-contentful-paint']});
                } catch (e) {
                    console.warn('LCP monitoring not supported');
                }
                
                // Cumulative Layout Shift (CLS)
                try {
                    let clsValue = 0;
                    const clsObserver = new PerformanceObserver(list => {
                        for (const entry of list.getEntries()) {
                            if (!entry.hadRecentInput) {
                                clsValue += entry.value;
                            }
                        }
                        
                        this.metrics = this.metrics || {};
                        this.metrics.cls = clsValue;
                        
                        $(document).trigger('ricelipka:performance:cls', [clsValue]);
                    });
                    
                    clsObserver.observe({entryTypes: ['layout-shift']});
                } catch (e) {
                    console.warn('CLS monitoring not supported');
                }
            }
        },
        
        // Monitor resource loading performance
        monitorResourceLoading: function() {
            $(window).on('load', () => {
                if (performance.timing) {
                    const timing = performance.timing;
                    const metrics = {
                        loadTime: timing.loadEventEnd - timing.navigationStart,
                        domReady: timing.domContentLoadedEventEnd - timing.navigationStart,
                        firstByte: timing.responseStart - timing.navigationStart,
                        domInteractive: timing.domInteractive - timing.navigationStart
                    };
                    
                    // Store metrics
                    this.metrics = Object.assign(this.metrics || {}, metrics);
                    
                    // Trigger performance event
                    $(document).trigger('ricelipka:performance:load', [metrics]);
                }
            });
        },
        
        // Monitor custom performance metrics
        monitorCustomMetrics: function() {
            // Monitor image loading performance
            let imageLoadCount = 0;
            let imageLoadTime = 0;
            
            $(document).on('ricelipka:image:loaded', 'img', function() {
                imageLoadCount++;
                imageLoadTime = performance.now();
                
                // Calculate average image load time
                if (imageLoadCount > 0) {
                    const avgLoadTime = imageLoadTime / imageLoadCount;
                    $(document).trigger('ricelipka:performance:images', [avgLoadTime, imageLoadCount]);
                }
            });
            
            // Monitor gallery loading performance
            $(document).on('ricelipka:gallery:loaded', '.gallery-grid, .awards-grid, .project-gallery', function() {
                const loadTime = performance.now();
                $(document).trigger('ricelipka:performance:gallery', [loadTime]);
            });
        },
        
        // Resource optimization
        initResourceOptimization: function() {
            // Preload critical resources
            this.preloadCriticalResources();
            
            // Optimize font loading
            this.optimizeFontLoading();
            
            // Optimize third-party resources
            this.optimizeThirdPartyResources();
        },
        
        // Preload critical resources
        preloadCriticalResources: function() {
            // Preload above-the-fold images
            const $aboveFoldImages = $('img').slice(0, 2);
            $aboveFoldImages.each(function() {
                const $img = $(this);
                const src = $img.attr('src') || $img.data('src');
                
                if (src) {
                    const link = document.createElement('link');
                    link.rel = 'preload';
                    link.as = 'image';
                    link.href = src;
                    document.head.appendChild(link);
                }
            });
        },
        
        // Optimize font loading
        optimizeFontLoading: function() {
            // Use font-display: swap for better performance
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(() => {
                    document.body.classList.add('fonts-loaded');
                });
            }
        },
        
        // Optimize third-party resources
        optimizeThirdPartyResources: function() {
            // Defer non-critical third-party scripts
            const deferredScripts = [
                'google-analytics',
                'facebook-pixel',
                'twitter-widgets'
            ];
            
            deferredScripts.forEach(scriptId => {
                const script = document.getElementById(scriptId);
                if (script) {
                    script.defer = true;
                }
            });
        },
        
        // Critical resource loading
        initCriticalResourceLoading: function() {
            // Load critical CSS immediately
            this.loadCriticalCSS();
            
            // Defer non-critical CSS
            this.deferNonCriticalCSS();
        },
        
        // Load critical CSS
        loadCriticalCSS: function() {
            const criticalCSS = [
                'responsive-layouts.css',
                'block-templates.css'
            ];
            
            criticalCSS.forEach(cssFile => {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = ricelipka_ajax.template_url + '/assets/css/' + cssFile;
                link.media = 'all';
                document.head.appendChild(link);
            });
        },
        
        // Defer non-critical CSS
        deferNonCriticalCSS: function() {
            const nonCriticalCSS = document.querySelectorAll('link[rel="stylesheet"][data-defer]');
            
            nonCriticalCSS.forEach(link => {
                link.media = 'print';
                link.onload = function() {
                    this.media = 'all';
                };
            });
        },
        
        // Get performance metrics
        getMetrics: function() {
            return this.metrics || {};
        },
        
        // Log performance metrics (for debugging)
        logMetrics: function() {
            if (console && console.table && this.metrics) {
                console.table(this.metrics);
            }
        }
    };
    
    // Initialize performance optimizations when document is ready
    $(document).ready(function() {
        RiceLipkaPerformance.init();
        
        // Expose to global scope for debugging
        window.RiceLipkaPerformance = RiceLipkaPerformance;
        
        // Trigger ready event
        $(document).trigger('ricelipka:performance:ready');
    });
    
    // Additional optimizations for specific content types
    $(document).on('ricelipka:performance:ready', function() {
        
        // Optimize project galleries
        $('.project-gallery').each(function() {
            const $gallery = $(this);
            const $images = $gallery.find('img');
            
            // Add loading placeholders
            $images.each(function() {
                const $img = $(this);
                if (!$img.hasClass('lazy-loaded')) {
                    $img.addClass('loading-placeholder');
                }
            });
        });
        
        // Optimize news article images
        $('.news-article img').each(function() {
            const $img = $(this);
            
            // Add responsive classes
            $img.addClass('responsive-image');
            
            // Optimize alt text for SEO
            if (!$img.attr('alt')) {
                const $article = $img.closest('.news-article');
                const title = $article.find('.news-title').text();
                if (title) {
                    $img.attr('alt', 'Image for: ' + title);
                }
            }
        });
        
        // Optimize award certificate images
        $('.award-certificate img').each(function() {
            const $img = $(this);
            
            // Add high-quality loading for certificates
            $img.attr('loading', 'eager');
            if ('fetchPriority' in HTMLImageElement.prototype) {
                $img.attr('fetchpriority', 'high');
            }
        });
    });

})(jQuery);