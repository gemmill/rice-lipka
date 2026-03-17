/**
 * Masonry Layout System
 * JavaScript-based masonry for proper item positioning
 */

class Masonry {
    constructor(container, options = {}) {
        this.container = typeof container === 'string' ? document.querySelector(container) : container;
        this.options = {
            itemSelector: '.masonry-item',
            columnWidth: null,
            gutter: 32,
            fitWidth: true,
            ...options
        };
        
        this.items = [];
        this.columnHeights = [];
        this.columnCount = 0;
        this.columnWidth = 0;
        
        if (!this.container) {
            console.error('Masonry container not found');
            return;
        }
        
        this.init();
    }
    
    init() {
        this.container.style.position = 'relative';
        this.calculateDimensions();
        this.layout();
        
        // Handle window resize
        window.addEventListener('resize', () => {
            clearTimeout(this.resizeTimeout);
            this.resizeTimeout = setTimeout(() => {
                this.calculateDimensions();
                this.layout();
            }, 250);
        });
    }
    
    calculateDimensions() {
        const containerWidth = this.container.offsetWidth;
        
        // Determine column count based on screen width
        if (containerWidth <= 480) {
            this.columnCount = 1;
        } else if (containerWidth <= 768) {
            this.columnCount = 2;
        } else {
            this.columnCount = 3;
        }
        
        // Calculate column width
        const totalGutter = this.options.gutter * (this.columnCount - 1);
        this.columnWidth = (containerWidth - totalGutter) / this.columnCount;
        
        // Initialize column heights
        this.columnHeights = new Array(this.columnCount).fill(0);
        
        console.log(`Masonry: ${this.columnCount} columns, width: ${this.columnWidth}px`);
    }
    
    layout() {
        this.items = Array.from(this.container.querySelectorAll(this.options.itemSelector));
        
        if (this.items.length === 0) {
            console.log('No masonry items found');
            return;
        }
        
        console.log(`Laying out ${this.items.length} items`);
        
        // Reset column heights
        this.columnHeights.fill(0);
        
        this.items.forEach((item, index) => {
            this.positionItem(item, index);
        });
        
        // Set container height
        const maxHeight = Math.max(...this.columnHeights);
        this.container.style.height = `${maxHeight}px`;
        
        console.log(`Container height: ${maxHeight}px`);
    }
    
    positionItem(item, index) {
        // Set item width
        item.style.width = `${this.columnWidth}px`;
        item.style.position = 'absolute';
        
        // Find shortest column
        const shortestColumnIndex = this.columnHeights.indexOf(Math.min(...this.columnHeights));
        
        // Calculate position
        const x = shortestColumnIndex * (this.columnWidth + this.options.gutter);
        const y = this.columnHeights[shortestColumnIndex];
        
        // Position item
        item.style.left = `${x}px`;
        item.style.top = `${y}px`;
        item.style.transform = 'none'; // Clear any transforms
        
        // Get item height after positioning
        const itemHeight = item.offsetHeight;
        
        // Update column height
        this.columnHeights[shortestColumnIndex] += itemHeight + this.options.gutter;
        
        console.log(`Item ${index}: (${x}, ${y}) height: ${itemHeight}px`);
    }
    
    addItems(newItems) {
        // Append new items to container
        newItems.forEach(item => {
            this.container.appendChild(item);
        });
        
        // Re-layout everything
        this.layout();
    }
    
    refresh() {
        this.layout();
    }
}

// Global masonry instance
window.masonryInstance = null;

// Initialize masonry when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize news masonry
    const newsMasonryContainer = document.getElementById('news-masonry');
    if (newsMasonryContainer) {
        console.log('Initializing news masonry...');
        setTimeout(() => {
            window.newsMasonryInstance = new Masonry(newsMasonryContainer, {
                itemSelector: '.masonry-item',
                gutter: 32
            });
        }, 100);
    }
    
    // Initialize awards masonry
    const awardsMasonryContainer = document.getElementById('awards-masonry');
    if (awardsMasonryContainer) {
        console.log('Initializing awards masonry...');
        setTimeout(() => {
            window.awardsMasonryInstance = new Masonry(awardsMasonryContainer, {
                itemSelector: '.masonry-item',
                gutter: 32
            });
        }, 100);
    }
    
    // Initialize about page masonry
    const aboutMasonryContainer = document.getElementById('about-masonry');
    if (aboutMasonryContainer) {
        console.log('Initializing about masonry...');
        console.log('About container found, items:', aboutMasonryContainer.querySelectorAll('.masonry-item').length);
        setTimeout(() => {
            window.aboutMasonryInstance = new Masonry(aboutMasonryContainer, {
                itemSelector: '.masonry-item',
                gutter: 32
            });
            console.log('About masonry initialized');
        }, 100);
    } else {
        console.log('No about masonry container found');
    }
    
    // Initialize projects masonry
    const projectsMasonryContainer = document.getElementById('projects-masonry');
    if (projectsMasonryContainer) {
        console.log('Initializing projects masonry...');
        setTimeout(() => {
            window.projectsMasonryInstance = new Masonry(projectsMasonryContainer, {
                itemSelector: '.masonry-item',
                gutter: 32
            });
        }, 100);
    }
    
    // Set global instance for backwards compatibility
    window.masonryInstance = window.newsMasonryInstance || window.awardsMasonryInstance || window.aboutMasonryInstance || window.projectsMasonryInstance;
});