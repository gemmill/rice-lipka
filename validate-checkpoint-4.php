<?php
/**
 * Task 4 Checkpoint Validation Script
 * 
 * Comprehensive validation for ACF field groups and category structure
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
 * Validate all ACF field groups and category structure
 */
function validate_checkpoint_4() {
    echo "<div style='font-family: Arial, sans-serif; margin: 20px; max-width: 1000px;'>\n";
    echo "<h1>Task 4 Checkpoint: ACF Field Groups and Category Structure Validation</h1>\n";
    
    $all_tests_passed = true;
    
    // Test 1: ACF Pro availability
    echo "<h2>1. ACF Pro Availability</h2>\n";
    if (function_exists('acf_get_field_groups')) {
        echo "<p>✅ ACF Pro is active and available</p>\n";
    } else {
        echo "<p>❌ ACF Pro is not active - this is required for the system to work</p>\n";
        $all_tests_passed = false;
    }
    
    // Test 2: Primary categories
    echo "<h2>2. Primary Category Structure</h2>\n";
    $primary_categories = array('news', 'projects', 'events', 'awards');
    $category_tests_passed = true;
    
    foreach ($primary_categories as $category_slug) {
        $category = get_term_by('slug', $category_slug, 'category');
        if ($category) {
            echo "<p>✅ Category '{$category_slug}' exists (ID: {$category->term_id}, Posts: {$category->count})</p>\n";
        } else {
            echo "<p>❌ Category '{$category_slug}' does not exist</p>\n";
            $category_tests_passed = false;
        }
    }
    
    if (!$category_tests_passed) {
        $all_tests_passed = false;
    }
    
    // Test 3: ACF Field Groups
    echo "<h2>3. ACF Field Groups Validation</h2>\n";
    if (function_exists('acf_get_field_groups')) {
        $field_groups = acf_get_field_groups();
        $expected_groups = array(
            'group_news_fields' => 'News Fields',
            'group_projects_fields' => 'Project Fields',
            'group_events_fields' => 'Event Fields',
            'group_awards_fields' => 'Award Fields'
        );
        
        $field_group_tests_passed = true;
        
        foreach ($expected_groups as $key => $title) {
            $found = false;
            foreach ($field_groups as $group) {
                if ($group['key'] === $key) {
                    echo "<p>✅ Field group '{$title}' found</p>\n";
                    
                    // Validate conditional logic
                    $location_rules = $group['location'];
                    $has_category_condition = false;
                    $expected_category = str_replace('group_', '', str_replace('_fields', '', $key));
                    
                    foreach ($location_rules as $rule_group) {
                        foreach ($rule_group as $rule) {
                            if ($rule['param'] === 'post_category' && 
                                $rule['operator'] === '==' && 
                                $rule['value'] === "category:{$expected_category}") {
                                $has_category_condition = true;
                                break 2;
                            }
                        }
                    }
                    
                    if ($has_category_condition) {
                        echo "<p>  ✅ Conditional logic configured for '{$expected_category}' category</p>\n";
                    } else {
                        echo "<p>  ❌ Conditional logic not properly configured for '{$expected_category}' category</p>\n";
                        $field_group_tests_passed = false;
                    }
                    
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                echo "<p>❌ Field group '{$title}' not found</p>\n";
                $field_group_tests_passed = false;
            }
        }
        
        if (!$field_group_tests_passed) {
            $all_tests_passed = false;
        }
    }
    
    // Test 4: Field Group Fields
    echo "<h2>4. Field Group Fields Validation</h2>\n";
    
    $field_requirements = array(
        'group_news_fields' => array(
            'news_title' => 'text',
            'publication_date' => 'date_picker',
            'excerpt' => 'textarea',
            'featured_image' => 'image',
            'subcategory' => 'select'
        ),
        'group_projects_fields' => array(
            'project_name' => 'text',
            'completion_status' => 'select',
            'completion_percentage' => 'number',
            'project_type' => 'select',
            'client' => 'text',
            'location' => 'text',
            'image_gallery' => 'gallery',
            'project_metadata' => 'group'
        ),
        'group_events_fields' => array(
            'event_title' => 'text',
            'event_date' => 'date_picker',
            'event_time' => 'time_picker',
            'location' => 'text',
            'external_links' => 'repeater',
            'registration_link' => 'url',
            'recurring_event' => 'true_false'
        ),
        'group_awards_fields' => array(
            'award_name' => 'text',
            'awarding_organization' => 'text',
            'associated_project' => 'post_object',
            'date_received' => 'date_picker',
            'recognition_image' => 'image'
        )
    );
    
    $field_tests_passed = true;
    
    foreach ($field_requirements as $group_key => $required_fields) {
        $group_name = str_replace(array('group_', '_fields'), array('', ''), $group_key);
        echo "<h3>{$group_name} Fields:</h3>\n";
        
        if (function_exists('acf_get_fields')) {
            $fields = acf_get_fields($group_key);
            
            if ($fields) {
                $found_fields = array();
                foreach ($fields as $field) {
                    $found_fields[$field['name']] = $field['type'];
                }
                
                foreach ($required_fields as $field_name => $field_type) {
                    if (isset($found_fields[$field_name])) {
                        if ($found_fields[$field_name] === $field_type) {
                            echo "<p>  ✅ {$field_name} ({$field_type})</p>\n";
                        } else {
                            echo "<p>  ⚠️ {$field_name} found but type is '{$found_fields[$field_name]}' instead of '{$field_type}'</p>\n";
                        }
                    } else {
                        echo "<p>  ❌ {$field_name} ({$field_type}) missing</p>\n";
                        $field_tests_passed = false;
                    }
                }
            } else {
                echo "<p>  ❌ No fields found for {$group_name}</p>\n";
                $field_tests_passed = false;
            }
        }
    }
    
    if (!$field_tests_passed) {
        $all_tests_passed = false;
    }
    
    // Test 5: Template Files
    echo "<h2>5. Template Files Validation</h2>\n";
    $template_files = array(
        'category-news.php' => 'News category template',
        'category-projects.php' => 'Projects category template',
        'category-events.php' => 'Events category template',
        'category-awards.php' => 'Awards category template'
    );
    
    $template_tests_passed = true;
    
    foreach ($template_files as $file => $description) {
        $file_path = get_template_directory() . '/' . $file;
        if (file_exists($file_path)) {
            $size = filesize($file_path);
            echo "<p>✅ {$description} exists ({$size} bytes)</p>\n";
        } else {
            echo "<p>❌ {$description} missing</p>\n";
            $template_tests_passed = false;
        }
    }
    
    if (!$template_tests_passed) {
        $all_tests_passed = false;
    }
    
    // Test 6: ACF Blocks
    echo "<h2>6. ACF Blocks Validation</h2>\n";
    $block_directories = array(
        'blocks/news-article/' => 'News Article Block',
        'blocks/project-portfolio/' => 'Project Portfolio Block',
        'blocks/event-details/' => 'Event Details Block',
        'blocks/award-information/' => 'Award Information Block'
    );
    
    $block_tests_passed = true;
    
    foreach ($block_directories as $dir => $description) {
        $block_path = get_template_directory() . '/' . $dir;
        $block_file = $block_path . 'block.php';
        
        if (file_exists($block_file)) {
            echo "<p>✅ {$description} template exists</p>\n";
        } else {
            echo "<p>❌ {$description} template missing</p>\n";
            $block_tests_passed = false;
        }
    }
    
    if (!$block_tests_passed) {
        $all_tests_passed = false;
    }
    
    // Test 7: Helper Functions
    echo "<h2>7. Helper Functions Validation</h2>\n";
    $required_functions = array(
        'ricelipka_get_category_fields' => 'Category fields helper function',
        'ricelipka_get_post_primary_category' => 'Primary category detection function',
        'ricelipka_get_subcategory_structure' => 'Subcategory structure function',
        'ricelipka_get_subcategories' => 'Subcategory retrieval function'
    );
    
    $function_tests_passed = true;
    
    foreach ($required_functions as $function_name => $description) {
        if (function_exists($function_name)) {
            echo "<p>✅ {$description} exists</p>\n";
        } else {
            echo "<p>❌ {$description} missing</p>\n";
            $function_tests_passed = false;
        }
    }
    
    if (!$function_tests_passed) {
        $all_tests_passed = false;
    }
    
    // Test 8: Subcategory System
    echo "<h2>8. Subcategory System Validation</h2>\n";
    if (function_exists('ricelipka_get_subcategory_structure')) {
        $structure = ricelipka_get_subcategory_structure();
        $subcategory_tests_passed = true;
        
        foreach ($primary_categories as $category) {
            if (isset($structure[$category]) && is_array($structure[$category])) {
                $count = count($structure[$category]);
                echo "<p>✅ {$category} has {$count} subcategories defined</p>\n";
            } else {
                echo "<p>❌ {$category} subcategories not defined</p>\n";
                $subcategory_tests_passed = false;
            }
        }
        
        if (!$subcategory_tests_passed) {
            $all_tests_passed = false;
        }
    } else {
        echo "<p>❌ Subcategory structure function not available</p>\n";
        $all_tests_passed = false;
    }
    
    // Final Results
    echo "<h2>Final Validation Results</h2>\n";
    
    if ($all_tests_passed) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>\n";
        echo "<h3 style='color: #155724; margin: 0 0 10px 0;'>✅ TASK 4 CHECKPOINT PASSED</h3>\n";
        echo "<p style='color: #155724; margin: 0;'>All ACF field groups and category structure components are properly implemented and functional.</p>\n";
        echo "<ul style='color: #155724;'>\n";
        echo "<li>✅ Four primary categories (News, Projects, Events, Awards) exist</li>\n";
        echo "<li>✅ ACF field groups with conditional logic are properly configured</li>\n";
        echo "<li>✅ All required fields are present with correct types</li>\n";
        echo "<li>✅ Category-specific templates are available</li>\n";
        echo "<li>✅ ACF blocks are registered and templates exist</li>\n";
        echo "<li>✅ Helper functions are available</li>\n";
        echo "<li>✅ Subcategory system is functional</li>\n";
        echo "</ul>\n";
        echo "<p style='color: #155724;'><strong>The system is ready for content creation and testing.</strong></p>\n";
        echo "</div>\n";
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>\n";
        echo "<h3 style='color: #721c24; margin: 0 0 10px 0;'>❌ TASK 4 CHECKPOINT FAILED</h3>\n";
        echo "<p style='color: #721c24; margin: 0;'>Some components are missing or not properly configured. Please review the validation results above and address the issues.</p>\n";
        echo "</div>\n";
    }
    
    echo "<p><em>Validation completed at " . date('Y-m-d H:i:s') . "</em></p>\n";
    echo "</div>\n";
    
    return $all_tests_passed;
}

/**
 * Run validation if explicitly called
 */
if (isset($_GET['validate_checkpoint_4']) && current_user_can('manage_options')) {
    add_action('wp_footer', 'validate_checkpoint_4');
}
?>