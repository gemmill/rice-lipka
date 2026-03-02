/**
 * Award Information Block JavaScript
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Award Information Block functionality
     */
    class AwardInformationBlock {
        constructor(blockElement) {
            this.block = $(blockElement);
            this.blockId = this.block.attr('id');
            this.init();
        }

        init() {
            this.setupCertificateModal();
            this.setupShareModal();
            this.setupTimelineAnimation();
            this.setupProjectGalleryPreview();
            this.setupPrintFunctionality();
            this.setupAccessibility();
        }

        /**
         * Setup certificate modal functionality
         */
        setupCertificateModal() {
            const certificateZoom = this.block.find('.certificate-zoom');
            const certificateImage = this.block.find('.certificate-image');
            const modal = this.block.find('.certificate-modal');
            const closeBtn = modal.find('.certificate-modal-close');

            // Open modal on certificate click or zoom button click
            certificateImage.add(certificateZoom).on('click', (e) => {
                e.preventDefault();
                this.openCertificateModal();
            });

            // Close modal
            closeBtn.on('click', () => {
                this.closeCertificateModal();
            });

            // Close modal on backdrop click
            modal.on('click', (e) => {
                if (e.target === modal[0]) {
                    this.closeCertificateModal();
                }
            });

            // Close modal on escape key
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && modal.is(':visible')) {
                    this.closeCertificateModal();
                }
            });
        }

        /**
         * Open certificate modal
         */
        openCertificateModal() {
            const modal = this.block.find('.certificate-modal');
            const certificateImage = this.block.find('.certificate-image');
            const fullImageUrl = certificateImage.data('full') || certificateImage.attr('src');
            
            // Update modal image source
            modal.find('.certificate-full-image').attr('src', fullImageUrl);
            
            // Show modal
            modal.fadeIn(300);
            $('body').addClass('modal-open');
            
            // Focus management
            modal.find('.certificate-modal-close').focus();
            
            // Trap focus within modal
            this.trapFocus(modal);
        }

        /**
         * Close certificate modal
         */
        closeCertificateModal() {
            const modal = this.block.find('.certificate-modal');
            modal.fadeOut(300);
            $('body').removeClass('modal-open');
            
            // Return focus to trigger element
            this.block.find('.certificate-zoom').focus();
        }

        /**
         * Setup share modal functionality
         */
        setupShareModal() {
            const shareBtn = this.block.find('.share-btn');
            const modal = this.block.find('.share-modal');
            const closeBtn = modal.find('.share-modal-close');
            const shareOptions = modal.find('.share-option');

            // Open share modal
            shareBtn.on('click', () => {
                this.openShareModal();
            });

            // Close modal
            closeBtn.on('click', () => {
                this.closeShareModal();
            });

            // Close modal on backdrop click
            modal.on('click', (e) => {
                if (e.target === modal[0]) {
                    this.closeShareModal();
                }
            });

            // Handle share options
            shareOptions.on('click', (e) => {
                e.preventDefault();
                const shareType = $(e.currentTarget).data('share');
                this.handleShare(shareType);
            });
        }

        /**
         * Open share modal
         */
        openShareModal() {
            const modal = this.block.find('.share-modal');
            modal.fadeIn(300);
            $('body').addClass('modal-open');
            
            // Focus management
            modal.find('.share-modal-close').focus();
            
            // Trap focus within modal
            this.trapFocus(modal);
        }

        /**
         * Close share modal
         */
        closeShareModal() {
            const modal = this.block.find('.share-modal');
            modal.fadeOut(300);
            $('body').removeClass('modal-open');
            
            // Return focus to trigger element
            this.block.find('.share-btn').focus();
        }

        /**
         * Handle different share types
         */
        handleShare(shareType) {
            const awardName = this.block.find('.award-name').text();
            const pageUrl = window.location.href;
            const pageTitle = document.title;
            
            switch (shareType) {
                case 'facebook':
                    this.shareOnFacebook(pageUrl, awardName);
                    break;
                case 'twitter':
                    this.shareOnTwitter(pageUrl, awardName);
                    break;
                case 'linkedin':
                    this.shareOnLinkedIn(pageUrl, awardName);
                    break;
                case 'email':
                    this.shareViaEmail(pageUrl, awardName);
                    break;
                case 'copy':
                    this.copyToClipboard(pageUrl);
                    break;
            }
            
            // Close modal after sharing
            this.closeShareModal();
        }

        /**
         * Share on Facebook
         */
        shareOnFacebook(url, title) {
            const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            this.openShareWindow(shareUrl, 'Facebook');
        }

        /**
         * Share on Twitter
         */
        shareOnTwitter(url, title) {
            const text = `Check out this award: ${title}`;
            const shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
            this.openShareWindow(shareUrl, 'Twitter');
        }

        /**
         * Share on LinkedIn
         */
        shareOnLinkedIn(url, title) {
            const shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
            this.openShareWindow(shareUrl, 'LinkedIn');
        }

        /**
         * Share via Email
         */
        shareViaEmail(url, title) {
            const subject = `Award Recognition: ${title}`;
            const body = `I wanted to share this award recognition with you: ${url}`;
            const mailtoUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            window.location.href = mailtoUrl;
        }

        /**
         * Copy URL to clipboard
         */
        copyToClipboard(url) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => {
                    this.showCopySuccess();
                }).catch(() => {
                    this.fallbackCopyToClipboard(url);
                });
            } else {
                this.fallbackCopyToClipboard(url);
            }
        }

        /**
         * Fallback copy to clipboard method
         */
        fallbackCopyToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                this.showCopySuccess();
            } catch (err) {
                console.error('Failed to copy URL:', err);
                this.showCopyError();
            }
            
            document.body.removeChild(textArea);
        }

        /**
         * Show copy success message
         */
        showCopySuccess() {
            const copyBtn = this.block.find('.copy-link');
            const originalText = copyBtn.text();
            copyBtn.text('Copied!').addClass('copy-success');
            
            setTimeout(() => {
                copyBtn.text(originalText).removeClass('copy-success');
            }, 2000);
        }

        /**
         * Show copy error message
         */
        showCopyError() {
            const copyBtn = this.block.find('.copy-link');
            const originalText = copyBtn.text();
            copyBtn.text('Copy failed').addClass('copy-error');
            
            setTimeout(() => {
                copyBtn.text(originalText).removeClass('copy-error');
            }, 2000);
        }

        /**
         * Open share window
         */
        openShareWindow(url, platform) {
            const width = 600;
            const height = 400;
            const left = (window.innerWidth - width) / 2;
            const top = (window.innerHeight - height) / 2;
            
            window.open(
                url,
                `share-${platform.toLowerCase()}`,
                `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`
            );
        }

        /**
         * Setup timeline animation
         */
        setupTimelineAnimation() {
            const timelineItems = this.block.find('.timeline-item');
            
            if (timelineItems.length === 0) return;

            // Animate timeline items on scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('timeline-animate');
                    }
                });
            }, {
                threshold: 0.3,
                rootMargin: '0px 0px -50px 0px'
            });

            timelineItems.each(function() {
                observer.observe(this);
            });

            // Sort timeline items by year for proper animation order
            this.sortTimelineItems();
        }

        /**
         * Sort timeline items by year
         */
        sortTimelineItems() {
            const container = this.block.find('.timeline-container');
            const items = container.find('.timeline-item').get();
            
            items.sort((a, b) => {
                const yearA = parseInt($(a).data('year')) || 0;
                const yearB = parseInt($(b).data('year')) || 0;
                return yearB - yearA; // Sort descending (newest first)
            });
            
            $.each(items, function(index, item) {
                container.append(item);
            });
        }

        /**
         * Setup project gallery preview
         */
        setupProjectGalleryPreview() {
            const galleryBtn = this.block.find('.project-gallery-preview');
            
            galleryBtn.on('click', (e) => {
                e.preventDefault();
                const projectId = galleryBtn.data('project-id');
                
                if (projectId) {
                    this.loadProjectGallery(projectId);
                } else {
                    // Show preview gallery for demo
                    this.showPreviewGallery();
                }
            });
        }

        /**
         * Load project gallery via AJAX
         */
        loadProjectGallery(projectId) {
            // Show loading state
            const galleryBtn = this.block.find('.project-gallery-preview');
            const originalText = galleryBtn.html();
            galleryBtn.html('<span class="loading-spinner">⟳</span> Loading...');

            $.ajax({
                url: riceLipkaBlocks.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'load_project_gallery',
                    project_id: projectId,
                    nonce: riceLipkaBlocks.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.displayGalleryModal(response.data.gallery);
                    } else {
                        console.error('Failed to load project gallery:', response.data);
                    }
                },
                error: (xhr, status, error) => {
                    console.error('AJAX error loading project gallery:', error);
                },
                complete: () => {
                    galleryBtn.html(originalText);
                }
            });
        }

        /**
         * Show preview gallery for demo purposes
         */
        showPreviewGallery() {
            const sampleGallery = [
                {
                    url: 'https://via.placeholder.com/800x600/007bff/ffffff?text=Project+Gallery+1',
                    alt: 'Project exterior view',
                    title: 'Building Exterior'
                },
                {
                    url: 'https://via.placeholder.com/800x600/28a745/ffffff?text=Project+Gallery+2',
                    alt: 'Project interior view',
                    title: 'Interior Space'
                },
                {
                    url: 'https://via.placeholder.com/800x600/dc3545/ffffff?text=Project+Gallery+3',
                    alt: 'Project detail view',
                    title: 'Architectural Detail'
                }
            ];
            
            this.displayGalleryModal(sampleGallery);
        }

        /**
         * Display gallery modal
         */
        displayGalleryModal(gallery) {
            // Create gallery modal HTML
            const modalHtml = this.createGalleryModalHtml(gallery);
            
            // Add modal to page
            $('body').append(modalHtml);
            
            // Initialize gallery functionality
            this.initializeGalleryModal();
        }

        /**
         * Create gallery modal HTML
         */
        createGalleryModalHtml(gallery) {
            let galleryItems = '';
            
            gallery.forEach((image, index) => {
                galleryItems += `
                    <div class="gallery-slide ${index === 0 ? 'active' : ''}" data-index="${index}">
                        <img src="${image.url}" alt="${image.alt}" title="${image.title}">
                    </div>
                `;
            });
            
            return `
                <div class="project-gallery-modal" id="gallery-modal-${this.blockId}">
                    <div class="gallery-modal-content">
                        <div class="gallery-modal-header">
                            <h3>Project Gallery</h3>
                            <button class="gallery-modal-close" aria-label="Close gallery">&times;</button>
                        </div>
                        <div class="gallery-modal-body">
                            <div class="gallery-container">
                                <div class="gallery-slides">
                                    ${galleryItems}
                                </div>
                                <button class="gallery-nav gallery-prev" aria-label="Previous image">‹</button>
                                <button class="gallery-nav gallery-next" aria-label="Next image">›</button>
                            </div>
                            <div class="gallery-counter">
                                <span class="current-slide">1</span> / <span class="total-slides">${gallery.length}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        /**
         * Initialize gallery modal functionality
         */
        initializeGalleryModal() {
            const modal = $(`#gallery-modal-${this.blockId}`);
            const closeBtn = modal.find('.gallery-modal-close');
            const prevBtn = modal.find('.gallery-prev');
            const nextBtn = modal.find('.gallery-next');
            let currentSlide = 0;
            const totalSlides = modal.find('.gallery-slide').length;

            // Show modal
            modal.fadeIn(300);
            $('body').addClass('modal-open');

            // Close modal
            closeBtn.on('click', () => {
                modal.fadeOut(300, () => {
                    modal.remove();
                    $('body').removeClass('modal-open');
                });
            });

            // Navigation
            prevBtn.on('click', () => {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                this.updateGallerySlide(modal, currentSlide);
            });

            nextBtn.on('click', () => {
                currentSlide = (currentSlide + 1) % totalSlides;
                this.updateGallerySlide(modal, currentSlide);
            });

            // Keyboard navigation
            $(document).on('keydown.gallery', (e) => {
                if (modal.is(':visible')) {
                    switch (e.key) {
                        case 'ArrowLeft':
                            prevBtn.click();
                            break;
                        case 'ArrowRight':
                            nextBtn.click();
                            break;
                        case 'Escape':
                            closeBtn.click();
                            break;
                    }
                }
            });

            // Remove keyboard listener when modal is closed
            modal.on('remove', () => {
                $(document).off('keydown.gallery');
            });
        }

        /**
         * Update gallery slide
         */
        updateGallerySlide(modal, slideIndex) {
            const slides = modal.find('.gallery-slide');
            const currentCounter = modal.find('.current-slide');
            
            slides.removeClass('active');
            slides.eq(slideIndex).addClass('active');
            currentCounter.text(slideIndex + 1);
        }

        /**
         * Setup print functionality
         */
        setupPrintFunctionality() {
            const printBtn = this.block.find('.print-btn');
            
            printBtn.on('click', () => {
                this.printAward();
            });
        }

        /**
         * Print award information
         */
        printAward() {
            // Create print-friendly version
            const printContent = this.createPrintContent();
            
            // Open print window
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Wait for content to load, then print
            printWindow.onload = () => {
                printWindow.print();
                printWindow.close();
            };
        }

        /**
         * Create print-friendly content
         */
        createPrintContent() {
            const awardName = this.block.find('.award-name').text();
            const organization = this.block.find('.organization-name').text();
            const date = this.block.find('.award-date time').text();
            const description = this.block.find('.award-description').html();
            const certificateImg = this.block.find('.certificate-image');
            
            let certificateHtml = '';
            if (certificateImg.length) {
                certificateHtml = `
                    <div class="print-certificate">
                        <img src="${certificateImg.attr('src')}" alt="${certificateImg.attr('alt')}" style="max-width: 100%; height: auto;">
                    </div>
                `;
            }
            
            return `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Award Certificate - ${awardName}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 2cm; }
                        .print-header { text-align: center; margin-bottom: 2rem; }
                        .award-title { font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem; }
                        .award-org { font-size: 1.2rem; color: #666; margin-bottom: 0.5rem; }
                        .award-date { font-size: 1rem; color: #888; }
                        .print-certificate { text-align: center; margin: 2rem 0; }
                        .print-description { margin: 2rem 0; line-height: 1.6; }
                        @page { margin: 2cm; }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <div class="award-title">${awardName}</div>
                        <div class="award-org">${organization}</div>
                        <div class="award-date">${date}</div>
                    </div>
                    ${certificateHtml}
                    <div class="print-description">${description}</div>
                </body>
                </html>
            `;
        }

        /**
         * Setup accessibility features
         */
        setupAccessibility() {
            // Add ARIA labels and roles
            this.block.find('.certificate-zoom').attr('role', 'button');
            this.block.find('.timeline-container').attr('role', 'list');
            this.block.find('.timeline-item').attr('role', 'listitem');
            
            // Keyboard navigation for interactive elements
            this.block.find('.certificate-image, .certificate-zoom').on('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.openCertificateModal();
                }
            });
            
            // Focus management for modals
            this.setupModalFocusManagement();
        }

        /**
         * Setup modal focus management
         */
        setupModalFocusManagement() {
            // Store focusable elements selector
            this.focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
        }

        /**
         * Trap focus within modal
         */
        trapFocus(modal) {
            const focusableElements = modal.find(this.focusableElements);
            const firstElement = focusableElements.first();
            const lastElement = focusableElements.last();

            modal.on('keydown.focustrap', (e) => {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstElement[0]) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        if (document.activeElement === lastElement[0]) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
        }
    }

    /**
     * Initialize Award Information Blocks
     */
    function initAwardInformationBlocks() {
        $('.award-information-block').each(function() {
            if (!$(this).data('award-initialized')) {
                new AwardInformationBlock(this);
                $(this).data('award-initialized', true);
            }
        });
    }

    /**
     * CSS for dynamic elements
     */
    function addDynamicStyles() {
        if ($('#award-information-dynamic-styles').length === 0) {
            $('head').append(`
                <style id="award-information-dynamic-styles">
                    .timeline-animate {
                        animation: slideInFromLeft 0.6s ease-out;
                    }
                    
                    @keyframes slideInFromLeft {
                        from {
                            opacity: 0;
                            transform: translateX(-30px);
                        }
                        to {
                            opacity: 1;
                            transform: translateX(0);
                        }
                    }
                    
                    .loading-spinner {
                        animation: spin 1s linear infinite;
                    }
                    
                    @keyframes spin {
                        from { transform: rotate(0deg); }
                        to { transform: rotate(360deg); }
                    }
                    
                    .copy-success {
                        background-color: #28a745 !important;
                        color: white !important;
                    }
                    
                    .copy-error {
                        background-color: #dc3545 !important;
                        color: white !important;
                    }
                    
                    .project-gallery-modal {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.9);
                        z-index: 10000;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    
                    .gallery-modal-content {
                        background: white;
                        border-radius: 8px;
                        max-width: 90vw;
                        max-height: 90vh;
                        overflow: hidden;
                    }
                    
                    .gallery-modal-header {
                        padding: 1rem;
                        border-bottom: 1px solid #eee;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }
                    
                    .gallery-modal-body {
                        position: relative;
                    }
                    
                    .gallery-container {
                        position: relative;
                        width: 80vw;
                        height: 60vh;
                        overflow: hidden;
                    }
                    
                    .gallery-slides {
                        width: 100%;
                        height: 100%;
                        position: relative;
                    }
                    
                    .gallery-slide {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    }
                    
                    .gallery-slide.active {
                        opacity: 1;
                    }
                    
                    .gallery-slide img {
                        max-width: 100%;
                        max-height: 100%;
                        object-fit: contain;
                    }
                    
                    .gallery-nav {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        background: rgba(0, 0, 0, 0.7);
                        color: white;
                        border: none;
                        font-size: 2rem;
                        padding: 1rem;
                        cursor: pointer;
                        z-index: 10;
                    }
                    
                    .gallery-prev {
                        left: 1rem;
                    }
                    
                    .gallery-next {
                        right: 1rem;
                    }
                    
                    .gallery-counter {
                        text-align: center;
                        padding: 1rem;
                        background: #f8f9fa;
                        font-weight: 600;
                    }
                    
                    body.modal-open {
                        overflow: hidden;
                    }
                </style>
            `);
        }
    }

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        addDynamicStyles();
        initAwardInformationBlocks();
        
        // Re-initialize blocks after AJAX content loads
        $(document).on('DOMNodeInserted', function(e) {
            if ($(e.target).hasClass('award-information-block') || $(e.target).find('.award-information-block').length) {
                setTimeout(initAwardInformationBlocks, 100);
            }
        });
    });

    // Initialize blocks in Gutenberg editor
    if (typeof wp !== 'undefined' && wp.domReady) {
        wp.domReady(function() {
            addDynamicStyles();
            initAwardInformationBlocks();
            
            // Re-initialize when blocks are updated in editor
            if (wp.data && wp.data.subscribe) {
                wp.data.subscribe(function() {
                    setTimeout(initAwardInformationBlocks, 100);
                });
            }
        });
    }

})(jQuery);