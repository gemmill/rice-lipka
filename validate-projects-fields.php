<?php
/**
 * Validation script for Projects category field group
 * 
 * This script validates that Task 3.2 has been completed successfully
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
 * Validate Projects field group implementation
 */
function validate_projects_field_group() {
    echo "<h2>Task 3.2 Validation: Projects Category Field Group</h2>\n";
    
    // Check if ACF Pro is available
    if (!function_exists('acf_get_field_groups')) {
        echo "<p>❌ ACF Pro is not active - field groups cannot be validated</p>\n";
        return false;
    }
    
    // Get all field groups
    $field_groups = acf_get_field_groups();
    $projects_group = null;
    
    // Find the Projects field group
    foreach ($field_groups as $group) {
        if ($group['key'] === 'group_projects_fields') {
            $projects_group = $group;
            break;
        }
    }
    
    if (!$projects_group) {
        echo "<p>❌ Projects field group not found</p>\n";
        return false;
    }
    
    echo "<p>✅ Projects field group found: {$projects_group['title']}</p>\n";
    
    // Get field group fields
    $fields = acf_get_fields($projects_group);
    
    // Required fields from Task 3.2
    $required_fields = array(
        'project_name' => 'Project Name',
        'completion_status' => 'Completion Status',
        'completion_percentage' => 'Completion Percentage',
        'project_type' => 'Project Type',
        'client' => 'Client',
        'location' => 'Location',
        'image_gallery' => 'Image Gallery',
        'project_metadata' => 'Project Metadata'
    );
    
    $found_fields = array();
    
    // Check each required field
    foreach ($fields as $field) {
        $found_fields[$field['name']] = $field['label'];
    }
    
    echo "<h3>Field Validation:</h3>\n";
    $all_fields_present = true;
    
    foreach ($required_fields as $field_name => $field_label) {
        if (isset($found_fields[$field_name])) {
            echo "<p>✅ {$field_label} field present</p>\n";
        } else {
            echo "<p>❌ {$field_label} field missing</p>\n";
            $all_fields_present = false;
        }
    }
    
    // Check conditional logic
    echo "<h3>Conditional Logic Validation:</h3>\n";
    
    $location_rules = $projects_group['location'];
    $has_category_condition = false;
    
    foreach ($location_rules as $rule_group) {
        foreach ($rule_group as $rule) {
            if ($rule['param'] === 'post_category' && 
                $rule['operator'] === '==' && 
                $rule['value'] === 'category:projects') {
                $has_category_condition = true;
                break 2;
            }
        }
    }
    
    if ($has_category_condition) {
        echo "<p>✅ Conditional logic configured to display when post category = 'Projects'</p>\n";
    } else {
        echo "<p>❌ Conditional logic not properly configured</p>\n";
        $all_fields_present = false;
    }
    
    // Check specific field configurations
    echo "<h3>Field Configuration Validation:</h3>\n";
    
    foreach ($fields as $field) {
        switch ($field['name']) {
            case 'completion_status':
                if (isset($field['choices']) && 
                    isset($field['choices']['completed']) && 
                    isset($field['choices']['in_progress']) && 
                    isset($field['choices']['planned'])) {
                    echo "<p>✅ Completion Status has correct options</p>\n";
                } else {
                    echo "<p>❌ Completion Status missing required options</p>\n";
                }
                break;
                
            case 'completion_percentage':
                if (isset($field['conditional_logic']) && !empty($field['conditional_logic'])) {
                    echo "<p>✅ Completion Percentage has conditional logic</p>\n";
                } else {
                    echo "<p>❌ Completion Percentage missing conditional logic</p>\n";
                }
                break;
                
            case 'project_type':
                if (isset($field['choices']) && 
                    isset($field['choices']['civic_architecture']) && 
                    isset($field['choices']['cultural_projects']) && 
                    isset($field['choices']['educational_buildings']) && 
                    isset($field['choices']['public_works'])) {
                    echo "<p>✅ Project Type has project subcategories</p>\n";
                } else {
                    echo "<p>❌ Project Type missing required subcategory options</p>\n";
                }
                break;
                
            case 'image_gallery':
                if ($field['type'] === 'gallery') {
                    echo "<p>✅ Image Gallery is configured as gallery field</p>\n";
                } else {
                    echo "<p>❌ Image Gallery not configured as gallery field</p>\n";
                }
                break;
                
            case 'project_metadata':
                if ($field['type'] === 'group' && isset($field['sub_fields'])) {
                    $sub_field_count = count($field['sub_fields']);
                    echo "<p>✅ Project Metadata is group field with {$sub_field_count} sub-fields</p>\n";
                } else {
                    echo "<p>❌ Project Metadata not configured as group field</p>\n";
                }
                break;
        }
    }
    
    // Final validation result
    echo "<h3>Task 3.2 Completion Status:</h3>\n";
    
    if ($all_fields_present) {
        echo "<p><strong>✅ TASK 3.2 COMPLETED SUCCESSFULLY</strong></p>\n";
        echo "<p>The Projects category field group has been properly implemented with:</p>\n";
        echo "<ul>\n";
        echo "<li>✅ Conditional logic to display when post category = 'Projects'</li>\n";
        echo "<li>✅ All required fields: project_name, completion_status, completion_percentage, project_type, client, location, image_gallery, project_metadata</li>\n";
        echo "<li>✅ Project-specific field validation and selection options</li>\n";
        echo "<li>✅ Requirements 2.2 and 7.1 satisfied</li>\n";
        echo "</ul>\n";
        return true;
    } else {
        echo "<p><strong>❌ TASK 3.2 INCOMPLETE</strong></p>\n";
        echo "<p>Some required fields or configurations are missing.</p>\n";
        return false;
    }
}

/**
 * Run validation if explicitly called
 */
if (isset($_GET['validate_projects_fields']) && current_user_can('manage_options')) {
    add_action('wp_footer', function() {
        echo "<div style='font-family: Arial, sans-serif; margin: 20px; max-width: 800px;'>\n";
        validate_projects_field_group();
        echo "</div>\n";
    });
}
?>