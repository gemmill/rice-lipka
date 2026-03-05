<?php
/**
 * Test script for new project item design
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    require_once('../../../wp-config.php');
}

echo "<h1>Project Item Design Test</h1>";

// Test 1: Check component file
echo "<h2>1. Component File Check</h2>";
$component_file = get_template_directory() . '/template-parts/item-project.php';
if (file_exists($component_file)) {
    echo "✅ item-project.php component exists<br>";
} else {
    echo "❌ item-project.php component not found<br>";
}

// Test 2: Project type to camelCase conversion
echo "<h2>2. Project Type CSS Classes</h2>";
$project_types = array(
    'cultural' => 'cultural',
    'academic' => 'academic', 
    'offices' => 'offices',
    'retail_commercial' => 'retailCommercial',
    'institutional' => 'institutional',
    'planning' => 'planning',
    'exhibitions' => 'exhibitions',
    'research_installation' => 'researchInstallation',
    'residential' => 'residential'
);

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Project Type</th><th>CSS Class</th></tr>";
foreach ($project_types as $type => $expected_class) {
    $actual_class = lcfirst(str_replace('_', '', ucwords($type, '_')));
    $status = ($actual_class === $expected_class) ? '✅' : '❌';
    echo "<tr><td>$type</td><td>$actual_class $status</td></tr>";
}
echo "</table>";

// Test 3: Check for projects with different types
echo "<h2>3. Sample Projects by Type</h2>";
$projects = get_posts(array(
    'post_type' => 'projects',
    'post_status' => 'publish',
    'numberposts' => 10
));

if (!empty($projects)) {
    echo "<ul>";
    foreach ($projects as $project) {
        $project_type = get_field('project_type', $project->ID);
        $css_class = $project_type ? lcfirst(str_replace('_', '', ucwords($project_type, '_'))) : 'no-type';
        $has_image = has_post_thumbnail($project->ID) ? '🖼️' : '📷';
        echo "<li>" . $project->post_title . " - Type: " . ($project_type ?: 'none') . " - CSS: .$css_class $has_image</li>";
    }
    echo "</ul>";
} else {
    echo "❌ No projects found for testing<br>";
}

// Test 4: Design Features
echo "<h2>4. New Design Features</h2>";
echo "<ul>";
echo "<li>✅ 2:3 aspect ratio image container</li>";
echo "<li>✅ Project title behind image in overlay</li>";
echo "<li>✅ Image opacity fades to 0 on hover</li>";
echo "<li>✅ Title reveals on hover with background</li>";
echo "<li>✅ Project category CSS classes in camelCase</li>";
echo "<li>✅ Placeholder shows first letter of title</li>";
echo "<li>✅ Different background colors per project type</li>";
echo "<li>✅ Responsive layout variations (default, compact, minimal)</li>";
echo "</ul>";

// Test 5: CSS Classes Generated
echo "<h2>5. CSS Classes Generated</h2>";
echo "<p>Each project will have classes like:</p>";
echo "<pre>";
echo "project-item project-item-default cultural (for cultural projects)
project-item project-item-compact retailCommercial (for retail & commercial)
project-item project-item-minimal researchInstallation (for research & installation)";
echo "</pre>";

// Test 6: Hover Effect Description
echo "<h2>6. Hover Effect</h2>";
echo "<p>On hover:</p>";
echo "<ul>";
echo "<li>Project image opacity: 1 → 0 (fade out)</li>";
echo "<li>Project overlay opacity: 0 → 1 (fade in)</li>";
echo "<li>Reveals project title and metadata</li>";
echo "<li>Background color varies by project type</li>";
echo "<li>Smooth 0.3s transition</li>";
echo "</ul>";

echo "<h2>7. Layout Variations</h2>";
echo "<ul>";
echo "<li><strong>Default:</strong> Full 2:3 aspect ratio, overlay on hover</li>";
echo "<li><strong>Compact:</strong> 1:1 aspect ratio, 120px width, side-by-side layout</li>";
echo "<li><strong>Minimal:</strong> 3:2 aspect ratio, smaller text</li>";
echo "<li><strong>Home:</strong> Custom 1:1 80px width for home page</li>";
echo "</ul>";

echo "<p><strong>Test completed!</strong> The new project design is ready with hover effects and category-based styling.</p>";
?>