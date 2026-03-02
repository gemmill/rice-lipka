/**
 * Drag-and-Drop Validation System
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
        const { __ } = wp.i18n;

        // Initialize validation system
        initializeDragDropValidation();
        initializeFieldValidation();
        initializeBlockReordering();
    });

    /**
     * Initialize drag-and-drop validation
     */
    function initializeDragDropValidation() {
        // Add validation hooks
        wp.hooks.addFilter(
            'blocks.canInsertBlockType',
            'ricelipka/validate-block-insertion',
            validateBlockInsertion
        );

        wp.hooks.addFilter(
            'blocks.canMoveBlocks',
            'ricelipka/validate-block-movement',
            validateBlockMovement
        );

        // Add visual feedback for drag operations
        wp.hooks.addAction(
            'blocks.dragStart',
            'ricelipka/drag-start-validation',
            onDragStartValidation
        );

        wp.hooks.addAction(
            'blocks.dragEnd',
            'ricelipka/drag-end-validation',
            onDragEndValidation
        );
    }

    /**
     * Validate block insertion based on category and content rules
     */
    function validateBlockInsertion(canInsert, blockType, rootClientId, clientId) {
        if (!canInsert) {
            return false;
        }

        const category = getCurrentPostCategory();
        if (!category) {
            return canInsert;
        }

        // Get validation rules
        const rules = getValidationRules();
        
        // Check category-specific block restrictions
        if (blockType.startsWith('acf/')) {
            const allowedBlocks = rules.categoryBlocks[category] || [];
            if (!allowedBlocks.includes(blockType)) {
                showValidationError(__('This block type is not allowed in this category', 'ricelipka-theme'));
                return false;
            }
        }

        // Check structural rules
        if (rules.structuralRules.acfBlocksFirst && blockType.startsWith('acf/')) {
            const { getBlocks } = select('core/block-editor');
            const blocks = getBlocks(rootClientId);
            
            // Find first non-ACF block
            const firstNonAcfIndex = blocks.findIndex(block => !block.name.startsWith('acf/'));
            
            if (firstNonAcfIndex !== -1) {
                const insertIndex = clientId ? blocks.findIndex(block => block.clientId === clientId) : blocks.length;
                
                if (insertIndex > firstNonAcfIndex) {
                    showValidationError(__('ACF blocks should be placed before content blocks', 'ricelipka-theme'));
                    return false;
                }
            }
        }

        return canInsert;
    }

    /**
     * Validate block movement during drag-and-drop
     */
    function validateBlockMovement(canMove, clientIds, rootClientId) {
        if (!canMove) {
            return false;
        }

        const { getBlock, getBlocks } = select('core/block-editor');
        const rules = getValidationRules();
        
        // Check each block being moved
        for (let clientId of clientIds) {
            const block = getBlock(clientId);
            if (!block) continue;

            // Validate ACF block positioning
            if (block.name.startsWith('acf/') && rules.structuralRules.acfBlocksFirst) {
                const allBlocks = getBlocks(rootClientId);
                const targetIndex = allBlocks.findIndex(b => b.clientId === clientId);
                const firstNonAcfIndex = allBlocks.findIndex(b => !b.name.startsWith('acf/'));
                
                if (firstNonAcfIndex !== -1 && targetIndex > firstNonAcfIndex) {
                    showValidationError(__('ACF blocks cannot be moved after content blocks', 'ricelipka-theme'));
                    return false;
                }
            }

            // Validate required field completion before moving
            if (block.name.startsWith('acf/')) {
                const validation = validateBlockFields(block);
                if (!validation.isValid && rules.requireFieldsBeforeMove) {
                    showValidationError(__('Complete required fields before moving this block', 'ricelipka-theme'));
                    return false;
                }
            }
        }

        return canMove;
    }

    /**
     * Handle drag start with validation feedback
     */
    function onDragStartValidation(clientId) {
        const { getBlock } = select('core/block-editor');
        const block = getBlock(clientId);
        
        if (!block) return;

        // Add visual indicators
        document.body.classList.add('ricelipka-dragging');
        
        // Highlight valid drop zones based on block type
        highlightValidDropZones(block);
        
        // Show validation hints
        showDragValidationHints(block);
    }

    /**
     * Handle drag end cleanup
     */
    function onDragEndValidation(clientId) {
        // Remove visual indicators
        document.body.classList.remove('ricelipka-dragging');
        
        // Clear validation hints
        clearValidationHints();
        
        // Remove drop zone highlights
        clearDropZoneHighlights();
    }

    /**
     * Initialize field validation system
     */
    function initializeFieldValidation() {
        // Subscribe to block changes for real-time validation
        let previousBlocks = [];
        
        subscribe(function() {
            const { getBlocks } = select('core/block-editor');
            const currentBlocks = getBlocks();
            
            if (JSON.stringify(currentBlocks) !== JSON.stringify(previousBlocks)) {
                validateAllBlocks(currentBlocks);
                previousBlocks = currentBlocks;
            }
        });

        // Add validation to block selection
        wp.hooks.addAction(
            'blocks.selectBlock',
            'ricelipka/validate-selected-block',
            validateSelectedBlock
        );
    }

    /**
     * Initialize block reordering enhancements
     */
    function initializeBlockReordering() {
        // Add reordering controls to ACF blocks
        wp.hooks.addFilter(
            'editor.BlockEdit',
            'ricelipka/add-reordering-controls',
            addReorderingControls
        );

        // Add keyboard shortcuts for reordering
        document.addEventListener('keydown', handleReorderingKeyboard);
    }

    /**
     * Get validation rules based on current context
     */
    function getValidationRules() {
        const category = getCurrentPostCategory();
        
        return {
            categoryBlocks: {
                'news': ['acf/news-article'],
                'projects': ['acf/project-portfolio'],
                'events': ['acf/event-details'],
                'awards': ['acf/award-information']
            },
            structuralRules: {
                acfBlocksFirst: true,
                maintainHierarchy: true,
                requireFieldsBeforeMove: false
            },
            fieldValidation: {
                realTime: true,
                showErrors: true,
                preventSaveOnError: true
            }
        };
    }

    /**
     * Get current post category
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
     * Validate all blocks in the editor
     */
    function validateAllBlocks(blocks) {
        const category = getCurrentPostCategory();
        if (!category) return;

        const errors = [];
        const warnings = [];

        blocks.forEach(function(block) {
            if (block.name.startsWith('acf/')) {
                const validation = validateBlockFields(block);
                
                if (!validation.isValid) {
                    errors.push({
                        blockId: block.clientId,
                        blockName: block.name,
                        errors: validation.errors
                    });
                }
                
                if (validation.warnings && validation.warnings.length > 0) {
                    warnings.push({
                        blockId: block.clientId,
                        blockName: block.name,
                        warnings: validation.warnings
                    });
                }
            }
        });

        // Update validation display
        updateValidationDisplay(errors, warnings);
    }

    /**
     * Validate fields in a specific block
     */
    function validateBlockFields(block) {
        const category = getCurrentPostCategory();
        const blockType = block.name.replace('acf/', '');
        
        const requiredFields = getRequiredFieldsForBlock(blockType, category);
        const blockData = block.attributes.data || {};
        
        const validation = {
            isValid: true,
            errors: [],
            warnings: []
        };

        // Check required fields
        requiredFields.forEach(function(field) {
            const value = blockData[field];
            
            if (!value || (typeof value === 'string' && value.trim() === '')) {
                validation.isValid = false;
                validation.errors.push({
                    field: field,
                    message: __('This field is required', 'ricelipka-theme')
                });
            }
        });

        // Check field relationships
        const relationships = getFieldRelationships(blockType);
        relationships.forEach(function(relationship) {
            if (blockData[relationship.field] && !validateRelationship(blockData[relationship.field], relationship)) {
                validation.warnings.push({
                    field: relationship.field,
                    message: relationship.warningMessage || __('Related content may not exist', 'ricelipka-theme')
                });
            }
        });

        return validation;
    }

    /**
     * Get required fields for a specific block type and category
     */
    function getRequiredFieldsForBlock(blockType, category) {
        const fieldMap = {
            'news-article': ['publication_date', 'excerpt'],
            'project-portfolio': ['completion_status', 'project_type', 'client', 'location'],
            'event-details': ['event_date', 'event_time', 'location'],
            'award-information': ['awarding_organization', 'date_received']
        };

        return fieldMap[blockType] || [];
    }

    /**
     * Get field relationships for validation
     */
    function getFieldRelationships(blockType) {
        const relationships = {
            'award-information': [
                {
                    field: 'associated_project',
                    type: 'post_object',
                    category: 'projects',
                    warningMessage: __('Associated project should exist in the Projects category', 'ricelipka-theme')
                }
            ]
        };

        return relationships[blockType] || [];
    }

    /**
     * Validate a field relationship
     */
    function validateRelationship(value, relationship) {
        if (relationship.type === 'post_object' && value) {
            // This would need to check if the referenced post exists and has the correct category
            // For now, we'll assume it's valid if a value exists
            return true;
        }
        
        return true;
    }

    /**
     * Highlight valid drop zones for a block
     */
    function highlightValidDropZones(block) {
        const dropZones = document.querySelectorAll('.block-editor-block-list__layout');
        const rules = getValidationRules();
        
        dropZones.forEach(function(zone) {
            // Check if this zone can accept the block type
            const canAccept = validateBlockInsertion(true, block.name, zone.dataset.rootClientId);
            
            if (canAccept) {
                zone.classList.add('ricelipka-valid-drop-zone');
            } else {
                zone.classList.add('ricelipka-invalid-drop-zone');
            }
        });
    }

    /**
     * Show drag validation hints
     */
    function showDragValidationHints(block) {
        const hint = document.createElement('div');
        hint.className = 'ricelipka-drag-hint';
        hint.textContent = __('Drag to reorder. ACF blocks should come first.', 'ricelipka-theme');
        
        document.body.appendChild(hint);
        
        // Position hint near cursor
        document.addEventListener('mousemove', updateHintPosition);
        
        function updateHintPosition(e) {
            hint.style.left = (e.clientX + 10) + 'px';
            hint.style.top = (e.clientY - 30) + 'px';
        }
    }

    /**
     * Clear validation hints
     */
    function clearValidationHints() {
        const hints = document.querySelectorAll('.ricelipka-drag-hint');
        hints.forEach(hint => hint.remove());
        
        document.removeEventListener('mousemove', updateHintPosition);
    }

    /**
     * Clear drop zone highlights
     */
    function clearDropZoneHighlights() {
        const zones = document.querySelectorAll('.ricelipka-valid-drop-zone, .ricelipka-invalid-drop-zone');
        zones.forEach(function(zone) {
            zone.classList.remove('ricelipka-valid-drop-zone', 'ricelipka-invalid-drop-zone');
        });
    }

    /**
     * Update validation display in the editor
     */
    function updateValidationDisplay(errors, warnings) {
        const { removeNotice, createErrorNotice, createWarningNotice } = dispatch('core/notices');
        
        // Remove previous validation notices
        removeNotice('ricelipka-validation-errors');
        removeNotice('ricelipka-validation-warnings');
        
        // Show errors
        if (errors.length > 0) {
            const errorMessage = errors.length === 1 
                ? __('1 block has validation errors', 'ricelipka-theme')
                : `${errors.length} ${__('blocks have validation errors', 'ricelipka-theme')}`;
                
            createErrorNotice(errorMessage, {
                id: 'ricelipka-validation-errors',
                isDismissible: true,
                actions: [
                    {
                        label: __('Show Details', 'ricelipka-theme'),
                        onClick: () => showValidationDetails(errors, 'error')
                    }
                ]
            });
        }

        // Show warnings
        if (warnings.length > 0) {
            const warningMessage = warnings.length === 1 
                ? __('1 block has validation warnings', 'ricelipka-theme')
                : `${warnings.length} ${__('blocks have validation warnings', 'ricelipka-theme')}`;
                
            createWarningNotice(warningMessage, {
                id: 'ricelipka-validation-warnings',
                isDismissible: true,
                actions: [
                    {
                        label: __('Show Details', 'ricelipka-theme'),
                        onClick: () => showValidationDetails(warnings, 'warning')
                    }
                ]
            });
        }
    }

    /**
     * Show validation error message
     */
    function showValidationError(message) {
        const { createErrorNotice } = dispatch('core/notices');
        
        createErrorNotice(message, {
            isDismissible: true,
            type: 'snackbar'
        });
    }

    /**
     * Validate selected block
     */
    function validateSelectedBlock(clientId) {
        const { getBlock } = select('core/block-editor');
        const block = getBlock(clientId);
        
        if (!block || !block.name.startsWith('acf/')) {
            return;
        }

        const validation = validateBlockFields(block);
        
        // Add visual indicators to the block
        const blockElement = document.querySelector(`[data-block="${clientId}"]`);
        if (blockElement) {
            blockElement.classList.remove('ricelipka-block-validation-error', 'ricelipka-validation-success');
            
            if (!validation.isValid) {
                blockElement.classList.add('ricelipka-block-validation-error');
            } else {
                blockElement.classList.add('ricelipka-validation-success');
            }
        }
    }

    /**
     * Add reordering controls to ACF blocks
     */
    function addReorderingControls(BlockEdit) {
        return function(props) {
            const { name, clientId } = props;
            
            if (!name.startsWith('acf/')) {
                return wp.element.createElement(BlockEdit, props);
            }

            const { BlockControls } = wp.blockEditor;
            const { ToolbarGroup, ToolbarButton } = wp.components;
            const { __ } = wp.i18n;

            const reorderControls = wp.element.createElement(
                BlockControls,
                {},
                wp.element.createElement(
                    ToolbarGroup,
                    {},
                    wp.element.createElement(
                        ToolbarButton,
                        {
                            icon: 'arrow-up-alt2',
                            label: __('Move Up', 'ricelipka-theme'),
                            onClick: () => moveBlockUp(clientId)
                        }
                    ),
                    wp.element.createElement(
                        ToolbarButton,
                        {
                            icon: 'arrow-down-alt2',
                            label: __('Move Down', 'ricelipka-theme'),
                            onClick: () => moveBlockDown(clientId)
                        }
                    )
                )
            );

            return wp.element.createElement(
                wp.element.Fragment,
                {},
                wp.element.createElement(BlockEdit, props),
                reorderControls
            );
        };
    }

    /**
     * Move block up in the order
     */
    function moveBlockUp(clientId) {
        const { moveBlocksUp } = dispatch('core/block-editor');
        moveBlocksUp([clientId]);
    }

    /**
     * Move block down in the order
     */
    function moveBlockDown(clientId) {
        const { moveBlocksDown } = dispatch('core/block-editor');
        moveBlocksDown([clientId]);
    }

    /**
     * Handle keyboard shortcuts for reordering
     */
    function handleReorderingKeyboard(event) {
        // Ctrl/Cmd + Shift + Up/Down for block reordering
        if ((event.ctrlKey || event.metaKey) && event.shiftKey) {
            const { getSelectedBlockClientId } = select('core/block-editor');
            const selectedBlock = getSelectedBlockClientId();
            
            if (!selectedBlock) return;

            if (event.key === 'ArrowUp') {
                event.preventDefault();
                moveBlockUp(selectedBlock);
            } else if (event.key === 'ArrowDown') {
                event.preventDefault();
                moveBlockDown(selectedBlock);
            }
        }
    }

    /**
     * Show detailed validation information
     */
    function showValidationDetails(items, type) {
        // This would open a modal or sidebar with detailed validation information
        console.log(`${type} details:`, items);
    }

})();