/**
 * ACF Help System JavaScript
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Help system object
    const ACFHelp = {
        
        // Initialize the help system
        init: function() {
            this.addFieldHelp();
            this.initOnboarding();
            this.bindEvents();
            this.initFieldValidation();
        },
        
        // Add help icons and tooltips to fields
        addFieldHelp: function() {
            $('.has-help-content .acf-label label').each(function() {
                const $label = $(this);
                const $field = $label.closest('.acf-field');
                const helpText = $field.data('help');
                
                if (helpText && !$label.find('.acf-help-tooltip').length) {
                    const $helpIcon = $('<span class="acf-help-tooltip">' +
                        '<button type="button" class="acf-help-icon" aria-label="Show field help">?</button>' +
                        '<div class="acf-help-content" role="tooltip">' + helpText + '</div>' +
                        '</span>');
                    
                    $label.append($helpIcon);
                }
            });
        },
        
        // Initialize onboarding for new users
        initOnboarding: function() {
            // Check if user has dismissed onboarding
            if (localStorage.getItem('acf_help_onboarding_dismissed')) {
                return;
            }
            
            // Check if this is a first visit to this block type
            const blockType = this.detectCurrentBlockType();
            if (!blockType) return;
            
            const dismissedKey = 'acf_help_onboarding_' + blockType + '_dismissed';
            if (localStorage.getItem(dismissedKey)) {
                return;
            }
            
            // Show onboarding banner after a short delay
            setTimeout(() => {
                this.showOnboardingBanner(blockType);
            }, 1000);
        },
        
        // Show onboarding banner
        showOnboardingBanner: function(blockType) {
            const blockTypeNames = {
                'news_article': 'News Article',
                'project_portfolio': 'Project Portfolio',
                'event_details': 'Event Details',
                'award_information': 'Award Information'
            };
            
            const blockName = blockTypeNames[blockType] || 'Content';
            
            const $banner = $('<div class="acf-onboarding-banner" role="banner">' +
                '<button class="acf-onboarding-close" aria-label="Close onboarding banner">&times;</button>' +
                '<div class="acf-onboarding-content">' +
                    '<div class="acf-onboarding-text">' +
                        '<h4>Welcome to ' + blockName + ' Blocks!</h4>' +
                        '<p>New to creating ' + blockName.toLowerCase() + ' content? We can help you get started with a quick tutorial.</p>' +
                    '</div>' +
                    '<div class="acf-onboarding-actions">' +
                        '<button class="acf-onboarding-btn" data-action="tutorial">Start Tutorial</button>' +
                        '<button class="acf-onboarding-btn" data-action="dismiss">Maybe Later</button>' +
                    '</div>' +
                '</div>' +
            '</div>');
            
            // Insert banner at the top of the ACF fields
            const $fieldsContainer = $('.acf-fields').first();
            if ($fieldsContainer.length) {
                $fieldsContainer.prepend($banner);
            } else {
                // Fallback: insert after post title
                $('#titlediv').after($banner);
            }
        },
        
        // Detect current block type based on visible fields
        detectCurrentBlockType: function() {
            if ($('[data-name="publication_date"]').length) return 'news_article';
            if ($('[data-name="completion_status"]').length) return 'project_portfolio';
            if ($('[data-name="awarding_organization"]').length) return 'award_information';
            return null;
        },
        
        // Bind event handlers
        bindEvents: function() {
            // Onboarding banner actions
            $(document).on('click', '.acf-onboarding-btn', (e) => {
                const action = $(e.target).data('action');
                
                if (action === 'tutorial') {
                    this.startTutorial();
                } else if (action === 'dismiss') {
                    this.dismissOnboarding();
                }
            });
            
            $(document).on('click', '.acf-onboarding-close', () => {
                this.dismissOnboarding();
            });
            
            // Help icon keyboard navigation
            $(document).on('keydown', '.acf-help-icon', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(e.target).trigger('click');
                }
            });
            
            // Re-initialize help when ACF adds new fields
            $(document).on('acf/setup_fields', () => {
                setTimeout(() => {
                    this.addFieldHelp();
                }, 100);
            });
        },
        
        // Dismiss onboarding banner
        dismissOnboarding: function() {
            $('.acf-onboarding-banner').fadeOut(300, function() {
                $(this).remove();
            });
            
            const blockType = this.detectCurrentBlockType();
            if (blockType) {
                localStorage.setItem('acf_help_onboarding_' + blockType + '_dismissed', 'true');
            }
        },
        
        // Start tutorial modal
        startTutorial: function() {
            const blockType = this.detectCurrentBlockType();
            if (!blockType || !window.acfHelp || !window.acfHelp.tutorials[blockType]) {
                console.warn('Tutorial not available for block type:', blockType);
                return;
            }
            
            this.dismissOnboarding();
            this.showTutorialModal(blockType);
        },
        
        // Show tutorial modal
        showTutorialModal: function(blockType) {
            const tutorial = window.acfHelp.tutorials[blockType];
            let currentStep = 0;
            
            const $overlay = $('<div class="acf-tutorial-overlay" role="dialog" aria-modal="true" aria-labelledby="tutorial-title"></div>');
            const $modal = $('<div class="acf-tutorial-modal">' +
                '<div class="acf-tutorial-header">' +
                    '<h2 id="tutorial-title" class="acf-tutorial-title">' + tutorial.title + '</h2>' +
                    '<div class="acf-tutorial-progress" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="' + tutorial.steps.length + '">' +
                        '<div class="acf-tutorial-progress-bar"></div>' +
                    '</div>' +
                '</div>' +
                '<div class="acf-tutorial-body">' +
                    this.generateTutorialSteps(tutorial.steps) +
                '</div>' +
                '<div class="acf-tutorial-footer">' +
                    '<div class="acf-tutorial-step-info">' +
                        'Step <span class="acf-tutorial-step-current">1</span> of ' +
                        '<span class="acf-tutorial-step-total">' + tutorial.steps.length + '</span>' +
                    '</div>' +
                    '<div class="acf-tutorial-nav">' +
                        '<button class="acf-tutorial-btn acf-tutorial-btn-secondary acf-tutorial-prev" disabled>Previous</button>' +
                        '<button class="acf-tutorial-btn acf-tutorial-btn-primary acf-tutorial-next">Next Step</button>' +
                        '<button class="acf-tutorial-btn acf-tutorial-btn-secondary acf-tutorial-skip">Skip Tutorial</button>' +
                    '</div>' +
                '</div>' +
            '</div>');
            
            $('body').append($overlay.append($modal));
            $overlay.fadeIn(200);
            
            // Focus management
            $modal.find('.acf-tutorial-next').focus();
            
            this.updateTutorialStep(currentStep, tutorial.steps.length, $modal);
            
            // Tutorial navigation
            $modal.on('click', '.acf-tutorial-next', () => {
                if (currentStep < tutorial.steps.length - 1) {
                    currentStep++;
                    this.updateTutorialStep(currentStep, tutorial.steps.length, $modal);
                } else {
                    this.closeTutorial($overlay);
                }
            });
            
            $modal.on('click', '.acf-tutorial-prev', () => {
                if (currentStep > 0) {
                    currentStep--;
                    this.updateTutorialStep(currentStep, tutorial.steps.length, $modal);
                }
            });
            
            $modal.on('click', '.acf-tutorial-skip', () => {
                this.closeTutorial($overlay);
            });
            
            // Close on overlay click (but not modal click)
            $overlay.on('click', (e) => {
                if (e.target === $overlay[0]) {
                    this.closeTutorial($overlay);
                }
            });
            
            // Keyboard navigation
            $modal.on('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeTutorial($overlay);
                } else if (e.key === 'ArrowLeft' && currentStep > 0) {
                    $modal.find('.acf-tutorial-prev').click();
                } else if (e.key === 'ArrowRight' && currentStep < tutorial.steps.length - 1) {
                    $modal.find('.acf-tutorial-next').click();
                }
            });
        },
        
        // Update tutorial step display
        updateTutorialStep: function(step, total, $modal) {
            $modal.find('.acf-tutorial-step').removeClass('active');
            $modal.find('.acf-tutorial-step').eq(step).addClass('active');
            
            $modal.find('.acf-tutorial-step-current').text(step + 1);
            $modal.find('.acf-tutorial-progress-bar').css('width', ((step + 1) / total * 100) + '%');
            $modal.find('.acf-tutorial-progress').attr('aria-valuenow', step + 1);
            
            $modal.find('.acf-tutorial-prev').prop('disabled', step === 0);
            
            if (step === total - 1) {
                $modal.find('.acf-tutorial-next').text(window.acfHelp.strings.finish_tutorial || 'Finish Tutorial');
            } else {
                $modal.find('.acf-tutorial-next').text(window.acfHelp.strings.next_step || 'Next Step');
            }
        },
        
        // Generate tutorial step HTML
        generateTutorialSteps: function(steps) {
            let html = '';
            steps.forEach((step, index) => {
                html += '<div class="acf-tutorial-step' + (index === 0 ? ' active' : '') + '">' +
                    '<h3>' + step.title + '</h3>' +
                    '<p>' + step.content + '</p>';
                
                if (step.tip) {
                    html += '<div class="acf-tutorial-tip">' + step.tip + '</div>';
                }
                
                html += '</div>';
            });
            return html;
        },
        
        // Close tutorial modal
        closeTutorial: function($overlay) {
            $overlay.fadeOut(200, function() {
                $overlay.remove();
                
                // Return focus to the first field
                $('.acf-field input, .acf-field textarea, .acf-field select').first().focus();
            });
            
            // Mark tutorial as completed for this block type
            const blockType = this.detectCurrentBlockType();
            if (blockType) {
                localStorage.setItem('acf_help_tutorial_' + blockType + '_completed', 'true');
            }
        },
        
        // Initialize field validation enhancements
        initFieldValidation: function() {
            // Add visual indicators for required fields
            $('.acf-field[data-required="1"]').each(function() {
                const $field = $(this);
                const $label = $field.find('.acf-label label');
                
                if (!$label.find('.acf-required-indicator').length) {
                    $label.append('<span class="acf-required-indicator" style="color: #d63638; margin-left: 4px;">*</span>');
                }
            });
            
            // Enhanced validation messages
            $(document).on('acf/validation_failure', function(e, form) {
                // Scroll to first error field
                const $firstError = form.find('.acf-error').first();
                if ($firstError.length) {
                    $('html, body').animate({
                        scrollTop: $firstError.offset().top - 100
                    }, 500);
                }
                
                // Show helpful error summary
                const errorCount = form.find('.acf-error').length;
                const $errorSummary = $('<div class="acf-error-summary" style="background: #fcf2f2; border: 1px solid #d63638; color: #d63638; padding: 12px; margin: 10px 0; border-radius: 4px;">' +
                    '<strong>Please fix ' + errorCount + ' error' + (errorCount > 1 ? 's' : '') + ' before publishing:</strong>' +
                    '<ul style="margin: 8px 0 0 20px;"></ul>' +
                '</div>');
                
                const $errorList = $errorSummary.find('ul');
                form.find('.acf-error').each(function() {
                    const $field = $(this);
                    const fieldLabel = $field.find('.acf-label label').text().replace('*', '').trim();
                    const errorMessage = $field.find('.acf-notice.-error p').text();
                    
                    $errorList.append('<li>' + fieldLabel + ': ' + errorMessage + '</li>');
                });
                
                // Remove existing error summary and add new one
                form.find('.acf-error-summary').remove();
                form.prepend($errorSummary);
                
                // Auto-remove error summary after 10 seconds
                setTimeout(() => {
                    $errorSummary.fadeOut();
                }, 10000);
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        ACFHelp.init();
    });
    
    // Re-initialize when ACF loads new content
    $(document).on('acf/setup_fields', function() {
        setTimeout(() => {
            ACFHelp.addFieldHelp();
            ACFHelp.initFieldValidation();
        }, 100);
    });
    
})(jQuery);