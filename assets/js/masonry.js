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
        // Defer initial layout until all images inside the container have loaded
        // so item heights are correct on first paint (prevents flicker/bounce).
        this.whenImagesReady(() => {
            this.layout();
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            clearTimeout(this.resizeTimeout);
            this.resizeTimeout = setTimeout(() => {
                this.calculateDimensions();
                this.layout();
            }, 250);
        });
    }

    whenImagesReady(callback) {
        const imgs = Array.from(this.container.querySelectorAll('img'));
        const pending = imgs.filter(img => !img.complete && img.src);

        if (pending.length === 0) {
            callback();
            return;
        }

        let remaining = pending.length;
        const done = () => {
            remaining--;
            if (remaining <= 0) callback();
        };

        pending.forEach(img => {
            img.addEventListener('load', done, { once: true });
            img.addEventListener('error', done, { once: true });
        });

        // Safety net: don't wait forever on a stuck image
        setTimeout(() => {
            if (remaining > 0) {
                remaining = 0;
                callback();
            }
        }, 3000);
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
    }
    
    layout() {
        this.items = Array.from(this.container.querySelectorAll(this.options.itemSelector));
        
        if (this.items.length === 0) {
            // Still show container even if no items
            this.container.classList.add('masonry-loaded');
            return;
        }
        
        // Reset column heights
        this.columnHeights.fill(0);
        
        this.items.forEach((item, index) => {
            this.positionItem(item, index);
        });
        
        // Set container height
        const maxHeight = Math.max(...this.columnHeights);
        this.container.style.height = `${maxHeight}px`;
        
        // Show container after layout is complete
        this.container.classList.add('masonry-loaded');
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
    function initMasonryById(id, instanceName) {
        const container = document.getElementById(id);
        if (!container) {
            return null;
        }

        setTimeout(() => {
            window[instanceName] = new Masonry(container, {
                itemSelector: '.masonry-item',
                gutter: 32
            });
        }, 100);

        return true;
    }

    // Initialize news masonry
    initMasonryById('news-masonry', 'newsMasonryInstance');
    
    // Initialize awards masonry
    initMasonryById('awards-masonry', 'awardsMasonryInstance');

    // Initialize people masonry
    initMasonryById('people-masonry', 'peopleMasonryInstance');

    // Initialize exhibitions masonry
    initMasonryById('exhibitions-masonry', 'exhibitionsMasonryInstance');

    // Initialize lectures masonry
    initMasonryById('lectures-masonry', 'lecturesMasonryInstance');

    // Initialize publications masonry
    initMasonryById('publications-masonry', 'publicationsMasonryInstance');
    
    // Initialize about page masonry
    const aboutMasonryContainer = document.getElementById('about-masonry');
    if (aboutMasonryContainer) {
        const items = aboutMasonryContainer.querySelectorAll('.masonry-item');
        
        setTimeout(() => {
            try {
                window.aboutMasonryInstance = new Masonry(aboutMasonryContainer, {
                    itemSelector: '.masonry-item',
                    gutter: 32
                });
            } catch (error) {
                // Fallback: show content anyway
                aboutMasonryContainer.classList.add('masonry-loaded');
            }
        }, 100);
    }

    // Initialize contact page masonry
    const contactMasonryContainer = document.getElementById('contact-masonry');
    if (contactMasonryContainer) {
        setTimeout(() => {
            try {
                window.contactMasonryInstance = new Masonry(contactMasonryContainer, {
                    itemSelector: '.masonry-item',
                    gutter: 32
                });
            } catch (error) {
                contactMasonryContainer.classList.add('masonry-loaded');
            }
        }, 100);
    }
    
    // Set global instance for backwards compatibility
    window.masonryInstance = window.newsMasonryInstance || window.awardsMasonryInstance || window.peopleMasonryInstance || window.exhibitionsMasonryInstance || window.lecturesMasonryInstance || window.publicationsMasonryInstance || window.aboutMasonryInstance || window.projectsMasonryInstance;
});