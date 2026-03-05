<?php
/**
 * Debug script for home page projects
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    require_once('../../../wp-config.php');
}

echo "<h1>Debug Home Page Projects</h1>";

// Check if projects post type is registered
echo "<h2>1. Post Type Registration</h2>";
$post_types = get_post_types(array('public' => true), 'names');
if (in_array('projects', $post_types)) {
    echo "✅ Projects post type is registered<br>";
} else {
    echo "❌ Projects post type is NOT registered<br>";
    echo "Available post types: " . implode(', ', $post_types) . "<br>";
}

// Check for projects in database
echo "<h2>2. Projects in Database</h2>";
$all_projects = get_posts(array(
    'post_type' => 'projects',
    'post_status' => array('publish', 'draft', 'private'),
    'numberposts' => -1
));

echo "Total projects found: " . count($all_projects) . "<br>";

if (!empty($all_projects)) {
    echo "<h3>All Projects:</h3>";
    echo "<ul>";
    foreach ($all_projects as $project) {
        echo "<li>" . $project->post_title . " (Status: " . $project->post_status . ", Date: " . $project->post_date . ")</li>";
    }
    echo "</ul>";
} else {
    echo "❌ No projects found in database<br>";
}

// Test the exact query from front-page.php
echo "<h2>3. Front Page Query Test</h2>";
$projects_query = new WP_Query(array(
    'post_type' => 'projects',
    'posts_per_page' => 5,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
));

echo "Query found " . $projects_query->found_posts . " published projects<br>";

if ($projects_query->have_posts()) {
    echo "<h3>Published Projects (Front Page Query):</h3>";
    echo "<ul>";
    while ($projects_query->have_posts()) {
        $projects_query->the_post();
        echo "<li>" . get_the_title() . " (Date: " . get_the_date() . ")</li>";
    }
    echo "</ul>";
} else {
    echo "❌ No published projects found<br>";
}
wp_reset_postdata();

// Check if we have imported projects
echo "<h2>4. Check for Imported Projects</h2>";
$imported_projects = get_posts(array(
    'post_type' => 'projects',
    'meta_key' => 'imported_from_ricelipka',
    'meta_value' => 'yes',
    'post_status' => 'any',
    'numberposts' => -1
));

echo "Imported projects found: " . count($imported_projects) . "<br>";

if (!empty($imported_projects)) {
    echo "<h3>Imported Projects Status:</h3>";
    echo "<ul>";
    foreach ($imported_projects as $project) {
        echo "<li>" . $project->post_title . " (Status: " . $project->post_status . ")</li>";
    }
    echo "</ul>";
}

// Check project fields
echo "<h2>5. Project Fields Check</h2>";
if (!empty($all_projects)) {
    $sample_project = $all_projects[0];
    echo "Sample project: " . $sample_project->post_title . "<br>";
    
    $project_type = get_field('project_type', $sample_project->ID);
    $project_year = get_field('project_year', $sample_project->ID);
    
    echo "Project type: " . ($project_type ? $project_type : 'Not set') . "<br>";
    echo "Project year: " . ($project_year ? $project_year : 'Not set') . "<br>";
    
    if (has_post_thumbnail($sample_project->ID)) {
        echo "✅ Has featured image<br>";
    } else {
        echo "❌ No featured image<br>";
    }
}

echo "<h2>6. Recommendations</h2>";
echo "<ul>";
if (empty($all_projects)) {
    echo "<li>❌ No projects found. You need to create or import projects.</li>";
    echo "<li>Check if the import script ran successfully</li>";
    echo "<li>Try creating a test project manually</li>";
} else {
    $published_count = count(array_filter($all_projects, function($p) { return $p->post_status === 'publish'; }));
    if ($published_count === 0) {
        echo "<li>❌ Projects exist but none are published. Publish some projects.</li>";
    } else {
        echo "<li>✅ Published projects exist. Check front-page.php template.</li>";
    }
}
echo "</ul>";

echo "<p><a href='" . admin_url('edit.php?post_type=projects') . "'>View Projects in Admin</a></p>";
?>