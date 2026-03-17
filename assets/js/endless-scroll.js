/**
 * Simple Endless Scroll
 * Uses WordPress localized data
 */

console.log('Endless scroll script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for endless scroll data...');
    
    // Check if WordPress provided the data
    if (typeof endlessScrollData === 'undefined') {
        console.log('No endlessScrollData found');
        return;
    }
    
    console.log('Endless scroll data:', endlessScrollData);
    
    // Auto-detect configuration based on page elements
    let config = null;
    
    if (document.getElementById('news-masonry')) {
        config = {
            ajaxAction: 'load_more_news',
            containerId: 'news-masonry',
            loadingId: 'news-loading',
            itemSelector: '.news-item',
            wrapperClass: 'masonry-item'
        };
        console.log('Detected news page');
    } else if (document.getElementById('projects-masonry')) {
        config = {
            ajaxAction: 'load_more_projects',
            containerId: 'projects-masonry',
            loadingId: 'projects-loading',
            itemSelector: '.project-item',
            wrapperClass: 'masonry-item'
        };
        console.log('Detected projects page');
    } else if (document.getElementById('awards-masonry')) {
        config = {
            ajaxAction: 'load_more_awards',
            containerId: 'awards-masonry',
            loadingId: 'awards-loading',
            itemSelector: '.award',
            wrapperClass: 'masonry-item'
        };
        console.log('Detected awards page');
    }
    
    if (!config) {
        console.log('No supported page type found');
        return;
    }
    
    const container = document.getElementById(config.containerId);
    const loadingIndicator = document.getElementById(config.loadingId);
    
    if (!container) {
        console.log('Container not found:', config.containerId);
        return;
    }
    
    let isLoading = false;
    let currentPage = parseInt(endlessScrollData.currentPage);
    let maxPages = parseInt(endlessScrollData.maxPages);
    
    console.log('Initialized with page', currentPage, 'of', maxPages);
    
    function loadMore() {
        if (isLoading || currentPage >= maxPages) {
            console.log('Cannot load more:', {isLoading, currentPage, maxPages});
            return;
        }
        
        isLoading = true;
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        
        const nextPage = currentPage + 1;
        console.log('Loading page', nextPage);
        
        fetch(endlessScrollData.ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: config.ajaxAction,
                page: nextPage,
                nonce: endlessScrollData.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            
            if (data.success && data.data.html) {
                const temp = document.createElement('div');
                temp.innerHTML = data.data.html;
                
                const items = temp.querySelectorAll(config.itemSelector);
                console.log('Adding', items.length, 'items');
                
                items.forEach(item => {
                    const wrapper = document.createElement('div');
                    wrapper.className = config.wrapperClass;
                    wrapper.appendChild(item);
                    container.appendChild(wrapper);
                });
                
                // Refresh masonry
                if (window.awardsMasonryInstance) {
                    window.awardsMasonryInstance.refresh();
                } else if (window.newsMasonryInstance) {
                    window.newsMasonryInstance.refresh();
                } else if (window.projectsMasonryInstance) {
                    window.projectsMasonryInstance.refresh();
                }
                
                currentPage = nextPage;
                if (data.data.max_pages) maxPages = data.data.max_pages;
            }
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            isLoading = false;
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        });
    }
    
    function checkScroll() {
        const scrollTop = window.pageYOffset;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        
        if (scrollTop + windowHeight >= documentHeight - 200) {
            loadMore();
        }
    }
    
    // Add scroll listener
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        if (scrollTimeout) return;
        scrollTimeout = setTimeout(() => {
            checkScroll();
            scrollTimeout = null;
        }, 100);
    });
    
    console.log('Endless scroll ready');
});