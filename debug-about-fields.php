<?php
/**
 * Debug script for About page ACF fields
 * 
 * Add ?debug_about_fields=1 to any page URL to see debug info
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
 * Debug About page ACF field group
 */
function debug_about_acf_fields() {
    if (!isset($_GET['debug_about_fields']) || !current_user_can('manage_options')) {
        return;
    }
    
    echo "<div style='background: white; padding: 20px; margin: 20px; border: 1px solid #ccc; font-family: Arial, sans-serif;'>";
    echo "<h2>About Page ACF Field Group Debug</h2>";
    
    // Check if ACF Pro is active
    if (!function_exists('acf_get_field_groups')) {
        echo "<p>❌ ACF Pro is not active</p>";
        echo "</div>";
        return;
    }
    
    echo "<p>✅ ACF Pro is active</p>";
    
    // Get all field groups
    $field_groups = acf_get_field_groups();
    echo "<h3>All Field Groups:</h3>";
    foreach ($field_groups as $group) {
        echo "<p>- {$group['title']} (key: {$group['key']})</p>";
    }
    
    // Check for About page field group specifically
    $about_group = null;
    foreach ($field_groups as $group) {
        if ($group['key'] === 'group_about_page_fields') {
            $about_group = $group;
            break;
        }
    }
    
    if ($about_group) {
        echo "<h3>✅ About Page Field Group Found:</h3>";
        echo "<p>Title: {$about_group['title']}</p>";
        echo "<p>Key: {$about_group['key']}</p>";
        echo "<p>Active: " . ($about_group['active'] ? 'Yes' : 'No') . "</p>";
        
        echo "<h4>Location Rules:</h4>";
        echo "<pre>" . print_r($about_group['location'], true) . "</pre>";
        
        // Get fields
        $fields = acf_get_fields($about_group);
        echo "<h4>Fields:</h4>";
        foreach ($fields as $field) {
            echo "<p>- {$field['label']} ({$field['name']}) - Type: {$field['type']}</p>";
        }
    } else {
        echo "<h3>❌ About Page Field Group NOT Found</h3>";
    }
    
    // Check current page info
    global $post;
    if ($post && $post->post_type === 'page') {
        echo "<h3>Current Page Info:</h3>";
        echo "<p>ID: {$post->ID}</p>";
        echo "<p>Slug: {$post->post_name}</p>";
        echo "<p>Template: " . get_page_template_slug($post->ID) . "</p>";
        
        // Check if About page exists
        $about_page = get_page_by_path('about');
        if ($about_page) {
            echo "<p>About page exists - ID: {$about_page->ID}</p>";
            echo "<p>Is this the About page? " . ($post->ID == $about_page->ID ? 'Yes' : 'No') . "</p>";
        } else {
            echo "<p>❌ About page does not exist</p>";
        }
    }
    
    echo "</div>";
}
add_action('wp_footer', 'debug_about_acf_fields');
?>