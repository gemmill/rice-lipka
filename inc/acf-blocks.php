<?php
/**
 * ACF Blocks functionality
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register ACF blocks
 */
function ricelipka_register_acf_blocks() {
    // Check if ACF Pro is active
    if (!function_exists('acf_register_block_type')) {
        return;
    }

    // Register custom image sizes for blocks
    add_image_size('news-featured', 800, 450, true);
    add_image_size('project-thumbnail', 400, 300, true);
    add_image_size('project-large', 1200, 800, true);
    add_image_size('project-gallery', 600, 400, true);
    add_image_size('event-banner', 1200, 300, true);
    add_image_size('award-certificate', 400, 300, true);

    // News Article Block
    acf_register_block_type(array(
        'name'              => 'news-article',
        'title'             => __('News Article', 'ricelipka-theme'),
        'description'       => __('Create engaging news articles with structured content and media. Includes help tooltips and field guidance for easy content creation.', 'ricelipka-theme'),
        'render_template'   => 'blocks/news-article/block.php',
        'category'          => 'ricelipka-blocks',
        'icon'              => 'admin-post',
        'keywords'          => array('news', 'article', 'post', 'announcement'),
        'supports'          => array(
            'align' => array('wide', 'full'),
            'mode' => false,
            'jsx' => true,
            'anchor' => true,
            'customClassName' => true,
        ),
        'example'           => array(
            'attributes' => array(
                'mode' => 'preview',
                'data' => array(
                    'news_title' => 'Rice+Lipka Architects Wins Prestigious Design Award',
                    'publication_date' => date('Y-m-d'),
                    'excerpt' => 'We are thrilled to announce that our latest civic architecture project has been recognized with the Excellence in Design Award from the American Institute of Architects.',
                    'subcategory' => 'award_notifications',
                    'is_preview' => true
                )
            )
        ),
        'enqueue_style'     => get_template_directory_uri() . '/blocks/news-article/style.css',
        'enqueue_script'    => get_template_directory_uri() . '/blocks/news-article/script.js',
    ));

    // Project Portfolio Block
    acf_register_block_type(array(
        'name'              => 'project-portfolio',
        'title'             => __('Project Portfolio', 'ricelipka-theme'),
        'description'       => __('Showcase architectural projects with detailed information and image galleries. Includes contextual help for project metadata and completion tracking.', 'ricelipka-theme'),
        'render_template'   => 'blocks/project-portfolio/block.php',
        'category'          => 'ricelipka-blocks',
        'icon'              => 'portfolio',
        'keywords'          => array('project', 'portfolio', 'architecture', 'gallery', 'lightbox'),
        'supports'          => array(
            'align' => array('wide', 'full'),
            'mode' => false,
            'jsx' => true,
            'anchor' => true,
            'customClassName' => true,
        ),
        'example'           => array(
            'attributes' => array(
                'mode' => 'preview',
                'data' => array(
                    'project_name' => 'Sample Architectural Project',
                    'completion_status' => 'completed',
                    'completion_percentage' => 100,
                    'project_type' => 'civic',
                    'client' => 'Sample Client Organization',
                    'location' => 'Sample City, State',
                    'is_preview' => true
                )
            )
        ),
        'enqueue_style'     => get_template_directory_uri() . '/blocks/project-portfolio/style.css',
        'enqueue_script'    => get_template_directory_uri() . '/blocks/project-portfolio/script.js',
    ));

    // Event Details Block
    acf_register_block_type(array(
        'name'              => 'event-details',
        'title'             => __('Event Details', 'ricelipka-theme'),
        'description'       => __('Create comprehensive event listings with dates, locations, and registration information. Includes guided setup for calendar integration and countdown timers.', 'ricelipka-theme'),
        'render_template'   => 'blocks/event-details/block.php',
        'category'          => 'ricelipka-blocks',
        'icon'              => 'calendar-alt',
        'keywords'          => array('event', 'calendar', 'date', 'countdown', 'registration', 'location'),
        'supports'          => array(
            'align' => array('wide', 'full'),
            'mode' => false,
            'jsx' => true,
            'anchor' => true,
            'customClassName' => true,
        ),
        'example'           => array(
            'attributes' => array(
                'mode' => 'preview',
                'data' => array(
                    'event_title' => 'Rice+Lipka Architects Open House',
                    'event_date' => date('Y-m-d', strtotime('+1 week')),
                    'event_time' => '18:00',
                    'location' => 'Rice+Lipka Architects Studio, Downtown',
                    'registration_link' => 'https://example.com/register',
                    'recurring_event' => false,
                    'is_preview' => true
                )
            )
        ),
        'enqueue_style'     => get_template_directory_uri() . '/blocks/event-details/style.css',
        'enqueue_script'    => get_template_directory_uri() . '/blocks/event-details/script.js',
    ));

    // Award Information Block
    acf_register_block_type(array(
        'name'              => 'award-information',
        'title'             => __('Award Information', 'ricelipka-theme'),
        'description'       => __('Document awards and recognition with detailed information and project associations. Includes help for timeline visualization and cross-referencing with projects.', 'ricelipka-theme'),
        'render_template'   => 'blocks/award-information/block.php',
        'category'          => 'ricelipka-blocks',
        'icon'              => 'awards',
        'keywords'          => array('award', 'recognition', 'achievement', 'certificate', 'timeline'),
        'supports'          => array(
            'align' => array('wide', 'full'),
            'mode' => false,
            'jsx' => true,
            'anchor' => true,
            'customClassName' => true,
        ),
        'example'           => array(
            'attributes' => array(
                'mode' => 'preview',
                'data' => array(
                    'award_name' => 'Excellence in Civic Architecture Award',
                    'awarding_organization' => 'American Institute of Architects',
                    'date_received' => date('Y-m-d', strtotime('-3 months')),
                    'is_preview' => true
                )
            )
        ),
        'enqueue_style'     => get_template_directory_uri() . '/blocks/award-information/style.css',
        'enqueue_script'    => get_template_directory_uri() . '/blocks/award-information/script.js',
    ));
}
add_action('acf/init', 'ricelipka_register_acf_blocks');

/**
 * Add custom block category
 */
function ricelipka_block_categories($categories, $post) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'ricelipka-blocks',
                'title' => __('Rice+Lipka Blocks', 'ricelipka-theme'),
                'icon'  => 'building',
            ),
        )
    );
}
add_filter('block_categories_all', 'ricelipka_block_categories', 10, 2);

/**
 * Enqueue block editor assets
 */
function ricelipka_block_editor_assets() {
    wp_enqueue_script(
        'ricelipka-block-editor',
        get_template_directory_uri() . '/assets/js/block-editor.js',
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-data'),
        wp_get_theme()->get('Version'),
        true
    );

    wp_enqueue_style(
        'ricelipka-block-editor',
        get_template_directory_uri() . '/assets/css/block-editor.css',
        array('wp-edit-blocks'),
        wp_get_theme()->get('Version')
    );

    // Localize script with theme data
    wp_localize_script('ricelipka-block-editor', 'riceLipkaBlocks', array(
        'themeUrl' => get_template_directory_uri(),
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ricelipka_blocks_nonce'),
        'strings' => array(
            'previewMode' => __('Preview Mode', 'ricelipka-theme'),
            'editMode' => __('Edit Mode', 'ricelipka-theme'),
            'validationError' => __('Please fill in all required fields', 'ricelipka-theme'),
            'contentTooShort' => __('Content appears to be very short', 'ricelipka-theme'),
            'contentTooLong' => __('Content is quite long - consider breaking into sections', 'ricelipka-theme'),
        )
    ));
}
add_action('enqueue_block_editor_assets', 'ricelipka_block_editor_assets');

/**
 * Filter blocks based on post category
 */
function ricelipka_filter_blocks_by_category($allowed_blocks, $editor_context = null) {
    // Handle different WordPress versions and parameter variations
    $post = null;
    
    if ($editor_context && is_object($editor_context)) {
        // WordPress 5.8+ passes WP_Block_Editor_Context
        if (isset($editor_context->post) && is_object($editor_context->post)) {
            $post = $editor_context->post;
        }
    } else {
        // Fallback to global post
        global $post;
    }
    
    if (!$post || !is_object($post) || !isset($post->ID)) {
        return $allowed_blocks;
    }

    // Get post categories
    $categories = get_the_category($post->ID);
    if (!$categories || !is_array($categories)) {
        return $allowed_blocks;
    }
    
    $primary_category = null;
    
    $primary_cats = array('news', 'projects', 'events', 'awards');
    foreach ($categories as $category) {
        if (is_object($category) && in_array($category->slug, $primary_cats)) {
            $primary_category = $category->slug;
            break;
        }
    }

    // If no primary category found, allow all blocks
    if (!$primary_category) {
        return $allowed_blocks;
    }

    // Define category-specific blocks
    $category_blocks = array(
        'news' => array('acf/news-article'),
        'projects' => array('acf/project-portfolio'),
        'events' => array('acf/event-details'),
        'awards' => array('acf/award-information'),
    );

    // Get allowed blocks for this category
    $category_allowed_blocks = isset($category_blocks[$primary_category]) 
        ? $category_blocks[$primary_category] 
        : array();

    // Always allow core blocks
    $core_blocks = array(
        'core/paragraph',
        'core/heading',
        'core/image',
        'core/gallery',
        'core/list',
        'core/quote',
        'core/separator',
        'core/spacer',
        'core/columns',
        'core/group',
    );

    return array_merge($core_blocks, $category_allowed_blocks);
}

// Hook with compatibility for different WordPress versions
if (version_compare(get_bloginfo('version'), '5.8', '>=')) {
    add_filter('allowed_block_types_all', 'ricelipka_filter_blocks_by_category', 10, 2);
} else {
    add_filter('allowed_block_types', 'ricelipka_filter_blocks_by_category', 10, 2);
}

/**
 * Handle AJAX requests for block preview updates
 */
function ricelipka_handle_block_preview_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'ricelipka_blocks_nonce')) {
        wp_die('Security check failed');
    }

    $block_type = sanitize_text_field($_POST['block_type']);
    $block_data = $_POST['block_data'];

    // Process the block data and return updated preview
    $response = array(
        'success' => true,
        'preview_html' => '',
        'validation' => array(
            'isValid' => true,
            'errors' => array(),
            'warnings' => array()
        )
    );

    // Validate based on block type
    if ($block_type === 'news-article') {
        $validation = ricelipka_validate_news_block($block_data);
        $response['validation'] = $validation;
    }

    wp_send_json($response);
}
add_action('wp_ajax_ricelipka_block_preview', 'ricelipka_handle_block_preview_ajax');
add_action('wp_ajax_nopriv_ricelipka_block_preview', 'ricelipka_handle_block_preview_ajax');

/**
 * Validate news block data
 */
function ricelipka_validate_news_block($data) {
    $validation = array(
        'isValid' => true,
        'errors' => array(),
        'warnings' => array()
    );

    // Check required fields
    if (empty($data['news_title']) && empty($data['post_title'])) {
        $validation['isValid'] = false;
        $validation['errors'][] = 'Title is required';
    }

    // Check content length
    $content = $data['post_content'] ?? '';
    $word_count = str_word_count(strip_tags($content));
    
    if ($word_count < 50 && !empty($content)) {
        $validation['warnings'][] = 'Content appears to be very short';
    } elseif ($word_count > 2000) {
        $validation['warnings'][] = 'Content is quite long - consider breaking into sections';
    }

    return $validation;
}

/**
 * Add custom image sizes to media library
 */
function ricelipka_add_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'news-featured' => __('News Featured (800x450)', 'ricelipka-theme'),
        'project-thumbnail' => __('Project Thumbnail (400x300)', 'ricelipka-theme'),
        'project-large' => __('Project Large (1200x800)', 'ricelipka-theme'),
        'project-gallery' => __('Project Gallery (600x400)', 'ricelipka-theme'),
        'event-banner' => __('Event Banner (1200x300)', 'ricelipka-theme'),
        'award-certificate' => __('Award Certificate (400x300)', 'ricelipka-theme'),
    ));
}
add_filter('image_size_names_choose', 'ricelipka_add_custom_image_sizes');