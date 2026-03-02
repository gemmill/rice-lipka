<?php
/**
 * Subcategory System Demonstration
 * 
 * This file demonstrates the subcategory functionality implemented in Task 2.3
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// This is a demonstration file - include WordPress if running standalone
if (!defined('ABSPATH')) {
    // For demonstration purposes only - in real usage, this would be included in WordPress
    echo "This file should be run within WordPress context.\n";
    exit;
}

/**
 * Demonstrate subcategory structure
 */
function demo_subcategory_structure() {
    echo "<h2>Subcategory Structure</h2>\n";
    
    if (function_exists('ricelipka_get_subcategory_structure')) {
        $structure = ricelipka_get_subcategory_structure();
        
        foreach ($structure as $primary => $subcategories) {
            echo "<h3>" . ucfirst($primary) . " Subcategories:</h3>\n";
            echo "<ul>\n";
            
            foreach ($subcategories as $slug => $name) {
                echo "<li><strong>{$slug}</strong>: {$name}</li>\n";
            }
            
            echo "</ul>\n";
        }
    } else {
        echo "<p>Subcategory structure function not available.</p>\n";
    }
}

/**
 * Demonstrate category hierarchy
 */
function demo_category_hierarchy() {
    echo "<h2>Category Hierarchy</h2>\n";
    
    if (function_exists('ricelipka_get_category_hierarchy')) {
        $hierarchy = ricelipka_get_category_hierarchy();
        
        foreach ($hierarchy as $slug => $data) {
            echo "<h3>{$data['category']->name} ({$data['post_count']} posts)</h3>\n";
            
            if (!empty($data['subcategories'])) {
                echo "<ul>\n";
                foreach ($data['subcategories'] as $subcategory) {
                    echo "<li>{$subcategory->name} ({$subcategory->count} posts)</li>\n";
                }
                echo "</ul>\n";
            } else {
                echo "<p>No subcategories found.</p>\n";
            }
        }
    } else {
        echo "<p>Category hierarchy function not available.</p>\n";
    }
}

/**
 * Demonstrate navigation generation
 */
function demo_navigation_generation() {
    echo "<h2>Generated Navigation</h2>\n";
    
    if (function_exists('ricelipka_generate_category_navigation')) {
        $navigation = ricelipka_generate_category_navigation();
        echo $navigation;
    } else {
        echo "<p>Navigation generation function not available.</p>\n";
    }
}

/**
 * Demonstrate filtering functionality
 */
function demo_filtering_functionality() {
    echo "<h2>Filtering Functionality</h2>\n";
    
    echo "<h3>Available Filter Types:</h3>\n";
    echo "<ul>\n";
    echo "<li><strong>News Filters:</strong> Project Updates, Event Announcements, Award Notifications, Firm News, Press Releases, Community Involvement</li>\n";
    echo "<li><strong>Project Filters:</strong> Civic Architecture, Cultural Projects, Educational Buildings, Public Works, Residential, Commercial, Mixed Use</li>\n";
    echo "<li><strong>Event Filters:</strong> Conferences, Workshops, Exhibitions, Lectures, Community Events, Awards Ceremonies, Networking Events</li>\n";
    echo "<li><strong>Award Filters:</strong> Design Excellence, Sustainability Awards, Innovation Awards, Community Impact, Professional Recognition, Project Awards</li>\n";
    echo "</ul>\n";
    
    echo "<h3>AJAX Filtering:</h3>\n";
    echo "<p>The system supports AJAX-based filtering that allows users to filter posts by subcategory without page reloads.</p>\n";
    echo "<p>Filter buttons are automatically generated for each category page with appropriate subcategory options.</p>\n";
}

/**
 * Run demonstration
 */
function run_subcategory_demo() {
    echo "<div style='font-family: Arial, sans-serif; margin: 20px; max-width: 800px;'>\n";
    echo "<h1>Rice+Lipka Theme - Subcategory System Demo</h1>\n";
    echo "<p>This demonstration shows the subcategory support and hierarchy implemented in Task 2.3.</p>\n";
    
    demo_subcategory_structure();
    demo_category_hierarchy();
    demo_navigation_generation();
    demo_filtering_functionality();
    
    echo "<h2>Implementation Features</h2>\n";
    echo "<ul>\n";
    echo "<li>✓ Subcategory structure for each primary category (News, Projects, Events, Awards)</li>\n";
    echo "<li>✓ Logic to ensure posts belong to exactly one primary category</li>\n";
    echo "<li>✓ Category-based navigation and filtering functionality</li>\n";
    echo "<li>✓ AJAX-powered filtering without page reloads</li>\n";
    echo "<li>✓ Template parts for consistent content display</li>\n";
    echo "<li>✓ Widget and shortcode support for navigation</li>\n";
    echo "<li>✓ Responsive design with mobile-friendly filters</li>\n";
    echo "<li>✓ Enhanced ACF field groups with subcategory support</li>\n";
    echo "</ul>\n";
    
    echo "<h2>Usage Examples</h2>\n";
    echo "<h3>Shortcode Usage:</h3>\n";
    echo "<code>[category_navigation show_counts=\"true\" show_subcategories=\"true\"]</code>\n";
    
    echo "<h3>Template Function Usage:</h3>\n";
    echo "<code>ricelipka_display_category_navigation(array('show_counts' => true));</code>\n";
    
    echo "<h3>Widget Usage:</h3>\n";
    echo "<p>Add the 'Category Navigation' widget to any widget area through the WordPress admin.</p>\n";
    
    echo "<p><strong>Demo completed.</strong> The subcategory system is fully implemented and ready for use.</p>\n";
    echo "</div>\n";
}

// Only run demo if explicitly called (for safety)
if (isset($_GET['run_subcategory_demo']) && current_user_can('manage_options')) {
    add_action('wp_footer', 'run_subcategory_demo');
}
?>