<?php
/**
 * Debug Projects
 * 
 * This file can be accessed directly via browser to check projects
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user has admin privileges
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Projects</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Projects Debug Information</h1>
    
    <?php
    // Check if projects post type exists
    if (post_type_exists('projects')) {
        echo '<div class="success">✓ Projects post type is registered</div>';
    } else {
        echo '<div class="error">✗ Projects post type is NOT registered</div>';
    }
    
    // Get projects count
    $projects_query = new WP_Query(array(
        'post_type' => 'projects',
        'post_status' => 'publish',
        'posts_per_page' => -1
    ));
    
    echo '<div class="info">Total published projects: ' . $projects_query->found_posts . '</div>';
    
    if ($projects_query->have_posts()) {
        echo '<h2>Published Projects:</h2>';
        echo '<ul>';
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            echo '<li><strong>' . get_the_title() . '</strong> (ID: ' . get_the_ID() . ')</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<div class="error">No projects found. You may need to import projects first.</div>';
    }
    
    // Check rewrite rules
    global $wp_rewrite;
    $rules = $wp_rewrite->wp_rewrite_rules();
    
    echo '<h2>Relevant Rewrite Rules:</h2>';
    echo '<pre>';
    foreach ($rules as $pattern => $replacement) {
        if (strpos($pattern, 'work') !== false || strpos($replacement, 'projects') !== false) {
            echo $pattern . ' => ' . $replacement . "\n";
        }
    }
    echo '</pre>';
    
    // Check if archive template exists
    $template_path = get_template_directory() . '/archive-projects.php';
    if (file_exists($template_path)) {
        echo '<div class="success">✓ archive-projects.php template exists</div>';
    } else {
        echo '<div class="error">✗ archive-projects.php template is missing</div>';
    }
    
    // Test URLs
    echo '<h2>Test URLs:</h2>';
    echo '<ul>';
    echo '<li><a href="' . home_url('/work/') . '" target="_blank">' . home_url('/work/') . '</a></li>';
    echo '<li><a href="' . home_url('/work/cultural/') . '" target="_blank">' . home_url('/work/cultural/') . '</a></li>';
    echo '<li><a href="' . get_post_type_archive_link('projects') . '" target="_blank">WordPress archive link</a></li>';
    echo '</ul>';
    ?>
    
    <p><a href="<?php echo admin_url(); ?>">← Back to WordPress Admin</a></p>
</body>
</html>