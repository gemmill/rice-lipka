<?php
/**
 * Test script for home page layout
 * 
 * This script helps verify that the home page displays correctly
 * with the tagline, projects, and news sections.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    require_once('../../../wp-config.php');
}

echo "<h1>Home Page Layout Test</h1>";

// Test 1: Check if front-page.php exists
echo "<h2>1. Template File Check</h2>";
$front_page_template = get_template_directory() . '/front-page.php';
if (file_exists($front_page_template)) {
    echo "✅ front-page.php exists<br>";
} else {
    echo "❌ front-page.php not found<br>";
}

// Test 2: Check site tagline
echo "<h2>2. Site Tagline</h2>";
$tagline = get_bloginfo('description');
if ($tagline) {
    echo "✅ Site tagline: " . esc_html($tagline) . "<br>";
} else {
    echo "⚠️ No site tagline set, using fallback<br>";
}

// Test 3: Check for projects
echo "<h2>3. Recent Projects</h2>";
$projects_query = new WP_Query(array(
    'post_type' => 'projects',
    'posts_per_page' => 5,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
));

if ($projects_query->have_posts()) {
    echo "✅ Found " . $projects_query->found_posts . " projects<br>";
    echo "<ul>";
    while ($projects_query->have_posts()) {
        $projects_query->the_post();
        echo "<li>" . get_the_title() . " (" . get_the_date() . ")</li>";
    }
    echo "</ul>";
} else {
    echo "❌ No projects found<br>";
}
wp_reset_postdata();

// Test 4: Check for news posts
echo "<h2>4. Recent News</h2>";
$news_query = new WP_Query(array(
    'post_type' => 'post',
    'posts_per_page' => 5,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
));

if ($news_query->have_posts()) {
    echo "✅ Found " . $news_query->found_posts . " news posts<br>";
    echo "<ul>";
    while ($news_query->have_posts()) {
        $news_query->the_post();
        echo "<li>" . get_the_title() . " (" . get_the_date() . ")</li>";
    }
    echo "</ul>";
} else {
    echo "❌ No news posts found<br>";
}
wp_reset_postdata();

// Test 5: Check CSS classes
echo "<h2>5. CSS Classes</h2>";
echo "Home page should have 'home' class in body tag<br>";
echo "Grid layout should display: tagline | projects | news<br>";

echo "<h2>6. Recommendations</h2>";
echo "<ul>";
echo "<li>Set a site tagline in Settings > General > Tagline</li>";
echo "<li>Create some projects using the Projects post type</li>";
echo "<li>Create some news posts using regular Posts</li>";
echo "<li>Visit the home page to see the 3-column layout</li>";
echo "</ul>";

echo "<p><strong>Test completed!</strong> Visit <a href='" . home_url() . "'>your home page</a> to see the results.</p>";
?>