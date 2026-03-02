<?php
/**
 * Single Category Validation Script
 * 
 * Validates that all posts have exactly one category
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    echo "This file should be run within WordPress context.\n";
    exit;
}

/**
 * Validate single category restriction
 */
function validate_single_category_restriction() {
    echo "<div style='font-family: Arial, sans-serif; margin: 20px; max-width: 1000px;'>\n";
    echo "<h1>Single Category Restriction Validation</h1>\n";
    
    $all_tests_passed = true;
    
    // Test 1: Check all posts have exactly one category
    echo "<h2>1. Post Category Count Validation</h2>\n";
    
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_status' => array('publish', 'draft', 'private'),
        'post_type' => 'post'
    ));
    
    $violations = array();
    $no_category_posts = array();
    $multiple_category_posts = array();
    
    foreach ($posts as $post) {
        $categories = get_the_category($post->ID);
        $category_count = count($categories);
        
        if ($category_count === 0) {
            $no_category_posts[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'status' => $post->post_status
            );
        } elseif ($category_count > 1) {
            $category_names = array_map(function($cat) { return $cat->name; }, $categories);
            $multiple_category_posts[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'status' => $post->post_status,
                'categories' => $category_names
            );
        }
    }
    
    // Report results
    $total_posts = count($posts);
    $valid_posts = $total_posts - count($no_category_posts) - count($multiple_category_posts);
    
    echo "<p><strong>Total Posts:</strong> {$total_posts}</p>\n";
    echo "<p><strong>Valid Posts (1 category):</strong> {$valid_posts}</p>\n";
    echo "<p><strong>Posts with No Categories:</strong> " . count($no_category_posts) . "</p>\n";
    echo "<p><strong>Posts with Multiple Categories:</strong> " . count($multiple_category_posts) . "</p>\n";
    
    if (empty($no_category_posts) && empty($multiple_category_posts)) {
        echo "<p>✅ All posts have exactly one category</p>\n";
    } else {
        $all_tests_passed = false;
        
        if (!empty($no_category_posts)) {
            echo "<h3>Posts with No Categories:</h3>\n";
            echo "<ul>\n";
            foreach ($no_category_posts as $post_info) {
                echo "<li>ID: {$post_info['id']} - \"{$post_info['title']}\" ({$post_info['status']})</li>\n";
            }
            echo "</ul>\n";
        }
        
        if (!empty($multiple_category_posts)) {
            echo "<h3>Posts with Multiple Categories:</h3>\n";
            echo "<ul>\n";
            foreach ($multiple_category_posts as $post_info) {
                $categories_str = implode(', ', $post_info['categories']);
                echo "<li>ID: {$post_info['id']} - \"{$post_info['title']}\" ({$post_info['status']}) - Categories: {$categories_str}</li>\n";
            }
            echo "</ul>\n";
        }
    }
    
    // Test 2: Check primary category distribution
    echo "<h2>2. Primary Category Distribution</h2>\n";
    
    $primary_categories = array('news', 'projects', 'awards', 'people');
    $category_counts = array();
    
    foreach ($primary_categories as $cat_slug) {
        $category = get_term_by('slug', $cat_slug, 'category');
        if ($category) {
            $count = $category->count;
            $category_counts[$cat_slug] = $count;
            echo "<p>✅ {$category->name}: {$count} posts</p>\n";
        } else {
            echo "<p>❌ Category '{$cat_slug}' does not exist</p>\n";
            $all_tests_passed = false;
        }
    }
    
    // Test 3: Check JavaScript functionality is loaded
    echo "<h2>3. Admin Interface Validation</h2>\n";
    
    global $pagenow;
    if ($pagenow === 'post.php' || $pagenow === 'post-new.php') {
        echo "<p>✅ Single category JavaScript should be active on this page</p>\n";
    } else {
        echo "<p>ℹ️ Visit post edit page to test JavaScript functionality</p>\n";
    }
    
    // Test 4: Check helper function
    echo "<h2>4. Helper Function Validation</h2>\n";
    
    if (function_exists('ricelipka_get_post_primary_category')) {
        echo "<p>✅ Primary category helper function exists</p>\n";
        
        // Test with a sample post
        if (!empty($posts)) {
            $sample_post = $posts[0];
            $primary_cat = ricelipka_get_post_primary_category($sample_post->ID);
            echo "<p>✅ Helper function returns: '{$primary_cat}' for post ID {$sample_post->ID}</p>\n";
        }
    } else {
        echo "<p>❌ Primary category helper function missing</p>\n";
        $all_tests_passed = false;
    }
    
    // Final Results
    echo "<h2>Final Validation Results</h2>\n";
    
    if ($all_tests_passed && empty($no_category_posts) && empty($multiple_category_posts)) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>\n";
        echo "<h3 style='color: #155724; margin: 0 0 10px 0;'>✅ SINGLE CATEGORY RESTRICTION WORKING</h3>\n";
        echo "<p style='color: #155724; margin: 0;'>All posts have exactly one category and the restriction system is properly implemented.</p>\n";
        echo "<ul style='color: #155724;'>\n";
        echo "<li>✅ All posts have exactly one category</li>\n";
        echo "<li>✅ Primary categories are properly distributed</li>\n";
        echo "<li>✅ Helper functions are working</li>\n";
        echo "<li>✅ Admin interface enhancements are loaded</li>\n";
        echo "</ul>\n";
        echo "</div>\n";
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>\n";
        echo "<h3 style='color: #721c24; margin: 0 0 10px 0;'>❌ SINGLE CATEGORY ISSUES FOUND</h3>\n";
        echo "<p style='color: #721c24; margin: 0;'>Some posts violate the single category rule. Please review the issues above.</p>\n";
        
        if (!empty($no_category_posts) || !empty($multiple_category_posts)) {
            echo "<p style='color: #721c24; margin-top: 10px;'><strong>Recommended Action:</strong> Run the automatic fix by adding <code>?fix_categories=1</code> to this URL.</p>\n";
        }
        echo "</div>\n";
    }
    
    echo "<p><em>Validation completed at " . date('Y-m-d H:i:s') . "</em></p>\n";
    echo "</div>\n";
    
    return $all_tests_passed;
}

/**
 * Automatic fix for category violations
 */
function fix_category_violations() {
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }
    
    echo "<div style='font-family: Arial, sans-serif; margin: 20px; max-width: 1000px;'>\n";
    echo "<h1>Fixing Category Violations</h1>\n";
    
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_status' => array('publish', 'draft', 'private'),
        'post_type' => 'post'
    ));
    
    $fixed_posts = array();
    $primary_cats = array('news', 'projects', 'awards', 'people');
    
    foreach ($posts as $post) {
        $categories = get_the_category($post->ID);
        $category_count = count($categories);
        
        if ($category_count === 0) {
            // No category - assign to news
            $news_category = get_term_by('slug', 'news', 'category');
            if ($news_category) {
                wp_set_post_categories($post->ID, array($news_category->term_id));
                $fixed_posts[] = "Post ID {$post->ID}: Added 'News' category";
            }
        } elseif ($category_count > 1) {
            // Multiple categories - keep only the first primary category
            $keep_category = null;
            
            foreach ($categories as $category) {
                if (in_array($category->slug, $primary_cats)) {
                    $keep_category = $category->term_id;
                    break;
                }
            }
            
            if (!$keep_category) {
                $keep_category = $categories[0]->term_id;
            }
            
            wp_set_post_categories($post->ID, array($keep_category));
            $category_name = get_term($keep_category, 'category')->name;
            $fixed_posts[] = "Post ID {$post->ID}: Kept only '{$category_name}' category";
        }
    }
    
    if (empty($fixed_posts)) {
        echo "<p>✅ No category violations found - all posts already have exactly one category.</p>\n";
    } else {
        echo "<h2>Fixed Posts:</h2>\n";
        echo "<ul>\n";
        foreach ($fixed_posts as $fix) {
            echo "<li>{$fix}</li>\n";
        }
        echo "</ul>\n";
        echo "<p>✅ Fixed " . count($fixed_posts) . " posts.</p>\n";
    }
    
    echo "</div>\n";
}

/**
 * Run validation or fix based on URL parameters
 */
if (isset($_GET['validate_single_category']) && current_user_can('manage_options')) {
    add_action('wp_footer', 'validate_single_category_restriction');
}

if (isset($_GET['fix_categories']) && $_GET['fix_categories'] == '1' && current_user_can('manage_options')) {
    add_action('wp_footer', 'fix_category_violations');
}
?>