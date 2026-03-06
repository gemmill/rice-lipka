<?php
/**
 * Import Awards from CSV
 * 
 * This script imports awards data from awards-import.csv into the WordPress awards custom post type
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user has admin privileges
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator to run this script.');
}

echo "<h1>Awards Import Script</h1>\n";

// Check if CSV file exists
$csv_file = 'awards-import.csv';
if (!file_exists($csv_file)) {
    die("Error: {$csv_file} not found!");
}

// Read CSV file
$csv_data = array_map('str_getcsv', file($csv_file));
$headers = array_shift($csv_data); // Remove header row

echo "<h2>Starting Import...</h2>\n";
echo "<p>Found " . count($csv_data) . " awards to import.</p>\n";

$imported = 0;
$skipped = 0;

foreach ($csv_data as $row) {
    $award_data = array_combine($headers, $row);
    
    // Clean up the data
    $title = trim($award_data['title'], '"');
    $date_received = $award_data['date_received'];
    $project_name = trim($award_data['project_name'], '"');
    $awarding_organization = trim($award_data['awarding_organization'], '"');
    
    // Check if award already exists (by title and date)
    $existing = get_posts(array(
        'post_type' => 'awards',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'date_received',
                'value' => $date_received,
                'compare' => '='
            )
        ),
        'title' => $title,
        'post_status' => 'any'
    ));
    
    if (!empty($existing)) {
        echo "<p>Skipped: {$title} ({$date_received}) - already exists</p>\n";
        $skipped++;
        continue;
    }
    
    // Create the award post
    $post_data = array(
        'post_title' => $title,
        'post_type' => 'awards',
        'post_status' => 'publish',
        'post_content' => '', // Awards don't need content
    );
    
    $post_id = wp_insert_post($post_data);
    
    if ($post_id && !is_wp_error($post_id)) {
        // Add custom fields
        update_field('awarding_organization', $awarding_organization, $post_id);
        update_field('date_received', $date_received . '-01-01', $post_id); // Add day/month for date field
        
        // Handle project association
        if (!empty($project_name)) {
            // Try to find matching project
            $projects = get_posts(array(
                'post_type' => 'projects',
                's' => $project_name,
                'posts_per_page' => 1
            ));
            
            if (!empty($projects)) {
                update_field('associated_project', $projects[0]->ID, $post_id);
            } else {
                // Store as text if no matching project found
                update_field('project_name_text', $project_name, $post_id);
            }
        }
        
        echo "<p>✓ Imported: {$title} ({$date_received})</p>\n";
        $imported++;
    } else {
        echo "<p>✗ Failed to import: {$title}</p>\n";
    }
}

echo "<h2>Import Complete!</h2>\n";
echo "<p>Imported: {$imported} awards</p>\n";
echo "<p>Skipped: {$skipped} awards (already existed)</p>\n";
echo "<p><a href='/awards/'>View Awards Archive</a></p>\n";
?>