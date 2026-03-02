/**
 * News Article Block JavaScript
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';

    /**
     * Initialize News Article Block functionality
     */
    function initNewsArticleBlock() {
        const newsBlocks = document.querySelectorAll('.news-article-block');
        
        newsBlocks.forEach(function(block) {
            // Initialize social sharing
            initSocialSharing(block);
            
            // Initialize read time estimation
            initReadTimeEstimation(block);
            
            // Initialize image lazy loading
            initImageLazyLoading(block);
            
            // Initialize accessibility enhancements
            initAccessibilityEnhancements(block);
        });
    }

    /**
     * Initialize social sharing functionality
     */
    function initSocialSharing(block) {
        const shareContainer = block.querySelector('.news-social-share');
        if (!shareContainer) {
            // Create social sharing if it doesn't exist
            createSocialSharing(block);
        }

        // Add click handlers for social sharing buttons
        const shareButtons = block.querySelectorAll('.social-share-button');
        shareButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const platform = this.classList.contains('facebook') ? 'facebook' :
                               this.classList.contains('twitter') ? 'twitter' :
                               this.classList.contains('linkedin') ? 'linkedin' :
                               this.classList.contains('email') ? 'email' : null;
                
                if (platform) {
                    shareContent(platform, block);
                }
            });
        });
    }

    /**
     * Create social sharing buttons
     */
    function createSocialSharing(block) {
        const contentArea = block.querySelector('.news-content');
        if (!contentArea) return;

        const shareHTML = `
            <div class="news-social-share">
                <h4>Share this article</h4>
                <div class="social-share-buttons">
                    <a href="#" class="social-share-button facebook" aria-label="Share on Facebook">
                        <span>f</span>
                    </a>
                    <a href="#" class="social-share-button twitter" aria-label="Share on Twitter">
                        <span>𝕏</span>
                    </a>
                    <a href="#" class="social-share-button linkedin" aria-label="Share on LinkedIn">
                        <span>in</span>
                    </a>
                    <a href="#" class="social-share-button email" aria-label="Share via Email">
                        <span>✉</span>
                    </a>
                </div>
            </div>
        `;
        
        contentArea.insertAdjacentHTML('beforeend', shareHTML);
    }

    /**
     * Handle social sharing
     */
    function shareContent(platform, block) {
        const title = block.querySelector('.news-title')?.textContent || document.title;
        const url = window.location.href;
        const excerpt = block.querySelector('.news-excerpt')?.textContent || '';
        
        let shareUrl = '';
        
        switch (platform) {
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                break;
            case 'twitter':
                shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
                break;
            case 'linkedin':
                shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
                break;
            case 'email':
                shareUrl = `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(excerpt + '\n\n' + url)}`;
                break;
        }
        
        if (shareUrl) {
            if (platform === 'email') {
                window.location.href = shareUrl;
            } else {
                window.open(shareUrl, '_blank', 'width=600,height=400,scrollbars=yes,resizable=yes');
            }
        }
    }

    /**
     * Initialize read time estimation
     */
    function initReadTimeEstimation(block) {
        const content = block.querySelector('.news-body');
        if (!content) return;

        const text = content.textContent || content.innerText || '';
        const wordsPerMinute = 200;
        const wordCount = text.trim().split(/\s+/).length;
        const readTime = Math.ceil(wordCount / wordsPerMinute);
        
        // Add read time to date area
        const dateElement = block.querySelector('.news-date');
        if (dateElement && readTime > 0) {
            const readTimeSpan = document.createElement('span');
            readTimeSpan.className = 'news-read-time';
            readTimeSpan.textContent = ` • ${readTime} min read`;
            readTimeSpan.style.marginLeft = '0.5rem';
            dateElement.appendChild(readTimeSpan);
        }
    }

    /**
     * Initialize image lazy loading
     */
    function initImageLazyLoading(block) {
        const images = block.querySelectorAll('img[loading="lazy"]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Initialize accessibility enhancements
     */
    function initAccessibilityEnhancements(block) {
        // Add ARIA labels and roles
        const title = block.querySelector('.news-title');
        if (title) {
            title.setAttribute('role', 'heading');
            title.setAttribute('aria-level', '2');
        }

        // Enhance keyboard navigation for interactive elements
        const interactiveElements = block.querySelectorAll('a, button');
        interactiveElements.forEach(function(element) {
            element.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    if (e.key === ' ') {
                        e.preventDefault();
                    }
                    this.click();
                }
            });
        });

        // Add focus indicators
        const focusableElements = block.querySelectorAll('a, button, [tabindex]');
        focusableElements.forEach(function(element) {
            element.addEventListener('focus', function() {
                this.style.outline = '2px solid #3498db';
                this.style.outlineOffset = '2px';
            });
            
            element.addEventListener('blur', function() {
                this.style.outline = '';
                this.style.outlineOffset = '';
            });
        });
    }

    /**
     * Real-time preview functionality for block editor
     */
    function initBlockEditorPreview() {
        if (typeof wp === 'undefined' || !wp.data) {
            return;
        }

        // Listen for block updates in the editor
        wp.data.subscribe(function() {
            const selectedBlock = wp.data.select('core/block-editor').getSelectedBlock();
            
            if (selectedBlock && selectedBlock.name === 'acf/news-article') {
                // Refresh preview when block is updated
                setTimeout(function() {
                    const previewBlock = document.querySelector('.news-article-block');
                    if (previewBlock) {
                        initNewsArticleBlock();
                    }
                }, 100);
            }
        });
    }

    /**
     * Enhanced preview functionality
     */
    function enhancePreviewFunctionality() {
        // Add preview mode indicators
        if (document.body.classList.contains('block-editor-page')) {
            const newsBlocks = document.querySelectorAll('.news-article-block');
            newsBlocks.forEach(function(block) {
                // Add preview indicator
                const previewIndicator = document.createElement('div');
                previewIndicator.className = 'block-preview-indicator';
                previewIndicator.textContent = 'Preview Mode';
                previewIndicator.style.cssText = `
                    position: absolute;
                    top: 0;
                    right: 0;
                    background: #0073aa;
                    color: white;
                    padding: 0.25rem 0.5rem;
                    font-size: 0.75rem;
                    border-radius: 0 0 0 4px;
                    z-index: 10;
                `;
                
                block.style.position = 'relative';
                block.appendChild(previewIndicator);
            });
        }
    }

    /**
     * Initialize responsive preview modes
     */
    function initResponsivePreview() {
        if (typeof wp === 'undefined' || !wp.data) {
            return;
        }

        // Listen for device preview changes
        wp.data.subscribe(function() {
            const deviceType = wp.data.select('core/edit-post').__experimentalGetPreviewDeviceType();
            
            if (deviceType) {
                document.body.classList.remove('preview-desktop', 'preview-tablet', 'preview-mobile');
                document.body.classList.add('preview-' + deviceType.toLowerCase());
                
                // Adjust block styles based on preview mode
                const newsBlocks = document.querySelectorAll('.news-article-block');
                newsBlocks.forEach(function(block) {
                    block.setAttribute('data-preview-device', deviceType.toLowerCase());
                });
            }
        });
    }

    /**
     * Content validation for required fields
     */
    function validateContent(block) {
        const requiredFields = {
            title: block.querySelector('.news-title'),
            content: block.querySelector('.news-body')
        };

        const validation = {
            isValid: true,
            errors: [],
            warnings: []
        };

        // Check required fields
        Object.keys(requiredFields).forEach(function(fieldName) {
            const field = requiredFields[fieldName];
            if (!field || !field.textContent.trim()) {
                validation.isValid = false;
                validation.errors.push(`${fieldName} is required`);
            }
        });

        // Check content length warnings
        const content = block.querySelector('.news-body');
        if (content) {
            const wordCount = (content.textContent || '').trim().split(/\s+/).length;
            if (wordCount < 50) {
                validation.warnings.push('Content appears to be very short');
            } else if (wordCount > 2000) {
                validation.warnings.push('Content is quite long - consider breaking into sections');
            }
        }

        return validation;
    }

    /**
     * Display validation messages
     */
    function displayValidation(block, validation) {
        // Remove existing validation messages
        const existingMessages = block.querySelectorAll('.block-validation-message');
        existingMessages.forEach(function(msg) {
            msg.remove();
        });

        // Add new validation messages
        if (validation.errors.length > 0 || validation.warnings.length > 0) {
            const messagesContainer = document.createElement('div');
            messagesContainer.className = 'block-validation-messages';
            
            validation.errors.forEach(function(error) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'block-validation-message error';
                errorDiv.textContent = error;
                messagesContainer.appendChild(errorDiv);
            });
            
            validation.warnings.forEach(function(warning) {
                const warningDiv = document.createElement('div');
                warningDiv.className = 'block-validation-message warning';
                warningDiv.textContent = warning;
                messagesContainer.appendChild(warningDiv);
            });
            
            block.insertBefore(messagesContainer, block.firstChild);
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNewsArticleBlock);
    } else {
        initNewsArticleBlock();
    }

    // Initialize block editor specific functionality
    if (typeof wp !== 'undefined' && wp.domReady) {
        wp.domReady(function() {
            initBlockEditorPreview();
            enhancePreviewFunctionality();
            initResponsivePreview();
        });
    }

    // Re-initialize when new blocks are added dynamically
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.classList && node.classList.contains('news-article-block')) {
                        initNewsArticleBlock();
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Export functions for external use
    window.RiceLipkaNewsBlock = {
        init: initNewsArticleBlock,
        validate: validateContent,
        displayValidation: displayValidation
    };

})();