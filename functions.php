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
    remove_meta_box('postexcerpt', 'projects', 'normal');
    remove_meta_box('postexcerpt', 'awards', 'normal');
    remove_meta_box('postexcerpt', 'people', 'normal');
}
add_action('admin_menu', 'ricelipka_remove_excerpt_metabox');

/**
 * Customize TinyMCE editor to only show basic text formatting and links
 */
function ricelipka_customize_tinymce($init) {
    // Define minimal toolbar with only text formatting and links
    $init['toolbar1'] = 'bold,italic,underline,strikethrough,|,bullist,numlist,|,link,unlink,|,undo,redo';
    $init['toolbar2'] = '';
    $init['toolbar3'] = '';
    $init['toolbar4'] = '';
    
    // Remove plugins that add unwanted functionality
    $init['plugins'] = 'lists,link,paste,textcolor';
    
    // Disable media buttons
    $init['media_buttons'] = false;
    
    // Remove color picker and other advanced options
    $init['textcolor_map'] = '';
    $init['textcolor_rows'] = 0;
    
    // Disable drag and drop
    $init['paste_data_images'] = false;
    $init['paste_remove_styles'] = true;
    $init['paste_remove_spans'] = true;
    $init['paste_strip_class_attributes'] = 'all';
    
    // Remove format dropdown
    $init['block_formats'] = 'Paragraph=p';
    
    // Disable resize
    $init['resize'] = false;
    
    // Remove statusbar
    $init['statusbar'] = false;
    
    // Remove menubar
    $init['menubar'] = false;
    
    return $init;
}
add_filter('tiny_mce_before_init', 'ricelipka_customize_tinymce');

/**
 * Remove media buttons from post editor
 */
function ricelipka_remove_media_buttons() {
    remove_action('media_buttons', 'media_buttons');
}
add_action('admin_head', 'ricelipka_remove_media_buttons');

/**
 * Remove additional editor buttons and features
 */
function ricelipka_remove_editor_buttons($buttons) {
    // Remove buttons we don't want
    $remove_buttons = array(
        'formatselect',
        'forecolor',
        'backcolor',
        'indent',
        'outdent',
        'alignleft',
        'aligncenter',
        'alignright',
        'alignjustify',
        'wp_more',
        'wp_page',
        'spellchecker',
        'fullscreen',
        'wp_adv',
        'wp_help'
    );
    
    return array_diff($buttons, $remove_buttons);
}
add_filter('mce_buttons', 'ricelipka_remove_editor_buttons');
add_filter('mce_buttons_2', 'ricelipka_remove_editor_buttons');
add_filter('mce_buttons_3', 'ricelipka_remove_editor_buttons');
add_filter('mce_buttons_4', 'ricelipka_remove_editor_buttons');

/**
 * Remove TinyMCE plugins we don't want
 */
function ricelipka_remove_tinymce_plugins($plugins) {
    $remove_plugins = array(
        'colorpicker',
        'textcolor',
        'image',
        'media',
        'wordpress',
        'wpgallery',
        'wplink',
        'wpdialogs',
        'wpfullscreen',
        'wpview'
    );
    
    return array_diff_key($plugins, array_flip($remove_plugins));
}
add_filter('tiny_mce_plugins', 'ricelipka_remove_tinymce_plugins');

/**
 * Add admin CSS to hide media-related elements
 */
function ricelipka_admin_css() {
    echo '<style>
        /* Hide media buttons */
        #wp-content-media-buttons,
        .wp-media-buttons,
        .insert-media,
        .add_media {
            display: none !important;
        }
        
        /* Hide drag and drop area */
        .uploader-inline,
        .drag-drop-area {
            display: none !important;
        }
        
        /* Hide format dropdown if it appears */
        .mce-listbox.mce-first,
        .mce-colorbutton,
        .mce-splitbtn {
            display: none !important;
        }
        
        /* Simplify editor appearance */
        .mce-toolbar-grp {
            border-bottom: 1px solid #ddd;
        }
        
        /* Hide visual/text tabs if needed */
        .wp-editor-tabs {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', 'ricelipka_admin_css');

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
require_once get_template_directory() . '/inc/category-navigation-widget.php';
require_once get_template_directory() . '/inc/performance.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/acf-help-system.php';
require_once get_template_directory() . '/inc/acf-field-validation.php';
require_once get_template_directory() . '/inc/acf-help-documentation.php';
require_once get_template_directory() . '/inc/chronological-ordering.php';

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
        'rewrite' => array('slug' => 'work'),
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
        'publicly_queryable' => true, // Enable archive queries
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
        'publicly_queryable' => true, // Enable archive queries
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
 * Add custom rewrite rules for project type filtering
 */
function ricelipka_add_project_type_rewrite_rules() {
    // Get valid project types for more specific matching
    $valid_types = array(
        'cultural',
        'academic', 
        'offices',
        'retail_commercial',
        'institutional',
        'planning',
        'exhibitions',
        'research_installation',
        'residential'
    );
    
    // Create a regex pattern for valid project types only
    $types_pattern = '(' . implode('|', $valid_types) . ')';
    
    // Add rewrite rule for /work/{project_type}/ (only valid types)
    add_rewrite_rule(
        '^work/' . $types_pattern . '/?$',
        'index.php?post_type=projects&project_type_filter=$matches[1]',
        'top'
    );
    
    // Add rewrite rule for /work/{project_type}/page/{page_num}/
    add_rewrite_rule(
        '^work/' . $types_pattern . '/page/([0-9]+)/?$',
        'index.php?post_type=projects&project_type_filter=$matches[1]&paged=$matches[2]',
        'top'
    );
    
    // Individual project URLs will use the default WordPress rewrite:
    // /work/{project-slug}/ -> handled by WordPress automatically
}
add_action('init', 'ricelipka_add_project_type_rewrite_rules');

/**
 * Add custom rewrite rules for news archive (regular posts)
 */
function ricelipka_add_news_rewrite_rules() {
    // Add rewrite rule for /news/ -> show all posts
    add_rewrite_rule(
        '^news/?$',
        'index.php?post_type=post&news_archive=1',
        'top'
    );
    
    // Add rewrite rule for /news/page/{page_num}/
    add_rewrite_rule(
        '^news/page/([0-9]+)/?$',
        'index.php?post_type=post&news_archive=1&paged=$matches[1]',
        'top'
    );
}
add_action('init', 'ricelipka_add_news_rewrite_rules');
add_action('init', 'ricelipka_add_project_type_rewrite_rules');

/**
 * Add custom query vars for project filtering
 */
function ricelipka_add_project_query_vars($vars) {
    $vars[] = 'project_type_filter';
    $vars[] = 'news_archive';
    return $vars;
}
add_filter('query_vars', 'ricelipka_add_project_query_vars');

/**
 * Modify the main query for project type filtering and news archive
 */
function ricelipka_modify_projects_query($query) {
    // Only modify the main query on the frontend for projects archive
    if (!is_admin() && $query->is_main_query()) {
        $project_type = get_query_var('project_type_filter');
        $news_archive = get_query_var('news_archive');
        
        // Handle projects archive
        if ($query->get('post_type') === 'projects') {
            // Set posts per page for projects
            $query->set('posts_per_page', 15);
            
            if ($project_type) {
                // Validate that the project type exists
                $valid_types = array(
                    'cultural',
                    'academic', 
                    'offices',
                    'retail_commercial',
                    'institutional',
                    'planning',
                    'exhibitions',
                    'research_installation',
                    'residential'
                );
                
                if (in_array($project_type, $valid_types)) {
                    // Add meta query to filter by project type
                    $meta_query = array(
                        array(
                            'key' => 'project_type',
                            'value' => $project_type,
                            'compare' => '='
                        )
                    );
                    
                    $query->set('meta_query', $meta_query);
                } else {
                    // Invalid project type, show 404
                    $query->set_404();
                }
            }
        }
        
        // Handle news archive (regular posts)
        if ($news_archive && $query->get('post_type') === 'post') {
            $query->set('posts_per_page', 12);
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
        }
    }
}
add_action('pre_get_posts', 'ricelipka_modify_projects_query');

/**
 * Template redirect for news archive
 */
function ricelipka_news_archive_template($template) {
    if (get_query_var('news_archive')) {
        $news_template = locate_template('news-archive.php');
        if ($news_template) {
            return $news_template;
        }
    }
    return $template;
}
add_filter('template_include', 'ricelipka_news_archive_template');

/**
 * Create custom navigation menu structure
 */
function ricelipka_create_custom_menu() {
    $menu_items = array(
        'work' => array(
            'title' => 'Work',
            'url' => home_url('/work/'),
            'submenu' => array(
                'cultural' => array(
                    'title' => 'Cultural',
                    'url' => home_url('/work/cultural/')
                ),
                'academic' => array(
                    'title' => 'Academic',
                    'url' => home_url('/work/academic/')
                ),
                'offices' => array(
                    'title' => 'Offices',
                    'url' => home_url('/work/offices/')
                ),
                'retail_commercial' => array(
                    'title' => 'Retail & Commercial',
                    'url' => home_url('/work/retail_commercial/')
                ),
                'institutional' => array(
                    'title' => 'Institutional',
                    'url' => home_url('/work/institutional/')
                ),
                'planning' => array(
                    'title' => 'Planning',
                    'url' => home_url('/work/planning/')
                ),
                'exhibitions' => array(
                    'title' => 'Exhibitions',
                    'url' => home_url('/work/exhibitions/')
                ),
                'research_installation' => array(
                    'title' => 'Research & Installation',
                    'url' => home_url('/work/research_installation/')
                ),
                'residential' => array(
                    'title' => 'Residential',
                    'url' => home_url('/work/residential/')
                ),
                'archive' => array(
                    'title' => 'Archive',
                    'url' => home_url('/work/')
                )
            )
        ),
        'news' => array(
            'title' => 'News',
            'url' => home_url('/news/')
        ),
        'about' => array(
            'title' => 'About',
            'url' => home_url('/about/'),
            'submenu' => array(
                'awards' => array(
                    'title' => 'Awards',
                    'url' => home_url('/awards/')
                ),
                'publications' => array(
                    'title' => 'Publications',
                    'url' => home_url('/publications/')
                ),
                'lectures' => array(
                    'title' => 'Lectures',
                    'url' => home_url('/lectures/')
                ),
                'exhibitions' => array(
                    'title' => 'Exhibitions',
                    'url' => home_url('/exhibitions/')
                ),
                'people' => array(
                    'title' => 'People',
                    'url' => home_url('/people/')
                )
            )
        ),
        'contact' => array(
            'title' => 'Contact',
            'url' => home_url('/contact/')
        )
    );
    
    return $menu_items;
}

/**
 * Display custom navigation menu with enhanced nested menu support
 */
function ricelipka_display_custom_menu() {
    $menu_items = ricelipka_create_custom_menu();
    $current_url = home_url($_SERVER['REQUEST_URI']);
    
    // Remove trailing slash for consistent comparison
    $current_url = rtrim($current_url, '/');
    
    echo '<ul class="primary-menu">';
    
    foreach ($menu_items as $key => $item) {
        $active_class = '';
        $ancestor_class = '';
        $has_submenu = isset($item['submenu']) && !empty($item['submenu']);
        
        // Clean item URL for comparison
        $item_url = rtrim($item['url'], '/');
        
        // Check if current page matches this menu item exactly
        if ($current_url === $item_url) {
            $active_class = ' current-menu-item';
        }
        // Check if current page is under this menu section (ancestor)
        elseif ($has_submenu && strpos($current_url, $item_url) === 0) {
            $ancestor_class = ' current-menu-ancestor';
        }
        
        echo '<li class="menu-item' . $active_class . $ancestor_class . ($has_submenu ? ' has-submenu' : '') . '">';
        echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>';
        
        if ($has_submenu) {
            echo '<ul class="submenu">';
            foreach ($item['submenu'] as $sub_key => $sub_item) {
                $sub_active_class = '';
                $sub_item_url = rtrim($sub_item['url'], '/');
                
                // Check for exact match or if current URL starts with submenu URL
                if ($current_url === $sub_item_url || strpos($current_url, $sub_item_url . '/') === 0) {
                    $sub_active_class = ' current-menu-item';
                }
                
                echo '<li class="submenu-item' . $sub_active_class . '">';
                echo '<a href="' . esc_url($sub_item['url']) . '">' . esc_html($sub_item['title']) . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo '</li>';
    }
    
    echo '</ul>';
}

/**
 * Theme activation hook
 */
function ricelipka_theme_activation() {
    // Register custom post types
    ricelipka_register_custom_post_types();
    
    // Add rewrite rules
    ricelipka_add_project_type_rewrite_rules();
    ricelipka_add_news_rewrite_rules();
    
    // Flush rewrite rules to ensure custom post type URLs work
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'ricelipka_theme_activation');

/**
 * Add body classes for better styling control
 */
function ricelipka_custom_body_classes($classes) {
    // Add class for front page
    if (is_front_page()) {
        $classes[] = 'home';
    }
    
    // Add class for specific post types
    if (is_singular()) {
        $post_type = get_post_type();
        $classes[] = 'single-' . $post_type;
    }
    
    return $classes;
}
add_filter('body_class', 'ricelipka_custom_body_classes');

/**
 * Redirect single people and awards pages to their archives
 */
function ricelipka_redirect_single_pages() {
    if (is_singular(array('people', 'awards'))) {
        $post_type = get_post_type();
        $archive_url = get_post_type_archive_link($post_type);
        
        if ($archive_url) {
            wp_redirect($archive_url, 301);
            exit;
        }
    }
}
add_action('template_redirect', 'ricelipka_redirect_single_pages');

/**
 * Convert project type to camelCase for CSS classes
 */
function ricelipka_project_type_to_camel_case($project_type) {
    if (empty($project_type)) {
        return '';
    }
    
    // Convert snake_case to camelCase (e.g., retail_commercial -> retailCommercial)
    return lcfirst(str_replace('_', '', ucwords($project_type, '_')));
}
/**
 * Get a random color from site settings
 */
function ricelipka_get_random_site_color() {
    // Make sure ACF is available
    if (function_exists('get_field')) {
        $colors_data = get_field('site_colors', 'option');
        
        if ($colors_data && is_array($colors_data)) {
            $colors = array();
            foreach ($colors_data as $color_item) {
                if (isset($color_item['color']) && !empty($color_item['color'])) {
                    $colors[] = $color_item['color'];
                }
            }
            
            if (!empty($colors)) {
                return $colors[array_rand($colors)];
            }
        }
    }
    
    // Fallback colors if no site colors are configured
    $fallback_colors = array('#000000', '#333333', '#666666', '#990000', '#006600', '#000099');
    return $fallback_colors[array_rand($fallback_colors)];
}

/**
 * Add inline CSS with random site color for headings
 */
function ricelipka_add_random_color_css() {
    $random_color = ricelipka_get_random_site_color();
    
    // Debug output (remove in production)
    if (defined('WP_DEBUG') && WP_DEBUG) {
        echo '<!-- Random color selected: ' . $random_color . ' -->';
    }
    
    echo '<style type="text/css">';
    echo 'body h1, body h2, body h3, body h4, body h5, body h6, body .heading { color: ' . esc_attr($random_color) . ' !important; }';
    echo '.menu > ul > li > a { color: ' . esc_attr($random_color) . ' !important; }';
    echo '</style>';
}
add_action('wp_head', 'ricelipka_add_random_color_css');
/**
 * Modify posts per page for awards archive
 */
function ricelipka_modify_awards_query($query) {
    // Only modify the main query on the frontend for awards archive
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('awards')) {
        $query->set('posts_per_page', 96);
    }
}
add_action('pre_get_posts', 'ricelipka_modify_awards_query');