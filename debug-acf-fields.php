<?php
/**
 * Debug ACF Field Groups and Location Rules
 * 
 * Add this to your WordPress admin by visiting:
 * /wp-admin/admin.php?page=debug-acf-fields
 */

// Add admin menu item
add_action('admin_menu', function() {
    add_management_page(
        'Debug ACF Fields',
        'Debug ACF Fields', 
        'manage_options',
        'debug-acf-fields',
        'debug_acf_fields_page'
    );
});

function debug_acf_fields_page() {
    if (!function_exists('acf_get_field_groups')) {
        echo '<div class="wrap"><h1>ACF Pro Not Active</h1><p>ACF Pro is required for this debug tool.</p></div>';
        return;
    }
    
    echo '<div class="wrap">';
    echo '<h1>ACF Field Groups Debug</h1>';
    
    // Get all field groups
    $field_groups = acf_get_field_groups();
    
    echo '<h2>All Field Groups (' . count($field_groups) . ')</h2>';
    
    foreach ($field_groups as $group) {
        echo '<div style="border: 1px solid #ccc; margin: 10px 0; padding: 15px; background: #f9f9f9;">';
        echo '<h3>' . esc_html($group['title']) . '</h3>';
        echo '<p><strong>Key:</strong> ' . esc_html($group['key']) . '</p>';
        echo '<p><strong>Active:</strong> ' . ($group['active'] ? 'Yes' : 'No') . '</p>';
        
        if (isset($group['location']) && is_array($group['location'])) {
            echo '<h4>Location Rules:</h4>';
            echo '<ul>';
            foreach ($group['location'] as $location_group) {
                echo '<li><strong>Rule Group:</strong><ul>';
                foreach ($location_group as $rule) {
                    echo '<li>' . esc_html($rule['param']) . ' ' . esc_html($rule['operator']) . ' ' . esc_html($rule['value']) . '</li>';
                }
                echo '</ul></li>';
            }
            echo '</ul>';
        }
        
        // Get fields in this group
        $fields = acf_get_fields($group['key']);
        if ($fields) {
            echo '<h4>Fields (' . count($fields) . '):</h4>';
            echo '<ul>';
            foreach ($fields as $field) {
                echo '<li>' . esc_html($field['label']) . ' (' . esc_html($field['name']) . ') - Type: ' . esc_html($field['type']) . '</li>';
            }
            echo '</ul>';
        }
        
        echo '</div>';
    }
    
    // Test with sample post IDs
    echo '<h2>Field Group Visibility Test</h2>';
    
    $test_categories = array('news', 'projects', 'awards', 'people');
    
    foreach ($test_categories as $cat_slug) {
        $category = get_term_by('slug', $cat_slug, 'category');
        if (!$category) continue;
        
        echo '<h3>Category: ' . esc_html($category->name) . '</h3>';
        
        // Create a temporary post to test visibility
        $temp_post = array(
            'post_title' => 'Test Post',
            'post_content' => 'Test content',
            'post_status' => 'draft',
            'post_type' => 'post'
        );
        
        $temp_post_id = wp_insert_post($temp_post);
        
        if ($temp_post_id && !is_wp_error($temp_post_id)) {
            // Assign category
            wp_set_post_categories($temp_post_id, array($category->term_id));
            
            // Get visible field groups for this post
            $visible_groups = acf_get_field_groups(array('post_id' => $temp_post_id));
            
            echo '<p><strong>Visible field groups for ' . esc_html($cat_slug) . ' category:</strong></p>';
            if (empty($visible_groups)) {
                echo '<p>No field groups visible (this is correct for News category)</p>';
            } else {
                echo '<ul>';
                foreach ($visible_groups as $group) {
                    echo '<li>' . esc_html($group['title']) . ' (' . esc_html($group['key']) . ')</li>';
                }
                echo '</ul>';
            }
            
            // Clean up
            wp_delete_post($temp_post_id, true);
        }
    }
    
    echo '<h2>JavaScript Debug Info</h2>';
    echo '<p>Open your browser\'s developer console (F12) when editing a post to see debug messages.</p>';
    echo '<p>The JavaScript will log:</p>';
    echo '<ul>';
    echo '<li>When category radio script initializes</li>';
    echo '<li>When categories are converted to radio buttons</li>';
    echo '<li>When category selection changes</li>';
    echo '<li>ACF field group information</li>';
    echo '</ul>';
    
    echo '</div>';
}
?>