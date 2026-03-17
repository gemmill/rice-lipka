/**
 * Generic Endless Scroll
 * Handles infinite scroll loading for any content type
 */

(function() {
    'use strict';
    
    // Configuration - can be set via window.endlessScrollConfig
    const config = window.endlessScrollConfig || {
        ajaxAction: 'load_more_news',
        containerId: 'news-masonry',
        loadingId: 'news-loading',
        itemSelector: '.news-item',
        wrapperClass: 'masonry-item'
    };
    
    let isLoading = false;
    let currentPage = window.endlessScrollData ? window.endlessScrollData.currentPage : 1;
    let maxPages = window.endlessScrollData ? window.endlessScrollData.maxPages : 1;
    let ajaxUrl = window.endlessScrollData ? window.endlessScrollData.ajaxUrl : '';
    
    const container = document.getElementById(config.containerId);
    const loadingIndicator = document.getElementById(config.loadingId);
    
    if (!container || !ajaxUrl) {
        return;
    }
    
    function loadMoreContent() {
        if (isLoading || currentPage >= maxPages) {
            return;
        }
        
        isLoading = true;
        loadingIndicator.style.display = 'block';
        
        const nextPage = currentPage + 1;
        
        fetch(ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: config.ajaxAction,
                page: nextPage,
                nonce: window.endlessScrollData.nonce || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.html) {
                // Create temporary container to parse HTML
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.data.html;
                
                // Get new items
                const items = Array.from(tempDiv.querySelectorAll(config.itemSelector));
                
                items.forEach(item => {
                    if (config.wrapperClass) {
                        // Wrap item in wrapper class
                        const wrapper = document.createElement('div');
                        wrapper.className = config.wrapperClass;
                        wrapper.appendChild(item);
                        container.appendChild(wrapper);
                    } else {
                        // Add item directly
                        container.appendChild(item);
                    }
                });
                
                // Refresh masonry layout if available
                if (window.masonryInstance) {
                    window.masonryInstance.refresh();
                }
                
                currentPage = nextPage;
                
                // Update max pages if provided
                if (data.data.max_pages) {
                    maxPages = data.data.max_pages;
                }
            }
        })
        .catch(error => {
            console.error('Error loading more content:', error);
        })
        .finally(() => {
            isLoading = false;
            loadingIndicator.style.display = 'none';
        });
    }
    
    function checkScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        
        // Load more when user is 200px from bottom
        if (scrollTop + windowHeight >= documentHeight - 200) {
            loadMoreContent();
        }
    }
    
    // Throttle scroll events
    let scrollTimeout;
    function throttledScroll() {
        if (scrollTimeout) {
            return;
        }
        
        scrollTimeout = setTimeout(() => {
            checkScroll();
            scrollTimeout = null;
        }, 100);
    }
    
    // Initialize
    window.addEventListener('scroll', throttledScroll);
    window.addEventListener('resize', throttledScroll);
    
})();