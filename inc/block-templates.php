<?php
/**
 * Block Templates and Drag-and-Drop Support
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register block templates for consistent content creation
 */
function ricelipka_register_block_templates() {
    // Register block template categories
    add_filter('block_categories_all', 'ricelipka_add_template_categories', 10, 2);
    
    // Add template support to post editor
    add_action('enqueue_block_editor_assets', 'ricelipka_enqueue_template_assets');
    
    // Register category-specific templates
    add_filter('default_content', 'ricelipka_apply_category_templates', 10, 2);
    add_filter('default_title', 'ricelipka_apply_category_titles', 10, 2);
}
add_action('init', 'ricelipka_register_block_templates');

/**
 * Add template categories to block editor
 */
function ricelipka_add_template_categories($categories, $post) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'ricelipka-templates',
                'title' => __('Rice+Lipka Templates', 'ricelipka-theme'),
                'icon'  => 'layout',
            ),
        )
    );
}

/**
 * Enqueue template assets for block editor
 */
function ricelipka_enqueue_template_assets() {
    wp_enqueue_script(
        'ricelipka-block-templates',
        get_template_directory_uri() . '/assets/js/block-templates.js',
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-data', 'wp-compose'),
        wp_get_theme()->get('Version'),
        true
    );

    wp_enqueue_style(
        'ricelipka-block-templates',
        get_template_directory_uri() . '/assets/css/block-templates.css',
        array('wp-edit-blocks'),
        wp_get_theme()->get('Version')
    );

    // Localize script with template data
    wp_localize_script('ricelipka-block-templates', 'riceLipkaTemplates', array(
        'templates' => ricelipka_get_block_templates(),
        'validation' => array(
            'requiredFields' => ricelipka_get_required_fields_by_category(),
            'fieldRelationships' => ricelipka_get_field_relationships(),
        ),
        'dragDrop' => array(
            'enabled' => true,
            'allowedBlocks' => ricelipka_get_draggable_blocks(),
            'restrictions' => ricelipka_get_drag_restrictions(),
        ),
        'strings' => array(
            'templateApplied' => __('Template applied successfully', 'ricelipka-theme'),
            'validationError' => __('Please complete all required fields', 'ricelipka-theme'),
            'dragRestriction' => __('This block cannot be moved to this position', 'ricelipka-theme'),
            'fieldRequired' => __('This field is required', 'ricelipka-theme'),
        )
    ));
}

/**
 * Apply category-specific templates to new posts
 */
function ricelipka_apply_category_templates($content, $post) {
    // Check if this is a new post with a category parameter
    if (isset($_GET['category'])) {
        $category = sanitize_text_field($_GET['category']);
        $templates = ricelipka_get_category_templates();
        
        if (isset($templates[$category])) {
            return $templates[$category]['content'];
        }
    }
    
    return $content;
}

/**
 * Apply category-specific titles to new posts
 */
function ricelipka_apply_category_titles($title, $post) {
    if (isset($_GET['category'])) {
        $category = sanitize_text_field($_GET['category']);
        $templates = ricelipka_get_category_templates();
        
        if (isset($templates[$category])) {
            return $templates[$category]['title'];
        }
    }
    
    return $title;
}

/**
 * Get block templates for each content type
 */
function ricelipka_get_block_templates() {
    return array(
        'news' => array(
            'featured-story' => array(
                'title' => __('Featured Story Template', 'ricelipka-theme'),
                'description' => __('Template for major announcements and featured stories', 'ricelipka-theme'),
                'blocks' => array(
                    array('acf/news-article', array(
                        'align' => 'wide',
                        'data' => array(
                            'subcategory' => 'project_updates'
                        )
                    )),
                    array('core/separator', array('className' => 'is-style-wide')),
                    array('core/columns', array('align' => 'wide'), array(
                        array('core/column', array('width' => '66.66%'), array(
                            array('core/paragraph', array('fontSize' => 'large')),
                            array('core/heading', array('level' => 3)),
                            array('core/list')
                        )),
                        array('core/column', array('width' => '33.33%'), array(
                            array('core/heading', array('level' => 4, 'content' => 'Quick Facts')),
                            array('core/list'),
                            array('core/quote')
                        ))
                    ))
                )
            ),
            'brief-update' => array(
                'title' => __('Brief Update Template', 'ricelipka-theme'),
                'description' => __('Template for quick updates and short announcements', 'ricelipka-theme'),
                'blocks' => array(
                    array('acf/news-article'),
                    array('core/paragraph'),
                    array('core/heading', array('level' => 3, 'content' => 'Key Points')),
                    array('core/list')
                )
            )
        ),
        'projects' => array(
            'showcase' => array(
                'title' => __('Project Showcase Template', 'ricelipka-theme'),
                'description' => __('Comprehensive showcase for major projects', 'ricelipka-theme'),
                'blocks' => array(
                    array('acf/project-portfolio', array('align' => 'wide')),
                    array('core/separator', array('className' => 'is-style-wide')),
                    array('core/columns', array('align' => 'wide'), array(
                        array('core/column', array('width' => '60%')),
                        array('core/column', array('width' => '40%'))
                    ))
                )
            ),
            'case-study' => array(
                'title' => __('Case Study Template', 'ricelipka-theme'),
                'description' => __('In-depth analysis template for project documentation', 'ricelipka-theme'),
                'blocks' => array(
                    array('acf/project-portfolio', array('align' => 'wide')),
                    array('core/heading', array('level' => 3, 'content' => 'Project Challenge')),
                    array('core/paragraph'),
                    array('core/heading', array('level' => 3, 'content' => 'Our Approach')),
                    array('core/columns'),
                    array('core/heading', array('level' => 3, 'content' => 'Results & Impact')),
                    array('core/quote')
                )
            )
        ),
        'events' => array(
            'upcoming' => array(
                'title' => __('Upcoming Event Template', 'ricelipka-theme'),
                'description' => __('Template for upcoming events with registration', 'ricelipka-theme'),
                'blocks' => array(
                    array('acf/event-details', array('align' => 'wide')),
                    array('core/group', array(
                        'align' => 'wide',
                        'style' => array(
                            'spacing' => array('padding' => array('top' => '2rem', 'bottom' => '2rem')),
                            'color' => array('background' => '#f8f9fa')
                        )
                    )),
                    array('core/columns', array('align' => 'wide'))
                )
            ),
            'workshop' => array(
                'title' => __('Workshop Template', 'ricelipka-theme'),
                'description' => __('Template for professional workshops and training', 'ricelipka-theme'),
                'blocks' => array(
                    array('acf/event-details'),
                    array('core/heading', array('level' => 3, 'content' => 'Learning Objectives')),
                    array('core/list'),
                    array('core/heading', array('level' => 3, 'content' => 'Workshop Schedule')),
                    array('core/list')
                )
            )
        ),
        'awards' => array(
            'recognition' => array(
                'title' => __('Recognition Display Template', 'ricelipka-theme'),
                'description' => __('Template for major awards and recognition', 'ricelipka-theme'),
                'blocks' => array(
                    array('acf/award-information', array('align' => 'wide')),
                    array('core/group', array(
                        'align' => 'wide',
                        'style' => array(
                            'spacing' => array('padding' => array('top' => '3rem', 'bottom' => '3rem')),
                            'color' => array('background' => '#f8f9fa')
                        )
                    )),
                    array('core/columns', array('align' => 'wide'))
                )
            ),
            'timeline' => array(
                'title' => __('Achievement Timeline Template', 'ricelipka-theme'),
                'description' => __('Template for displaying multiple awards chronologically', 'ricelipka-theme'),
                'blocks' => array(
                    array('acf/award-information'),
                    array('core/heading', array('level' => 3, 'content' => 'Our Recognition Journey')),
                    array('core/group', array(
                        'style' => array(
                            'color' => array('background' => '#f8f9fa')
                        )
                    ))
                )
            )
        )
    );
}

/**
 * Get category-specific templates for new posts
 */
function ricelipka_get_category_templates() {
    return array(
        'news' => array(
            'title' => 'New News Article',
            'content' => '<!-- wp:acf/news-article {"data":{"news_title":"","publication_date":"' . date('Y-m-d') . '","excerpt":"","subcategory":"project_updates"}} /-->' . "\n\n" . 
                        '<!-- wp:paragraph {"placeholder":"Start writing your news article content here..."} -->' . "\n" .
                        '<p></p>' . "\n" .
                        '<!-- /wp:paragraph -->'
        ),
        'projects' => array(
            'title' => 'New Project Portfolio',
            'content' => '<!-- wp:acf/project-portfolio {"data":{"project_name":"","completion_status":"planned","project_type":"civic","client":"","location":""}} /-->' . "\n\n" . 
                        '<!-- wp:paragraph {"placeholder":"Describe your project here..."} -->' . "\n" .
                        '<p></p>' . "\n" .
                        '<!-- /wp:paragraph -->'
        ),
        'events' => array(
            'title' => 'New Event',
            'content' => '<!-- wp:acf/event-details {"data":{"event_title":"","event_date":"' . date('Y-m-d', strtotime('+1 week')) . '","event_time":"18:00","location":"","registration_link":"","recurring_event":false}} /-->' . "\n\n" . 
                        '<!-- wp:paragraph {"placeholder":"Describe your event here..."} -->' . "\n" .
                        '<p></p>' . "\n" .
                        '<!-- /wp:paragraph -->'
        ),
        'awards' => array(
            'title' => 'New Award Recognition',
            'content' => '<!-- wp:acf/award-information {"data":{"award_name":"","awarding_organization":"","date_received":"' . date('Y-m-d') . '"}} /-->' . "\n\n" . 
                        '<!-- wp:paragraph {"placeholder":"Describe the significance of this award..."} -->' . "\n" .
                        '<p></p>' . "\n" .
                        '<!-- /wp:paragraph -->'
        )
    );
}

/**
 * Get required fields by category for validation
 */
function ricelipka_get_required_fields_by_category() {
    return array(
        'news' => array('news_title', 'publication_date', 'excerpt'),
        'projects' => array('project_name', 'completion_status', 'project_type', 'client', 'location'),
        'events' => array('event_title', 'event_date', 'event_time', 'location'),
        'awards' => array('award_name', 'awarding_organization', 'date_received')
    );
}

/**
 * Get field relationships for cross-validation
 */
function ricelipka_get_field_relationships() {
    return array(
        'awards' => array(
            'associated_project' => array(
                'type' => 'post_object',
                'post_type' => 'post',
                'category' => 'projects'
            )
        )
    );
}

/**
 * Get blocks that support drag-and-drop reordering
 */
function ricelipka_get_draggable_blocks() {
    return array(
        'acf/news-article',
        'acf/project-portfolio', 
        'acf/event-details',
        'acf/award-information',
        'core/paragraph',
        'core/heading',
        'core/image',
        'core/gallery',
        'core/list',
        'core/quote',
        'core/separator',
        'core/spacer',
        'core/columns',
        'core/group'
    );
}

/**
 * Get drag-and-drop restrictions
 */
function ricelipka_get_drag_restrictions() {
    return array(
        'acf_blocks_first' => true, // ACF blocks should generally come first
        'maintain_structure' => true, // Maintain logical content structure
        'category_specific' => array(
            'news' => array('acf/news-article'),
            'projects' => array('acf/project-portfolio'),
            'events' => array('acf/event-details'),
            'awards' => array('acf/award-information')
        )
    );
}

/**
 * Add template insertion buttons to block editor
 */
function ricelipka_add_template_inserter() {
    add_action('admin_footer', function() {
        if (get_current_screen()->id !== 'post') {
            return;
        }
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add template insertion functionality
            if (typeof wp !== 'undefined' && wp.data && wp.blocks) {
                const { select, dispatch } = wp.data;
                const { createBlock } = wp.blocks;
                
                // Add template buttons to block inserter
                wp.hooks.addFilter(
                    'blocks.registerBlockType',
                    'ricelipka/add-template-support',
                    function(settings, name) {
                        if (name.startsWith('acf/')) {
                            settings.supports = settings.supports || {};
                            settings.supports.reusable = false; // Prevent reusable block creation
                            settings.supports.inserter = true;
                        }
                        return settings;
                    }
                );
            }
        });
        </script>
        <?php
    });
}
add_action('admin_init', 'ricelipka_add_template_inserter');

/**
 * Enhanced block editor settings for drag-and-drop
 */
function ricelipka_enhanced_block_editor_settings($settings, $context) {
    if (isset($context->post) && $context->post->post_type === 'post') {
        // Get post category
        $categories = get_the_category($context->post->ID);
        $primary_category = null;
        
        $primary_cats = array('news', 'projects', 'events', 'awards');
        foreach ($categories as $category) {
            if (in_array($category->slug, $primary_cats)) {
                $primary_category = $category->slug;
                break;
            }
        }
        
        if ($primary_category) {
            // Enable enhanced drag-and-drop features
            $settings['supportsLayout'] = true;
            $settings['__experimentalBlockPatterns'] = true;
            $settings['__experimentalCanUserUseUnfilteredHTML'] = false;
            
            // Add template support
            $settings['__experimentalBlockPatternCategories'] = array_merge(
                $settings['__experimentalBlockPatternCategories'] ?? array(),
                array(
                    array(
                        'name' => 'ricelipka-templates',
                        'label' => __('Content Templates', 'ricelipka-theme'),
                    )
                )
            );
            
            // Add validation settings
            $settings['__experimentalFieldValidation'] = true;
            $settings['__experimentalDragAndDrop'] = array(
                'enabled' => true,
                'restrictions' => ricelipka_get_drag_restrictions(),
                'validation' => ricelipka_get_required_fields_by_category()
            );
        }
    }
    
    return $settings;
}
add_filter('block_editor_settings_all', 'ricelipka_enhanced_block_editor_settings', 10, 2);