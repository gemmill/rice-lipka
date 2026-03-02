<?php
/**
 * Test file for category structure functionality
 * 
 * This file can be used to test the category detection and field retrieval
 * functionality during development.
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Test category detection function
 */
function test_category_detection() {
    // Test primary categories
    $primary_categories = array('news', 'projects', 'events', 'awards');
    
    echo "<h2>Testing Category Detection</h2>\n";
    
    foreach ($primary_categories as $category) {
        echo "<h3>Testing category: {$category}</h3>\n";
        
        // Check if category exists
        $term = get_term_by('slug', $category, 'category');
        if ($term) {
            echo "<p>✓ Category '{$category}' exists (ID: {$term->term_id})</p>\n";
        } else {
            echo "<p>✗ Category '{$category}' does not exist</p>\n";
        }
    }
}

/**
 * Test ACF field groups
 */
function test_acf_field_groups() {
    echo "<h2>Testing ACF Field Groups</h2>\n";
    
    if (!function_exists('acf_get_field_groups')) {
        echo "<p>✗ ACF Pro is not active</p>\n";
        return;
    }
    
    $field_groups = acf_get_field_groups();
    $expected_groups = array(
        'group_news_fields' => 'News Fields',
        'group_projects_fields' => 'Project Fields',
        'group_events_fields' => 'Event Fields',
        'group_awards_fields' => 'Award Fields'
    );
    
    foreach ($expected_groups as $key => $title) {
        $found = false;
        foreach ($field_groups as $group) {
            if ($group['key'] === $key) {
                echo "<p>✓ Field group '{$title}' found</p>\n";
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "<p>✗ Field group '{$title}' not found</p>\n";
        }
    }
}

/**
 * Test template files
 */
function test_template_files() {
    echo "<h2>Testing Template Files</h2>\n";
    
    $template_files = array(
        'category-news.php' => 'News category template',
        'category-projects.php' => 'Projects category template',
        'category-events.php' => 'Events category template',
        'category-awards.php' => 'Awards category template'
    );
    
    foreach ($template_files as $file => $description) {
        $file_path = get_template_directory() . '/' . $file;
        if (file_exists($file_path)) {
            echo "<p>✓ {$description} exists</p>\n";
        } else {
            echo "<p>✗ {$description} missing</p>\n";
        }
    }
}

/**
 * Test subcategory functionality
 */
function test_subcategory_functionality() {
    echo "<h2>Testing Subcategory Functionality</h2>\n";
    
    // Test subcategory structure function
    if (function_exists('ricelipka_get_subcategory_structure')) {
        echo "<p>✓ ricelipka_get_subcategory_structure() function exists</p>\n";
        
        $structure = ricelipka_get_subcategory_structure();
        $expected_categories = array('news', 'projects', 'events', 'awards');
        
        foreach ($expected_categories as $category) {
            if (isset($structure[$category]) && is_array($structure[$category])) {
                $count = count($structure[$category]);
                echo "<p>✓ {$category} has {$count} subcategories defined</p>\n";
            } else {
                echo "<p>✗ {$category} subcategories not defined</p>\n";
            }
        }
    } else {
        echo "<p>✗ ricelipka_get_subcategory_structure() function missing</p>\n";
    }
    
    // Test subcategory retrieval function
    if (function_exists('ricelipka_get_subcategories')) {
        echo "<p>✓ ricelipka_get_subcategories() function exists</p>\n";
    } else {
        echo "<p>✗ ricelipka_get_subcategories() function missing</p>\n";
    }
    
    // Test enhanced primary category function
    if (function_exists('ricelipka_get_post_primary_category_with_subcategories')) {
        echo "<p>✓ ricelipka_get_post_primary_category_with_subcategories() function exists</p>\n";
    } else {
        echo "<p>✗ ricelipka_get_post_primary_category_with_subcategories() function missing</p>\n";
    }
    
    // Test category hierarchy function
    if (function_exists('ricelipka_get_category_hierarchy')) {
        echo "<p>✓ ricelipka_get_category_hierarchy() function exists</p>\n";
    } else {
        echo "<p>✗ ricelipka_get_category_hierarchy() function missing</p>\n";
    }
    
    // Test navigation generation function
    if (function_exists('ricelipka_generate_category_navigation')) {
        echo "<p>✓ ricelipka_generate_category_navigation() function exists</p>\n";
    } else {
        echo "<p>✗ ricelipka_generate_category_navigation() function missing</p>\n";
    }
}

/**
 * Test template parts
 */
function test_template_parts() {
    echo "<h2>Testing Template Parts</h2>\n";
    
    $template_parts = array(
        'template-parts/content-news.php' => 'News content template part',
        'template-parts/content-projects.php' => 'Projects content template part',
        'template-parts/content-events.php' => 'Events content template part',
        'template-parts/content-awards.php' => 'Awards content template part'
    );
    
    foreach ($template_parts as $file => $description) {
        $file_path = get_template_directory() . '/' . $file;
        if (file_exists($file_path)) {
            echo "<p>✓ {$description} exists</p>\n";
        } else {
            echo "<p>✗ {$description} missing</p>\n";
        }
    }
}

/**
 * Test widget functionality
 */
function test_widget_functionality() {
    echo "<h2>Testing Widget Functionality</h2>\n";
    
    // Test widget class
    if (class_exists('RiceLipka_Category_Navigation_Widget')) {
        echo "<p>✓ Category Navigation Widget class exists</p>\n";
    } else {
        echo "<p>✗ Category Navigation Widget class missing</p>\n";
    }
    
    // Test shortcode
    if (shortcode_exists('category_navigation')) {
        echo "<p>✓ [category_navigation] shortcode registered</p>\n";
    } else {
        echo "<p>✗ [category_navigation] shortcode not registered</p>\n";
    }
    
    // Test template function
    if (function_exists('ricelipka_display_category_navigation')) {
        echo "<p>✓ ricelipka_display_category_navigation() function exists</p>\n";
    } else {
        echo "<p>✗ ricelipka_display_category_navigation() function missing</p>\n";
    }
}

/**
 * Test AJAX functionality
 */
function test_ajax_functionality() {
    echo "<h2>Testing AJAX Functionality</h2>\n";
    
    // Check if AJAX actions are registered
    global $wp_filter;
    
    $ajax_actions = array(
        'wp_ajax_filter_posts_by_category',
        'wp_ajax_nopriv_filter_posts_by_category'
    );
    
    foreach ($ajax_actions as $action) {
        if (isset($wp_filter[$action])) {
            echo "<p>✓ AJAX action '{$action}' registered</p>\n";
        } else {
            echo "<p>✗ AJAX action '{$action}' not registered</p>\n";
        }
    }
}

/**
 * Test CSS and JS files
 */
function test_assets() {
    echo "<h2>Testing Assets</h2>\n";
    
    $assets = array(
        'assets/css/custom.css' => 'Custom CSS file',
        'assets/js/main.js' => 'Main JavaScript file'
    );
    
    foreach ($assets as $file => $description) {
        $file_path = get_template_directory() . '/' . $file;
        if (file_exists($file_path)) {
            echo "<p>✓ {$description} exists</p>\n";
            
            // Check file size to ensure it's not empty
            $size = filesize($file_path);
            if ($size > 0) {
                echo "<p>✓ {$description} has content ({$size} bytes)</p>\n";
            } else {
                echo "<p>⚠ {$description} is empty</p>\n";
            }
        } else {
            echo "<p>✗ {$description} missing</p>\n";
        }
    }
}

/**
 * Run all tests
 */
function run_category_structure_tests() {
    echo "<div style='font-family: Arial, sans-serif; margin: 20px;'>\n";
    echo "<h1>Rice+Lipka Theme Category Structure Tests</h1>\n";
    
    test_category_detection();
    test_acf_field_groups();
    test_template_files();
    test_subcategory_functionality();
    test_template_parts();
    test_widget_functionality();
    test_ajax_functionality();
    test_assets();
    
    echo "<p><strong>Test completed.</strong> Check the results above.</p>\n";
    echo "</div>\n";
}

// Only run tests if explicitly called (for safety)
if (isset($_GET['run_category_tests']) && current_user_can('manage_options')) {
    add_action('wp_footer', 'run_category_structure_tests');
}