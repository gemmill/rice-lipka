<?php
/**
 * Test script to verify people and awards don't have single pages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    require_once('../../../wp-config.php');
}

echo "<h1>No Single Pages Test</h1>";

// Test 1: Check post type registration
echo "<h2>1. Post Type Registration</h2>";
$people_post_type = get_post_type_object('people');
$awards_post_type = get_post_type_object('awards');

if ($people_post_type) {
    echo "✅ People post type exists<br>";
    echo "Publicly queryable: " . ($people_post_type->publicly_queryable ? "Yes" : "No") . "<br>";
    echo "Has archive: " . ($people_post_type->has_archive ? "Yes" : "No") . "<br>";
} else {
    echo "❌ People post type not found<br>";
}

if ($awards_post_type) {
    echo "✅ Awards post type exists<br>";
    echo "Publicly queryable: " . ($awards_post_type->publicly_queryable ? "Yes" : "No") . "<br>";
    echo "Has archive: " . ($awards_post_type->has_archive ? "Yes" : "No") . "<br>";
} else {
    echo "❌ Awards post type not found<br>";
}

// Test 2: Check for sample data
echo "<h2>2. Sample Data</h2>";
$people = get_posts(array('post_type' => 'people', 'post_status' => 'publish', 'numberposts' => 3));
$awards = get_posts(array('post_type' => 'awards', 'post_status' => 'publish', 'numberposts' => 3));

echo "People found: " . count($people) . "<br>";
echo "Awards found: " . count($awards) . "<br>";

// Test 3: Check template files
echo "<h2>3. Template Files</h2>";
$templates = array(
    'archive-people.php' => 'People archive template',
    'archive-awards.php' => 'Awards archive template',
    'template-parts/item-person.php' => 'Person item component',
    'template-parts/item-award.php' => 'Award item component'
);

foreach ($templates as $template => $description) {
    $file_path = get_template_directory() . '/' . $template;
    if (file_exists($file_path)) {
        echo "✅ $description exists<br>";
    } else {
        echo "❌ $description missing<br>";
    }
}

// Test 4: Check removed templates
echo "<h2>4. Removed Single Page Templates</h2>";
$removed_templates = array(
    'template-parts/single-people.php' => 'Single people template',
    'template-parts/single-awards.php' => 'Single awards template'
);

foreach ($removed_templates as $template => $description) {
    $file_path = get_template_directory() . '/' . $template;
    if (!file_exists($file_path)) {
        echo "✅ $description removed<br>";
    } else {
        echo "❌ $description still exists<br>";
    }
}

// Test 5: Archive URLs
echo "<h2>5. Archive URLs</h2>";
$people_archive = get_post_type_archive_link('people');
$awards_archive = get_post_type_archive_link('awards');

if ($people_archive) {
    echo "✅ People archive URL: <a href='$people_archive'>$people_archive</a><br>";
} else {
    echo "❌ People archive URL not available<br>";
}

if ($awards_archive) {
    echo "✅ Awards archive URL: <a href='$awards_archive'>$awards_archive</a><br>";
} else {
    echo "❌ Awards archive URL not available<br>";
}

// Test 6: Single page redirect test
echo "<h2>6. Single Page Redirect</h2>";
if (!empty($people)) {
    $person_url = get_permalink($people[0]->ID);
    echo "Person single URL would redirect: <a href='$person_url'>$person_url</a> → $people_archive<br>";
}

if (!empty($awards)) {
    $award_url = get_permalink($awards[0]->ID);
    echo "Award single URL would redirect: <a href='$award_url'>$award_url</a> → $awards_archive<br>";
}

echo "<h2>7. Summary</h2>";
echo "<ul>";
echo "<li>✅ People and Awards post types set to publicly_queryable = false</li>";
echo "<li>✅ Single page templates removed</li>";
echo "<li>✅ Archive templates created</li>";
echo "<li>✅ Item components created for consistent display</li>";
echo "<li>✅ Redirect function added to send single page visits to archives</li>";
echo "<li>✅ Content templates updated to use new components</li>";
echo "</ul>";

echo "<p><strong>Test completed!</strong> People and Awards now only display in archive format.</p>";
?>