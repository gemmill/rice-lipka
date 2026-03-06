<?php
/**
 * Debug Awards Archive Issues
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h1>Awards Archive Debug</h1>\n";

// Check if awards post type exists
$post_types = get_post_types(array('public' => true), 'objects');
echo "<h2>Registered Post Types:</h2>\n";
foreach ($post_types as $post_type) {
    echo "- " . $post_type->name . " (has_archive: " . ($post_type->has_archive ? 'yes' : 'no') . ")\n";
}

// Check awards post type specifically
$awards_post_type = get_post_type_object('awards');
if ($awards_post_type) {
    echo "\n<h2>Awards Post Type Details:</h2>\n";
    echo "- Name: " . $awards_post_type->name . "\n";
    echo "- Public: " . ($awards_post_type->public ? 'yes' : 'no') . "\n";
    echo "- Has Archive: " . ($awards_post_type->has_archive ? 'yes' : 'no') . "\n";
    echo "- Publicly Queryable: " . ($awards_post_type->publicly_queryable ? 'yes' : 'no') . "\n";
    echo "- Rewrite: " . print_r($awards_post_type->rewrite, true) . "\n";
} else {
    echo "\n<h2>ERROR: Awards post type not found!</h2>\n";
}

// Check if there are any awards posts
$awards_count = wp_count_posts('awards');
echo "\n<h2>Awards Posts Count:</h2>\n";
echo "- Published: " . $awards_count->publish . "\n";
echo "- Draft: " . $awards_count->draft . "\n";

// Test the archive URL
$archive_url = get_post_type_archive_link('awards');
echo "\n<h2>Archive URL:</h2>\n";
echo "- WordPress thinks the archive URL is: " . ($archive_url ? $archive_url : 'NULL') . "\n";

// Check rewrite rules
$rewrite_rules = get_option('rewrite_rules');
echo "\n<h2>Relevant Rewrite Rules:</h2>\n";
foreach ($rewrite_rules as $pattern => $replacement) {
    if (strpos($pattern, 'awards') !== false || strpos($replacement, 'awards') !== false) {
        echo "- " . $pattern . " => " . $replacement . "\n";
    }
}

// Test query
echo "\n<h2>Test Query:</h2>\n";
$test_query = new WP_Query(array(
    'post_type' => 'awards',
    'posts_per_page' => 1
));
echo "- Found " . $test_query->found_posts . " awards posts\n";

if ($test_query->have_posts()) {
    while ($test_query->have_posts()) {
        $test_query->the_post();
        echo "- Sample award: " . get_the_title() . "\n";
    }
    wp_reset_postdata();
}
?>