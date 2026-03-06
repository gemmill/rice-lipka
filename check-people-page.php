<?php
/**
 * Check if there's a people page conflicting with the archive
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h1>People Page/Archive Check</h1>\n";

// Check if there's a page with slug 'people'
$people_page = get_page_by_path('people');
if ($people_page) {
    echo "<h2>Found People Page:</h2>\n";
    echo "- ID: " . $people_page->ID . "\n";
    echo "- Title: " . $people_page->post_title . "\n";
    echo "- Slug: " . $people_page->post_name . "\n";
    echo "- Status: " . $people_page->post_status . "\n";
    echo "- URL: " . get_permalink($people_page->ID) . "\n";
} else {
    echo "<h2>No People Page Found</h2>\n";
}

// Check people archive URL
$people_archive_url = get_post_type_archive_link('people');
echo "\n<h2>People Archive:</h2>\n";
echo "- Archive URL: " . ($people_archive_url ? $people_archive_url : 'NULL') . "\n";

// Test what template would be used
echo "\n<h2>Template Hierarchy Test:</h2>\n";
echo "- Current URL /people/ would use: ";

// Simulate the template hierarchy
if ($people_page) {
    echo "page-people.php (page template takes priority)\n";
} else {
    echo "archive-people.php (custom post type archive)\n";
}
?>