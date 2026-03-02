<?php
/**
 * Rice+Lipka Architects Theme Functions
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup and configuration
 */
function ricelipka_theme_setup() {
    // Add theme support for various WordPress features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    // Add support for classic editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'ricelipka-theme'),
        'footer' => __('Footer Menu', 'ricelipka-theme'),
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'ricelipka_theme_setup');

/**
 * Remove excerpt metabox from post editor
 */
function ricelipka_remove_excerpt_metabox() {
    remove_meta_box('postexcerpt', 'post', 'normal');
    remove_meta_box('postexcerpt', 'news', 'normal');
    remove_meta_box('postexcerpt', 'projects', 'normal');
    remove_meta_box('postexcerpt', 'awards', 'normal');
    remove_meta_box('postexcerpt', 'people', 'normal');
}
add_action('admin_menu', 'ricelipka_remove_excerpt_metabox');

/**
 * Enqueue scripts and styles
 */
function ricelipka_theme_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style(
        'ricelipka-theme-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue responsive layouts CSS
    wp_enqueue_style(
        'ricelipka-responsive-layouts',
        get_template_directory_uri() . '/assets/css/responsive-layouts.css',
        array('ricelipka-theme-style'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue help documentation CSS
    wp_enqueue_style(
        'ricelipka-help-documentation',
        get_template_directory_uri() . '/assets/css/help-documentation.css',
        array('ricelipka-responsive-layouts'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue ACF help CSS
    wp_enqueue_style(
        'ricelipka-acf-help',
        get_template_directory_uri() . '/assets/css/acf-help.css',
        array('ricelipka-responsive-layouts'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue performance optimization CSS
    wp_enqueue_style(
        'ricelipka-performance-optimization',
        get_template_directory_uri() . '/assets/css/performance-optimization.css',
        array('ricelipka-responsive-layouts'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue chronological ordering CSS
    wp_enqueue_style(
        'ricelipka-chronological-ordering',
        get_template_directory_uri() . '/assets/css/chronological-ordering.css',
        array('ricelipka-responsive-layouts'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue custom CSS (if exists)
    if (file_exists(get_template_directory() . '/assets/css/custom.css')) {
        wp_enqueue_style(
            'ricelipka-theme-custom',
            get_template_directory_uri() . '/assets/css/custom.css',
            array('ricelipka-responsive-layouts'),
            wp_get_theme()->get('Version')
        );
    }
    
    // Enqueue main JavaScript
    wp_enqueue_script(
        'ricelipka-theme-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Enqueue ACF help JavaScript
    wp_enqueue_script(
        'ricelipka-acf-help',
        get_template_directory_uri() . '/assets/js/acf-help.js',
        array('jquery', 'ricelipka-theme-script'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Enqueue responsive enhancements JavaScript
    wp_enqueue_script(
        'ricelipka-responsive-enhancements',
        get_template_directory_uri() . '/assets/js/responsive-enhancements.js',
        array('jquery', 'ricelipka-theme-script'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Enqueue performance optimization JavaScript
    wp_enqueue_script(
        'ricelipka-performance-optimization',
        get_template_directory_uri() . '/assets/js/performance-optimization.js',
        array('jquery', 'ricelipka-theme-script'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Enqueue chronological ordering JavaScript
    wp_enqueue_script(
        'ricelipka-chronological-ordering',
        get_template_directory_uri() . '/assets/js/chronological-ordering.js',
        array('jquery', 'ricelipka-theme-script'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Localize script for AJAX and performance optimization
    wp_localize_script('ricelipka-theme-script', 'ricelipka_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ricelipka_nonce'),
        'template_url' => get_template_directory_uri(),
        'breakpoints' => array(
            'mobile' => 767,
            'tablet' => 1024,
            'desktop' => 1025
        ),
        'performance' => array(
            'lazy_loading' => true,
            'webp_support' => function_exists('imagewebp'),
            'intersection_observer' => true
        )
    ));
}
add_action('wp_enqueue_scripts', 'ricelipka_theme_scripts');

/**
 * Register widget areas
 */
function ricelipka_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'ricelipka-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'ricelipka-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer', 'ricelipka-theme'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'ricelipka-theme'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'ricelipka_theme_widgets_init');

/**
 * Custom excerpt length
 */
function ricelipka_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'ricelipka_excerpt_length');

/**
 * Custom excerpt more text
 */
function ricelipka_excerpt_more($more) {
    return '... <a href="' . get_permalink() . '" class="read-more">more></a>';
}
add_filter('excerpt_more', 'ricelipka_excerpt_more');

/**
 * Include additional theme files
 */
require_once get_template_directory() . '/inc/acf-blocks.php'; // Now contains classic editor functionality
require_once get_template_directory() . '/inc/category-fields.php';
require_once get_template_directory() . '/debug-about-fields.php'; // Debug file - remove in production
require_once get_template_directory() . '/inc/category-navigation-widget.php';
require_once get_template_directory() . '/inc/performance.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/acf-help-system.php';
require_once get_template_directory() . '/inc/acf-field-validation.php';
require_once get_template_directory() . '/inc/acf-help-documentation.php';
require_once get_template_directory() . '/inc/chronological-ordering.php';

// Include debug script for development
if (defined('WP_DEBUG') && WP_DEBUG) {
    require_once get_template_directory() . '/debug-acf-fields.php';
}

/**
 * Restrict posts to only one category selection
 */
function ricelipka_restrict_single_category() {
    // This will be replaced with custom post types
    // Keeping minimal functionality for now
}
add_action('init', 'ricelipka_restrict_single_category');

/**
 * Register custom post types for content organization
 */
function ricelipka_register_custom_post_types() {
    // News Post Type
    register_post_type('news', array(
        'labels' => array(
            'name' => 'News',
            'singular_name' => 'News Article',
            'add_new' => 'Add New Article',
            'add_new_item' => 'Add New News Article',
            'edit_item' => 'Edit News Article',
            'new_item' => 'New News Article',
            'view_item' => 'View News Article',
            'search_items' => 'Search News',
            'not_found' => 'No news articles found',
            'not_found_in_trash' => 'No news articles found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
        'rewrite' => array('slug' => 'news'),
        'show_in_rest' => true
    ));

    // Projects Post Type
    register_post_type('projects', array(
        'labels' => array(
            'name' => 'Projects',
            'singular_name' => 'Project',
            'add_new' => 'Add New Project',
            'add_new_item' => 'Add New Project',
            'edit_item' => 'Edit Project',
            'new_item' => 'New Project',
            'view_item' => 'View Project',
            'search_items' => 'Search Projects',
            'not_found' => 'No projects found',
            'not_found_in_trash' => 'No projects found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
        'rewrite' => array('slug' => 'projects'),
        'show_in_rest' => true
    ));

    // Awards Post Type
    register_post_type('awards', array(
        'labels' => array(
            'name' => 'Awards',
            'singular_name' => 'Award',
            'add_new' => 'Add New Award',
            'add_new_item' => 'Add New Award',
            'edit_item' => 'Edit Award',
            'new_item' => 'New Award',
            'view_item' => 'View Award',
            'search_items' => 'Search Awards',
            'not_found' => 'No awards found',
            'not_found_in_trash' => 'No awards found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-awards',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
        'rewrite' => array('slug' => 'awards'),
        'show_in_rest' => true
    ));

    // People Post Type
    register_post_type('people', array(
        'labels' => array(
            'name' => 'People',
            'singular_name' => 'Person',
            'add_new' => 'Add New Person',
            'add_new_item' => 'Add New Person',
            'edit_item' => 'Edit Person',
            'new_item' => 'New Person',
            'view_item' => 'View Person',
            'search_items' => 'Search People',
            'not_found' => 'No people found',
            'not_found_in_trash' => 'No people found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
        'rewrite' => array('slug' => 'people'),
        'show_in_rest' => true
    ));
}
add_action('init', 'ricelipka_register_custom_post_types');

/**
 * Disable comments on all posts and custom post types
 */
function ricelipka_disable_comments() {
    // Disable comments for all post types
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);
    
    // Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);
    
    // Remove comments page in admin
    add_action('admin_init', function() {
        // Remove comments metabox from dashboard
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        
        // Remove comments menu
        remove_menu_page('edit-comments.php');
        
        // Remove comments from admin bar
        add_action('wp_before_admin_bar_render', function() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
        });
    });
    
    // Remove comment-related widgets
    add_action('widgets_init', function() {
        unregister_widget('WP_Widget_Recent_Comments');
    });
    
    // Remove comment feed links
    remove_action('wp_head', 'feed_links_extra', 3);
    
    // Remove comment reply script
    add_action('wp_print_scripts', function() {
        wp_deregister_script('comment-reply');
    });
}
add_action('init', 'ricelipka_disable_comments');




/**
 * Theme activation hook
 */
function ricelipka_theme_activation() {
    // Register custom post types
    ricelipka_register_custom_post_types();
    
    // Flush rewrite rules to ensure custom post type URLs work
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'ricelipka_theme_activation');