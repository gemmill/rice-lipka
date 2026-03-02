/**
 * Block Editor JavaScript for Rice+Lipka Architects theme
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    wp.domReady(function() {
        
        /**
         * Add custom block styles
         */
        wp.blocks.registerBlockStyle('core/paragraph', {
            name: 'highlight',
            label: 'Highlight',
            isDefault: false,
        });

        wp.blocks.registerBlockStyle('core/heading', {
            name: 'underlined',
            label: 'Underlined',
            isDefault: false,
        });

        /**
         * Add block patterns for common layouts
         */
        wp.blocks.registerBlockPattern('ricelipka/news-layout', {
            title: 'News Article Layout',
            description: 'A pre-designed layout for news articles with featured image and structured content',
            content: '<!-- wp:acf/news-article /-->',
            categories: ['ricelipka-patterns'],
            keywords: ['news', 'article', 'announcement'],
        });

        wp.blocks.registerBlockPattern('ricelipka/project-showcase', {
            title: 'Project Showcase',
            description: 'A layout for showcasing architectural projects with galleries and metadata',
            content: '<!-- wp:acf/project-portfolio /-->',
            categories: ['ricelipka-patterns'],
            keywords: ['project', 'portfolio', 'architecture'],
        });

        wp.blocks.registerBlockPattern('ricelipka/event-announcement', {
            title: 'Event Announcement',
            description: 'A layout for event announcements with date, time, and location',
            content: '<!-- wp:acf/event-details /-->',
            categories: ['ricelipka-patterns'],
            keywords: ['event', 'announcement', 'calendar'],
        });

        wp.blocks.registerBlockPattern('ricelipka/award-display', {
            title: 'Award Display',
            description: 'A layout for displaying awards and recognition with project associations',
            content: '<!-- wp:acf/award-information /-->',
            categories: ['ricelipka-patterns'],
            keywords: ['award', 'recognition', 'achievement'],
        });

        /**
         * Register pattern category
         */
        wp.blocks.registerBlockPatternCategory('ricelipka-patterns', {
            label: 'Rice+Lipka Patterns'
        });

        /**
         * Initialize real-time preview functionality
         */
        initRealTimePreview();

        /**
         * Initialize block validation
         */
        initBlockValidation();

        /**
         * Initialize responsive preview modes
         */
        initResponsivePreview();

    });

    /**
     * Real-time preview functionality
     */
    function initRealTimePreview() {
        // Subscribe to block editor changes
        let previousSelectedBlock = null;
        
        wp.data.subscribe(function() {
            const selectedBlock = wp.data.select('core/block-editor').getSelectedBlock();
            
            // Check if selection changed to an ACF block
            if (selectedBlock && selectedBlock.name && selectedBlock.name.startsWith('acf/')) {
                if (previousSelectedBlock !== selectedBlock.clientId) {
                    previousSelectedBlock = selectedBlock.clientId;
                    enhanceACFBlockPreview(selectedBlock);
                }
            }
        });

        // Listen for attribute changes
        wp.data.subscribe(function() {
            const selectedBlock = wp.data.select('core/block-editor').getSelectedBlock();
            
            if (selectedBlock && selectedBlock.name && selectedBlock.name.startsWith('acf/')) {
                // Debounce preview updates
                clearTimeout(window.riceLipkaPreviewTimeout);
                window.riceLipkaPreviewTimeout = setTimeout(function() {
                    updateBlockPreview(selectedBlock);
                }, 300);
            }
        });
    }

    /**
     * Enhance ACF block preview
     */
    function enhanceACFBlockPreview(block) {
        setTimeout(function() {
            const blockElement = document.querySelector(`[data-block="${block.clientId}"]`);
            if (blockElement) {
                // Add preview enhancement class
                blockElement.classList.add('enhanced-preview');
                
                // Add real-time validation
                addValidationToBlock(blockElement, block);
                
                // Add preview mode indicator
                addPreviewModeIndicator(blockElement, block);
            }
        }, 100);
    }

    /**
     * Update block preview
     */
    function updateBlockPreview(block) {
        const blockElement = document.querySelector(`[data-block="${block.clientId}"]`);
        if (!blockElement) return;

        // Get block data
        const blockData = {
            block_type: block.name.replace('acf/', ''),
            block_data: block.attributes,
            post_content: wp.data.select('core/editor').getEditedPostContent()
        };

        // Validate block data
        validateBlockData(blockElement, blockData);
    }

    /**
     * Add validation to block
     */
    function addValidationToBlock(blockElement, block) {
        // Remove existing validation
        const existingValidation = blockElement.querySelector('.block-validation-container');
        if (existingValidation) {
            existingValidation.remove();
        }

        // Create validation container
        const validationContainer = document.createElement('div');
        validationContainer.className = 'block-validation-container';
        validationContainer.style.cssText = `
            position: absolute;
            top: 0;
            right: 0;
            z-index: 100;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 200px;
            font-size: 0.8rem;
        `;

        blockElement.style.position = 'relative';
        blockElement.appendChild(validationContainer);
    }

    /**
     * Add preview mode indicator
     */
    function addPreviewModeIndicator(blockElement, block) {
        const indicator = document.createElement('div');
        indicator.className = 'preview-mode-indicator';
        indicator.innerHTML = '👁️ Live Preview';
        indicator.style.cssText = `
            position: absolute;
            top: -30px;
            left: 0;
            background: #0073aa;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 10;
        `;

        blockElement.style.position = 'relative';
        blockElement.appendChild(indicator);
    }

    /**
     * Validate block data
     */
    function validateBlockData(blockElement, blockData) {
        const validationContainer = blockElement.querySelector('.block-validation-container');
        if (!validationContainer) return;

        let validation = {
            isValid: true,
            errors: [],
            warnings: []
        };

        // Validate based on block type
        if (blockData.block_type === 'news-article') {
            validation = validateNewsArticleBlock(blockData.block_data);
        }

        // Update validation display
        displayValidationResults(validationContainer, validation);
    }

    /**
     * Validate news article block
     */
    function validateNewsArticleBlock(data) {
        const validation = {
            isValid: true,
            errors: [],
            warnings: []
        };

        // Check required fields
        if (!data.post_title) {
            validation.isValid = false;
            validation.errors.push('Title is required');
        }

        // Check content length
        const content = data.post_content || '';
        const wordCount = content.split(/\s+/).filter(word => word.length > 0).length;
        
        if (wordCount < 50 && content.length > 0) {
            validation.warnings.push('Content appears short');
        } else if (wordCount > 2000) {
            validation.warnings.push('Content is very long');
        }

        return validation;
    }

    /**
     * Display validation results
     */
    function displayValidationResults(container, validation) {
        container.innerHTML = '';

        if (validation.isValid && validation.warnings.length === 0) {
            container.innerHTML = '<span style="color: #46b450;">✓ Valid</span>';
            return;
        }

        if (validation.errors.length > 0) {
            validation.errors.forEach(function(error) {
                const errorDiv = document.createElement('div');
                errorDiv.style.color = '#dc3232';
                errorDiv.innerHTML = '⚠️ ' + error;
                container.appendChild(errorDiv);
            });
        }

        if (validation.warnings.length > 0) {
            validation.warnings.forEach(function(warning) {
                const warningDiv = document.createElement('div');
                warningDiv.style.color = '#ffb900';
                warningDiv.innerHTML = '⚡ ' + warning;
                container.appendChild(warningDiv);
            });
        }
    }

    /**
     * Initialize block validation
     */
    function initBlockValidation() {
        // Add validation to publish button
        const publishButton = document.querySelector('.editor-post-publish-button');
        if (publishButton) {
            publishButton.addEventListener('click', function(e) {
                const acfBlocks = document.querySelectorAll('[data-type^="acf/"]');
                let hasErrors = false;

                acfBlocks.forEach(function(blockElement) {
                    const validation = blockElement.querySelector('.block-validation-container');
                    if (validation && validation.textContent.includes('⚠️')) {
                        hasErrors = true;
                    }
                });

                if (hasErrors) {
                    e.preventDefault();
                    alert(riceLipkaBlocks.strings.validationError);
                }
            });
        }
    }

    /**
     * Initialize responsive preview modes
     */
    function initResponsivePreview() {
        // Listen for device preview changes
        wp.data.subscribe(function() {
            const deviceType = wp.data.select('core/edit-post').__experimentalGetPreviewDeviceType();
            
            if (deviceType) {
                document.body.classList.remove('preview-desktop', 'preview-tablet', 'preview-mobile');
                document.body.classList.add('preview-' + deviceType.toLowerCase());
                
                // Update block previews for device type
                const acfBlocks = document.querySelectorAll('[data-type^="acf/"]');
                acfBlocks.forEach(function(block) {
                    block.setAttribute('data-preview-device', deviceType.toLowerCase());
                });
            }
        });
    }

    /**
     * Category-based block filtering
     */
    wp.hooks.addFilter(
        'blocks.registerBlockType',
        'ricelipka/category-block-filter',
        function(settings, name) {
            // Add category-specific classes to ACF blocks
            if (name.startsWith('acf/')) {
                settings.attributes = settings.attributes || {};
                settings.attributes.categoryContext = {
                    type: 'string',
                    default: ''
                };
                
                // Add enhanced preview support
                settings.attributes.enhancedPreview = {
                    type: 'boolean',
                    default: true
                };
            }
            return settings;
        }
    );

    /**
     * Enhanced block editor experience
     */
    wp.hooks.addAction(
        'blocks.getSaveElement',
        'ricelipka/enhance-block-output',
        function(element, blockType, attributes) {
            // Add contextual help for ACF blocks
            if (blockType.name && blockType.name.startsWith('acf/')) {
                // Add data attributes for enhanced functionality
                if (element && element.props) {
                    element.props['data-enhanced-preview'] = 'true';
                    element.props['data-block-type'] = blockType.name.replace('acf/', '');
                }
            }
        }
    );

})();