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
    
    // Add support for block editor styles
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
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
    
    // Enqueue block templates CSS
    wp_enqueue_style(
        'ricelipka-block-templates',
        get_template_directory_uri() . '/assets/css/block-templates.css',
        array('ricelipka-responsive-layouts'),
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
    
    // Enqueue block templates JavaScript
    wp_enqueue_script(
        'ricelipka-block-templates',
        get_template_directory_uri() . '/assets/js/block-templates.js',
        array('jquery', 'ricelipka-theme-script'),
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
    
    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
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
 * Category detection helper function
 */
function ricelipka_get_post_primary_category($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    $primary_cats = array('news', 'projects', 'events', 'awards');
    
    foreach ($categories as $category) {
        if (in_array($category->slug, $primary_cats)) {
            return $category->slug;
        }
    }
    
    return 'news'; // Default fallback
}

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
require_once get_template_directory() . '/inc/acf-blocks.php';
require_once get_template_directory() . '/inc/category-fields.php';
require_once get_template_directory() . '/inc/category-navigation-widget.php';
require_once get_template_directory() . '/inc/performance.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/acf-help-system.php';
require_once get_template_directory() . '/inc/acf-field-validation.php';
require_once get_template_directory() . '/inc/acf-help-documentation.php';
require_once get_template_directory() . '/inc/block-templates.php';
require_once get_template_directory() . '/inc/pattern-registration.php';
require_once get_template_directory() . '/inc/chronological-ordering.php';

// Include block patterns
require_once get_template_directory() . '/patterns/news-layouts.php';
require_once get_template_directory() . '/patterns/project-layouts.php';
require_once get_template_directory() . '/patterns/event-layouts.php';
require_once get_template_directory() . '/patterns/award-layouts.php';

/**
 * Theme activation hook
 */
function ricelipka_theme_activation() {
    // Create default categories if they don't exist
    $categories = array(
        'news' => 'News',
        'projects' => 'Projects', 
        'events' => 'Events',
        'awards' => 'Awards'
    );
    
    foreach ($categories as $slug => $name) {
        if (!term_exists($slug, 'category')) {
            wp_insert_term($name, 'category', array('slug' => $slug));
        }
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'ricelipka_theme_activation');