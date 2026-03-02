/**
 * Block Templates and Drag-and-Drop Support
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Wait for WordPress to be ready
    wp.domReady(function() {
        if (!wp.data || !wp.blocks || !wp.blockEditor) {
            return;
        }

        const { select, dispatch, subscribe } = wp.data;
        const { createBlock } = wp.blocks;
        const { __ } = wp.i18n;

        // Initialize template system
        initializeTemplateSystem();
        initializeDragAndDrop();
        initializeFieldValidation();
    });

    /**
     * Initialize the template system
     */
    function initializeTemplateSystem() {
        // Add template insertion functionality
        wp.hooks.addFilter(
            'editor.BlockEdit',
            'ricelipka/add-template-controls',
            addTemplateControls
        );

        // Add template suggestions
        wp.hooks.addFilter(
            'blocks.getSaveContent.extraProps',
            'ricelipka/add-template-data',
            addTemplateData
        );
    }

    /**
     * Add template controls to ACF blocks
     */
    function addTemplateControls(BlockEdit) {
        return function(props) {
            const { name, attributes, setAttributes } = props;
            
            // Only add to ACF blocks
            if (!name.startsWith('acf/')) {
                return wp.element.createElement(BlockEdit, props);
            }

            const { InspectorControls } = wp.blockEditor;
            const { PanelBody, Button, ButtonGroup } = wp.components;

            // Get available templates for this block type
            const blockType = name.replace('acf/', '');
            const templates = getTemplatesForBlock(blockType);

            const templateControls = wp.element.createElement(
                InspectorControls,
                {},
                wp.element.createElement(
                    PanelBody,
                    {
                        title: __('Content Templates', 'ricelipka-theme'),
                        initialOpen: false
                    },
                    wp.element.createElement(
                        'p',
                        {},
                        __('Choose a template to quickly set up your content structure:', 'ricelipka-theme')
                    ),
                    wp.element.createElement(
                        ButtonGroup,
                        {},
                        templates.map(function(template) {
                            return wp.element.createElement(
                                Button,
                                {
                                    key: template.id,
                                    isSecondary: true,
                                    onClick: function() {
                                        applyTemplate(template, props);
                                    }
                                },
                                template.title
                            );
                        })
                    )
                )
            );

            return wp.element.createElement(
                wp.element.Fragment,
                {},
                wp.element.createElement(BlockEdit, props),
                templateControls
            );
        };
    }

    /**
     * Get available templates for a specific block type
     */
    function getTemplatesForBlock(blockType) {
        const templates = riceLipkaTemplates.templates || {};
        const category = getCurrentPostCategory();
        
        if (!category || !templates[category]) {
            return [];
        }

        return Object.keys(templates[category]).map(function(key) {
            const template = templates[category][key];
            return {
                id: key,
                title: template.title,
                description: template.description,
                blocks: template.blocks
            };
        });
    }

    /**
     * Get the current post's primary category
     */
    function getCurrentPostCategory() {
        const { getCurrentPost } = select('core/editor');
        const post = getCurrentPost();
        
        if (!post || !post.categories) {
            return null;
        }

        const primaryCategories = ['news', 'projects', 'events', 'awards'];
        const { getEntityRecord } = select('core');
        
        for (let categoryId of post.categories) {
            const category = getEntityRecord('taxonomy', 'category', categoryId);
            if (category && primaryCategories.includes(category.slug)) {
                return category.slug;
            }
        }
        
        return null;
    }

    /**
     * Apply a template to the current post
     */
    function applyTemplate(template, blockProps) {
        const { insertBlocks } = dispatch('core/block-editor');
        const { getSelectedBlockClientId, getBlockRootClientId } = select('core/block-editor');
        
        // Create blocks from template
        const blocks = template.blocks.map(function(blockData) {
            return createBlock(blockData[0], blockData[1] || {}, blockData[2] || []);
        });

        // Insert blocks after current block
        const selectedBlockId = getSelectedBlockClientId();
        const rootClientId = getBlockRootClientId(selectedBlockId);
        
        insertBlocks(blocks, undefined, rootClientId);
        
        // Show success message
        dispatch('core/notices').createSuccessNotice(
            riceLipkaTemplates.strings.templateApplied,
            { type: 'snackbar', isDismissible: true }
        );
    }

    /**
     * Initialize drag-and-drop functionality
     */
    function initializeDragAndDrop() {
        // Add drag-and-drop validation
        wp.hooks.addFilter(
            'blocks.canInsertBlockType',
            'ricelipka/validate-block-insertion',
            validateBlockInsertion
        );

        // Add visual indicators for drag operations
        wp.hooks.addAction(
            'blocks.dragStart',
            'ricelipka/drag-start',
            onDragStart
        );

        wp.hooks.addAction(
            'blocks.dragEnd',
            'ricelipka/drag-end',
            onDragEnd
        );
    }

    /**
     * Validate block insertion during drag-and-drop
     */
    function validateBlockInsertion(canInsert, blockType, rootClientId) {
        if (!riceLipkaTemplates.dragDrop.enabled) {
            return canInsert;
        }

        const restrictions = riceLipkaTemplates.dragDrop.restrictions;
        const category = getCurrentPostCategory();
        
        // Check category-specific restrictions
        if (category && restrictions.category_specific && restrictions.category_specific[category]) {
            const allowedBlocks = restrictions.category_specific[category];
            if (blockType.startsWith('acf/') && !allowedBlocks.includes(blockType)) {
                return false;
            }
        }

        // Check if block is in allowed draggable blocks
        const allowedBlocks = riceLipkaTemplates.dragDrop.allowedBlocks;
        if (!allowedBlocks.includes(blockType)) {
            return false;
        }

        return canInsert;
    }

    /**
     * Handle drag start
     */
    function onDragStart(clientId) {
        // Add visual indicators
        document.body.classList.add('ricelipka-dragging');
        
        // Highlight valid drop zones
        const validDropZones = document.querySelectorAll('.block-editor-block-list__layout');
        validDropZones.forEach(function(zone) {
            zone.classList.add('ricelipka-valid-drop-zone');
        });
    }

    /**
     * Handle drag end
     */
    function onDragEnd(clientId) {
        // Remove visual indicators
        document.body.classList.remove('ricelipka-dragging');
        
        const dropZones = document.querySelectorAll('.ricelipka-valid-drop-zone');
        dropZones.forEach(function(zone) {
            zone.classList.remove('ricelipka-valid-drop-zone');
        });
    }

    /**
     * Initialize field validation
     */
    function initializeFieldValidation() {
        // Subscribe to block changes for validation
        let previousBlocks = [];
        
        subscribe(function() {
            const { getBlocks } = select('core/block-editor');
            const currentBlocks = getBlocks();
            
            if (currentBlocks !== previousBlocks) {
                validateBlocks(currentBlocks);
                previousBlocks = currentBlocks;
            }
        });

        // Add validation to save process
        wp.hooks.addFilter(
            'editor.preSavePost',
            'ricelipka/validate-before-save',
            validateBeforeSave
        );
    }

    /**
     * Validate blocks for required fields
     */
    function validateBlocks(blocks) {
        const category = getCurrentPostCategory();
        if (!category) return;

        const requiredFields = riceLipkaTemplates.validation.requiredFields[category] || [];
        const errors = [];

        blocks.forEach(function(block) {
            if (block.name.startsWith('acf/')) {
                const blockData = block.attributes.data || {};
                
                requiredFields.forEach(function(field) {
                    if (!blockData[field] || blockData[field].trim() === '') {
                        errors.push({
                            blockId: block.clientId,
                            field: field,
                            message: riceLipkaTemplates.strings.fieldRequired
                        });
                    }
                });
            }
        });

        // Display validation errors
        displayValidationErrors(errors);
    }

    /**
     * Display validation errors in the editor
     */
    function displayValidationErrors(errors) {
        const { removeNotice, createErrorNotice } = dispatch('core/notices');
        
        // Remove previous validation notices
        removeNotice('ricelipka-validation-error');
        
        if (errors.length > 0) {
            const errorMessage = errors.length === 1 
                ? riceLipkaTemplates.strings.fieldRequired
                : `${errors.length} ${__('fields require attention', 'ricelipka-theme')}`;
                
            createErrorNotice(errorMessage, {
                id: 'ricelipka-validation-error',
                isDismissible: true
            });
        }
    }

    /**
     * Validate before saving post
     */
    function validateBeforeSave(post) {
        const { getBlocks } = select('core/block-editor');
        const blocks = getBlocks();
        const category = getCurrentPostCategory();
        
        if (!category) return post;

        const requiredFields = riceLipkaTemplates.validation.requiredFields[category] || [];
        let hasErrors = false;

        blocks.forEach(function(block) {
            if (block.name.startsWith('acf/')) {
                const blockData = block.attributes.data || {};
                
                requiredFields.forEach(function(field) {
                    if (!blockData[field] || blockData[field].trim() === '') {
                        hasErrors = true;
                    }
                });
            }
        });

        if (hasErrors) {
            dispatch('core/notices').createErrorNotice(
                riceLipkaTemplates.strings.validationError,
                { isDismissible: true }
            );
            
            // Prevent save by returning null
            return null;
        }

        return post;
    }

    /**
     * Add template data to block save content
     */
    function addTemplateData(extraProps, blockType, attributes) {
        if (blockType.name && blockType.name.startsWith('acf/')) {
            extraProps['data-ricelipka-block'] = blockType.name;
            extraProps['data-category'] = getCurrentPostCategory();
        }
        return extraProps;
    }

})();