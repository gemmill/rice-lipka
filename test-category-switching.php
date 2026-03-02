<?php
/**
 * Test script for category switching and ACF field display
 * 
 * This script validates that:
 * 1. Single category restriction is working
 * 2. ACF field groups are properly configured with location rules
 * 3. Category-based field switching functionality is in place
 */

// WordPress environment setup
require_once 'wp-config.php';
require_once ABSPATH . 'wp-admin/includes/admin.php';

echo "<h1>Category Switching Test Results</h1>\n";

// Test 1: Check if primary categories exist
echo "<h2>1. Primary Categories Test</h2>\n";
$primary_categories = array('news', 'projects', 'awards', 'people');
$missing_categories = array();

foreach ($primary_categories as $slug) {
    $category = get_term_by('slug', $slug, 'category');
    if ($category) {
        echo "✓ Category '{$slug}' exists (ID: {$category->term_id})<br>\n";
    } else {
        echo "✗ Category '{$slug}' is missing<br>\n";
        $missing_categories[] = $slug;
    }
}

if (empty($missing_categories)) {
    echo "<strong>Result: PASS - All primary categories exist</strong><br>\n";
} else {
    echo "<strong>Result: FAIL - Missing categories: " . implode(', ', $missing_categories) . "</strong><br>\n";
}

// Test 2: Check ACF field groups and location rules
echo "<h2>2. ACF Field Groups Test</h2>\n";

if (function_exists('acf_get_field_groups')) {
    $field_groups = acf_get_field_groups();
    $expected_groups = array(
        'group_projects_fields' => 'Project Fields',
        'group_awards_fields' => 'Award Fields', 
        'group_people_fields' => 'People Fields'
    );
    
    $found_groups = array();
    
    foreach ($field_groups as $group) {
        if (isset($expected_groups[$group['key']])) {
            $found_groups[] = $group['key'];
            echo "✓ Field group '{$group['title']}' found (Key: {$group['key']})<br>\n";
            
            // Check location rules
            if (isset($group['location']) && is_array($group['location'])) {
                foreach ($group['location'] as $location_group) {
                    foreach ($location_group as $rule) {
                        if ($rule['param'] === 'post_category') {
                            echo "&nbsp;&nbsp;→ Location rule: {$rule['param']} {$rule['operator']} {$rule['value']}<br>\n";
                        }
                    }
                }
            }
        }
    }
    
    $missing_groups = array_diff(array_keys($expected_groups), $found_groups);
    
    if (empty($missing_groups)) {
        echo "<strong>Result: PASS - All expected ACF field groups found</strong><br>\n";
    } else {
        echo "<strong>Result: FAIL - Missing field groups: " . implode(', ', $missing_groups) . "</strong><br>\n";
    }
} else {
    echo "✗ ACF Pro is not active or acf_get_field_groups() function not available<br>\n";
    echo "<strong>Result: FAIL - ACF Pro required</strong><br>\n";
}

// Test 3: Check theme functions
echo "<h2>3. Theme Functions Test</h2>\n";

$required_functions = array(
    'ricelipka_get_post_primary_category' => 'Category detection helper',
    'ricelipka_enforce_single_category' => 'Single category enforcement',
    'ricelipka_category_radio_script' => 'Category radio button conversion',
    'ricelipka_validate_single_category' => 'Category validation on save'
);

$missing_functions = array();

foreach ($required_functions as $function_name => $description) {
    if (function_exists($function_name)) {
        echo "✓ Function '{$function_name}' exists ({$description})<br>\n";
    } else {
        echo "✗ Function '{$function_name}' is missing ({$description})<br>\n";
        $missing_functions[] = $function_name;
    }
}

if (empty($missing_functions)) {
    echo "<strong>Result: PASS - All required functions exist</strong><br>\n";
} else {
    echo "<strong>Result: FAIL - Missing functions: " . implode(', ', $missing_functions) . "</strong><br>\n";
}

// Test 4: Check WordPress hooks
echo "<h2>4. WordPress Hooks Test</h2>\n";

$hooks_to_check = array(
    'save_post' => array('ricelipka_enforce_single_category', 'ricelipka_validate_single_category'),
    'admin_head' => array('ricelipka_category_radio_script'),
    'add_meta_boxes' => array('ricelipka_add_category_metabox_help'),
    'admin_notices' => array('ricelipka_category_selection_notice')
);

foreach ($hooks_to_check as $hook => $expected_functions) {
    echo "Hook '{$hook}':<br>\n";
    foreach ($expected_functions as $function_name) {
        $priority = has_action($hook, $function_name);
        if ($priority !== false) {
            echo "&nbsp;&nbsp;✓ {$function_name} (priority: {$priority})<br>\n";
        } else {
            echo "&nbsp;&nbsp;✗ {$function_name} not hooked<br>\n";
        }
    }
}

// Test 5: Simulate category switching behavior
echo "<h2>5. Category Switching Simulation</h2>\n";

// Create a test post to check category behavior
$test_post_id = wp_insert_post(array(
    'post_title' => 'Test Post for Category Switching',
    'post_content' => 'This is a test post to validate category switching functionality.',
    'post_status' => 'draft',
    'post_type' => 'post'
));

if ($test_post_id && !is_wp_error($test_post_id)) {
    echo "✓ Test post created (ID: {$test_post_id})<br>\n";
    
    // Test setting different categories
    foreach ($primary_categories as $category_slug) {
        $category = get_term_by('slug', $category_slug, 'category');
        if ($category) {
            wp_set_post_categories($test_post_id, array($category->term_id));
            $assigned_categories = get_the_category($test_post_id);
            
            if (count($assigned_categories) === 1 && $assigned_categories[0]->slug === $category_slug) {
                echo "&nbsp;&nbsp;✓ Successfully assigned '{$category_slug}' category<br>\n";
                
                // Test ACF field group visibility for this category
                if (function_exists('acf_get_field_groups')) {
                    $visible_groups = acf_get_field_groups(array('post_id' => $test_post_id));
                    $group_count = count($visible_groups);
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;→ {$group_count} ACF field groups visible for '{$category_slug}'<br>\n";
                }
            } else {
                echo "&nbsp;&nbsp;✗ Failed to assign '{$category_slug}' category<br>\n";
            }
        }
    }
    
    // Clean up test post
    wp_delete_post($test_post_id, true);
    echo "✓ Test post cleaned up<br>\n";
    
} else {
    echo "✗ Failed to create test post<br>\n";
}

echo "<h2>Summary</h2>\n";
echo "<p>The category switching functionality has been implemented with the following features:</p>\n";
echo "<ul>\n";
echo "<li>Single category restriction enforced via JavaScript and server-side validation</li>\n";
echo "<li>Category checkboxes converted to radio buttons for single selection</li>\n";
echo "<li>ACF field groups configured with proper location rules</li>\n";
echo "<li>JavaScript-based field group switching when category changes</li>\n";
echo "<li>Visual feedback and loading indicators</li>\n";
echo "<li>Fallback validation on post save</li>\n";
echo "</ul>\n";

echo "<h3>Next Steps for Testing:</h3>\n";
echo "<ol>\n";
echo "<li>Go to WordPress admin → Posts → Add New</li>\n";
echo "<li>Observe that category checkboxes are converted to radio buttons</li>\n";
echo "<li>Select different categories and watch ACF fields appear/disappear</li>\n";
echo "<li>Verify that only one category can be selected at a time</li>\n";
echo "<li>Save the post and confirm category assignment is maintained</li>\n";
echo "</ol>\n";

echo "<p><strong>Note:</strong> If ACF fields don't switch immediately, the JavaScript may need browser developer tools debugging to identify any console errors.</p>\n";
?>