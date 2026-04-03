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
    
    // Enqueue ACF help CSS
    wp_enqueue_style(
        'ricelipka-acf-help',
        get_template_directory_uri() . '/assets/css/acf-help.css',
        array('ricelipka-responsive-layouts'),
        wp_get_theme()->get('Version')
    );
    

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
    
    // Enqueue archive table JavaScript on work archive page
    if (is_page_template('page-work-archive.php') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/work/archive') !== false)) {
        wp_enqueue_script(
            'ricelipka-archive-table',
            get_template_directory_uri() . '/assets/js/archive-table.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
    }
    
    // Enqueue masonry and endless scroll JavaScript on archives that need it
    if (get_query_var('news_archive') || 
        (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/news') !== false) ||
        is_post_type_archive('awards') ||
        is_post_type_archive('projects') ||
        is_page_template('page-about.php') ||
        is_page('about')) { // Add this condition for about page
        
        wp_enqueue_script(
            'ricelipka-masonry',
            get_template_directory_uri() . '/assets/js/masonry.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
        
        // Only enqueue endless scroll for archives, not about page
        if (!is_page_template('page-about.php') && !is_page('about')) {
            wp_enqueue_script(
                'ricelipka-endless-scroll',
                get_template_directory_uri() . '/assets/js/endless-scroll.js',
                array('ricelipka-masonry'),
                wp_get_theme()->get('Version'),
                true
            );
            
            // Pass data to endless scroll script
            global $wp_query;
            wp_localize_script('ricelipka-endless-scroll', 'endlessScrollData', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'currentPage' => get_query_var('paged') ? get_query_var('paged') : 1,
                'maxPages' => $wp_query->max_num_pages,
                'nonce' => wp_create_nonce('ricelipka_nonce')
            ));
        }
    }
    
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
require_once get_template_directory() . '/inc/acf-blocks.php';
require_once get_template_directory() . '/inc/performance.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/acf-help-system.php';

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
 * Add custom rewrite rules for project type filtering and work archive
 */
function ricelipka_add_project_type_rewrite_rules() {
    // Add rewrite rule for /work/archive/
    add_rewrite_rule(
        '^work/archive/?$',
        'index.php?pagename=work-archive',
        'top'
    );
    
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
            $query->set('posts_per_page',1000);
            
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
 * Template redirect for news archive and work archive
 */
function ricelipka_news_archive_template($template) {
    if (get_query_var('news_archive')) {
        $news_template = locate_template('archive-news.php');
        if ($news_template) {
            return $news_template;
        }
    }
    
    // Handle work archive template
    global $wp_query;
    if (isset($wp_query->query_vars['pagename']) && $wp_query->query_vars['pagename'] === 'work-archive') {
        $archive_template = locate_template('page-work-archive.php');
        if ($archive_template) {
            return $archive_template;
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
                    'url' => home_url('/work/archive/')
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
 * Get a persistent random color from site settings (changes every 30 minutes)
 */
function ricelipka_get_random_site_color() {
    // Create a time-based salt that changes every 30 minutes
    $time_slot = floor(time() / (30 * 60)); // 30 minutes = 1800 seconds
    $salt = 'ricelipka_color_' . $time_slot;
    
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
                // Use the salt to seed the random selection for consistency
                $color_index = abs(crc32($salt)) % count($colors);
                return $colors[$color_index];
            }
        }
    }
    
    // Fallback colors if no site colors are configured
    $fallback_colors = array('#000000', '#333333', '#666666', '#990000', '#006600', '#000099');
    $color_index = abs(crc32($salt)) % count($fallback_colors);
    return $fallback_colors[$color_index];
}

/**
 * Add inline CSS with persistent random site color for headings
 */
function ricelipka_add_random_color_css() {
    $random_color = ricelipka_get_random_site_color();
    
    echo '<style type="text/css">';
    echo 'body h1, body h2, body h3, body h4, body h5, body h6, body .heading { color: ' . esc_attr($random_color) . ' !important; }';
    echo '.menu .menu-item.current-menu-item > a, .menu .menu-item.current-menu-ancestor > a, .menu .submenu .submenu-item.current-menu-item > a { color: ' . esc_attr($random_color) . ' !important; }';
    echo '</style>';
}
add_action('wp_head', 'ricelipka_add_random_color_css');
/**
 * Modify posts per page for awards archive
 */
function ricelipka_modify_awards_query($query) {
    // Only modify the main query on the frontend for awards archive
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('awards')) {
        $query->set('posts_per_page', 18);
    }
}
add_action('pre_get_posts', 'ricelipka_modify_awards_query');

/**
 * Modify news archive query
 */
function ricelipka_modify_news_query($query) {
    // Only modify the main query on the frontend for news archive
    if (!is_admin() && $query->is_main_query() && get_query_var('news_archive')) {
        $query->set('posts_per_page', 18);
    }
}
add_action('pre_get_posts', 'ricelipka_modify_news_query');

/**
 * AJAX handler for loading more news
 */
function ricelipka_load_more_news() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'ricelipka_nonce')) {
        wp_die('Security check failed');
    }
    
    $page = intval($_POST['page']);
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 18,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $news_query = new WP_Query($args);
    
    if ($news_query->have_posts()) {
        ob_start();
        
        while ($news_query->have_posts()) {
            $news_query->the_post();
            get_template_part('template-parts/item-news');
        }
        
        $html = ob_get_clean();
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html' => $html,
            'max_pages' => $news_query->max_num_pages,
            'current_page' => $page
        ));
    } else {
        wp_send_json_error('No more posts found');
    }
}
add_action('wp_ajax_load_more_news', 'ricelipka_load_more_news');
add_action('wp_ajax_nopriv_load_more_news', 'ricelipka_load_more_news');
/**
 * AJAX handler for loading more awards
 */
function ricelipka_load_more_awards() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'ricelipka_nonce')) {
        wp_die('Security check failed');
    }
    
    $page = intval($_POST['page']);
    
    $args = array(
        'post_type' => 'awards',
        'post_status' => 'publish',
        'posts_per_page' => 18,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $awards_query = new WP_Query($args);
    
    if ($awards_query->have_posts()) {
        ob_start();
        
        while ($awards_query->have_posts()) {
            $awards_query->the_post();
            get_template_part('template-parts/item-award', null, array(
                'class' => 'award-card',
                'layout' => 'default',
                'image_size' => 'medium',
                'show_meta' => true,
                'show_excerpt' => true
            ));
        }
        
        $html = ob_get_clean();
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html' => $html,
            'max_pages' => $awards_query->max_num_pages,
            'current_page' => $page
        ));
    } else {
        wp_send_json_error('No more awards found');
    }
}
add_action('wp_ajax_load_more_awards', 'ricelipka_load_more_awards');
add_action('wp_ajax_nopriv_load_more_awards', 'ricelipka_load_more_awards');
/**
 * AJAX handler for loading more projects
 */
function ricelipka_load_more_projects() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'ricelipka_nonce')) {
        wp_die('Security check failed');
    }
    
    $page = intval($_POST['page']);
    
    $args = array(
        'post_type' => 'projects',
        'post_status' => 'publish',
        'posts_per_page' => 18,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $projects_query = new WP_Query($args);
    
    if ($projects_query->have_posts()) {
        ob_start();
        
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            get_template_part('template-parts/item-project');
        }
        
        $html = ob_get_clean();
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html' => $html,
            'max_pages' => $projects_query->max_num_pages,
            'current_page' => $page
        ));
    } else {
        wp_send_json_error('No more projects found');
    }
}
add_action('wp_ajax_load_more_projects', 'ricelipka_load_more_projects');
add_action('wp_ajax_nopriv_load_more_projects', 'ricelipka_load_more_projects');

/**
 * Get child pages for a given page ID
 */
function ricelipka_get_page_child_pages($page_id) {
    $child_pages = get_pages(array(
        'parent' => $page_id,
        'post_status' => 'publish',
        'sort_column' => 'menu_order',
        'sort_order' => 'ASC'
    ));
    
    return $child_pages;
}

/**
 * Get display name for project type
 */
function ricelipka_get_project_type_display($project_type) {
    $project_types = array(
        'cultural' => 'Cultural',
        'academic' => 'Academic',
        'offices' => 'Offices',
        'retail_commercial' => 'Retail & Commercial',
        'institutional' => 'Institutional',
        'planning' => 'Planning',
        'exhibitions' => 'Exhibitions',
        'research_installation' => 'Research & Installation',
        'residential' => 'Residential'
    );
    
    return isset($project_types[$project_type]) ? $project_types[$project_type] : ucfirst(str_replace('_', ' ', $project_type));
}

/**
 * Get ACF fields for a post type
 */
function ricelipka_get_post_type_fields($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (!function_exists('get_fields')) {
        return array();
    }
    
    $fields = get_fields($post_id);
    return is_array($fields) ? $fields : array();
}

/**
 * Register ACF Field Groups
 */
function ricelipka_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // Projects Field Group
    acf_add_local_field_group(array(
        'key' => 'group_projects',
        'title' => 'Project Details',
        'fields' => array(
            array(
                'key' => 'field_project_year',
                'label' => 'Project Year',
                'name' => 'project_year',
                'type' => 'number',
                'instructions' => 'Enter the year the project was completed or is expected to be completed.',
                'required' => 0,
                'min' => 1900,
                'max' => 2050,
                'step' => 1,
            ),
            array(
                'key' => 'field_project_type',
                'label' => 'Project Type',
                'name' => 'project_type',
                'type' => 'select',
                'instructions' => 'Select the category that best describes this project.',
                'required' => 0,
                'choices' => array(
                    'cultural' => 'Cultural',
                    'academic' => 'Academic',
                    'offices' => 'Offices',
                    'retail_commercial' => 'Retail & Commercial',
                    'institutional' => 'Institutional',
                    'planning' => 'Planning',
                    'exhibitions' => 'Exhibitions',
                    'research_installation' => 'Research & Installation',
                    'residential' => 'Residential',
                ),
                'default_value' => '',
                'allow_null' => 1,
            ),
            array(
                'key' => 'field_client',
                'label' => 'Client',
                'name' => 'client',
                'type' => 'text',
                'instructions' => 'Enter the client organization or entity that commissioned this project.',
                'required' => 0,
            ),
            array(
                'key' => 'field_location',
                'label' => 'Location',
                'name' => 'location',
                'type' => 'text',
                'instructions' => 'Enter the project location (city, state or full address if appropriate).',
                'required' => 0,
            ),
            array(
                'key' => 'field_completion_status',
                'label' => 'Completion Status',
                'name' => 'completion_status',
                'type' => 'select',
                'instructions' => 'Select the current completion status of the project.',
                'required' => 0,
                'choices' => array(
                    'planning' => 'Planning',
                    'design' => 'Design',
                    'construction' => 'Construction',
                    'completed' => 'Completed',
                ),
                'default_value' => 'completed',
                'allow_null' => 0,
            ),
            array(
                'key' => 'field_image_gallery',
                'label' => 'Image Gallery',
                'name' => 'image_gallery',
                'type' => 'gallery',
                'instructions' => 'Upload images for the project gallery. Include exterior views, interior spaces, detail shots, and construction progress.',
                'required' => 0,
                'min' => 0,
                'max' => 20,
                'insert' => 'append',
                'library' => 'all',
                'min_width' => 800,
                'min_height' => 600,
                'return_format' => 'array',
                'preview_size' => 'medium',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'projects',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));

    // News Field Group
    acf_add_local_field_group(array(
        'key' => 'group_news',
        'title' => 'News Details',
        'fields' => array(
            array(
                'key' => 'field_publication_date',
                'label' => 'Publication Date',
                'name' => 'publication_date',
                'type' => 'date_picker',
                'instructions' => 'Select the date this news item was published.',
                'required' => 0,
                'display_format' => 'F j, Y',
                'return_format' => 'Y-m-d',
                'first_day' => 1,
            ),
            array(
                'key' => 'field_subcategory',
                'label' => 'News Category',
                'name' => 'subcategory',
                'type' => 'select',
                'instructions' => 'Select the category for this news item.',
                'required' => 0,
                'choices' => array(
                    'press' => 'Press',
                    'awards' => 'Awards',
                    'events' => 'Events',
                    'announcements' => 'Announcements',
                    'publications' => 'Publications',
                ),
                'default_value' => '',
                'allow_null' => 1,
            ),
            array(
                'key' => 'field_excerpt',
                'label' => 'Custom Excerpt',
                'name' => 'excerpt',
                'type' => 'textarea',
                'instructions' => 'Enter a custom excerpt for this news item (optional).',
                'required' => 0,
                'rows' => 3,
            ),
            array(
                'key' => 'field_featured_image',
                'label' => 'Featured Image',
                'name' => 'featured_image',
                'type' => 'image',
                'instructions' => 'Upload a featured image for this news item.',
                'required' => 0,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => 600,
                'min_height' => 400,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'news',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));

    // Awards Field Group
    acf_add_local_field_group(array(
        'key' => 'group_awards',
        'title' => 'Award Details',
        'fields' => array(
            array(
                'key' => 'field_award_name',
                'label' => 'Award Name',
                'name' => 'award_name',
                'type' => 'text',
                'instructions' => 'Enter the official name of the award.',
                'required' => 0,
            ),
            array(
                'key' => 'field_awarding_organization',
                'label' => 'Awarding Organization',
                'name' => 'awarding_organization',
                'type' => 'text',
                'instructions' => 'Enter the name of the organization that gave this award.',
                'required' => 0,
            ),
            array(
                'key' => 'field_date_received',
                'label' => 'Date Received',
                'name' => 'date_received',
                'type' => 'date_picker',
                'instructions' => 'Select the date this award was received.',
                'required' => 0,
                'display_format' => 'F j, Y',
                'return_format' => 'Y-m-d',
                'first_day' => 1,
            ),
            array(
                'key' => 'field_associated_project',
                'label' => 'Associated Project',
                'name' => 'associated_project',
                'type' => 'post_object',
                'instructions' => 'Select the project this award is associated with (if applicable).',
                'required' => 0,
                'post_type' => array('projects'),
                'taxonomy' => '',
                'allow_null' => 1,
                'multiple' => 0,
                'return_format' => 'object',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'awards',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));

    // People Field Group
    acf_add_local_field_group(array(
        'key' => 'group_people',
        'title' => 'Person Details',
        'fields' => array(
            array(
                'key' => 'field_person_title',
                'label' => 'Job Title',
                'name' => 'person_title',
                'type' => 'text',
                'instructions' => 'Enter the person\'s job title or position.',
                'required' => 0,
            ),
            array(
                'key' => 'field_person_associations',
                'label' => 'Professional Associations',
                'name' => 'person_associations',
                'type' => 'textarea',
                'instructions' => 'Enter any professional associations or credentials.',
                'required' => 0,
                'rows' => 3,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'people',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));
}
add_action('acf/init', 'ricelipka_register_acf_fields');
/**
 * Check if ACF is installed and show admin notice if not
 */
function ricelipka_check_acf_plugin() {
    if (!function_exists('acf_add_local_field_group')) {
        add_action('admin_notices', 'ricelipka_acf_missing_notice');
    }
}
add_action('admin_init', 'ricelipka_check_acf_plugin');

/**
 * Display admin notice if ACF is missing
 */
function ricelipka_acf_missing_notice() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p>
            <strong>Rice+Lipka Theme:</strong> This theme requires the Advanced Custom Fields (ACF) plugin to display custom fields in the admin. 
            <a href="<?php echo admin_url('plugin-install.php?s=advanced+custom+fields&tab=search&type=term'); ?>">Install ACF Plugin</a>
        </p>
    </div>
    <?php
}