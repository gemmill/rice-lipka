<?php
/**
 * Flush Rewrite Rules Helper
 * 
 * This file can be accessed directly via browser to flush rewrite rules
 * Use this after adding new rewrite rules or when URLs aren't working
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

// Flush rewrite rules
flush_rewrite_rules();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Rewrite Rules Flushed</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; text-align: center; }
        .success { background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .button { background: #0073aa; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; }
        .button:hover { background: #005a87; }
    </style>
</head>
<body>
    <h1>Rewrite Rules Flushed Successfully</h1>
    
    <div class="success">
        <p><strong>Success!</strong> WordPress rewrite rules have been flushed.</p>
        <p>Your custom URLs should now work properly:</p>
        <ul style="text-align: left; display: inline-block;">
            <li><code>/work/</code> - All projects</li>
            <li><code>/work/cultural/</code> - Cultural projects</li>
            <li><code>/work/academic/</code> - Academic projects</li>
            <li><code>/work/offices/</code> - Office projects</li>
            <li><code>/work/retail_commercial/</code> - Retail & Commercial projects</li>
            <li><code>/work/institutional/</code> - Institutional projects</li>
            <li><code>/work/planning/</code> - Planning projects</li>
            <li><code>/work/exhibitions/</code> - Exhibition projects</li>
            <li><code>/work/research_installation/</code> - Research & Installation projects</li>
        </ul>
    </div>
    
    <p>
        <a href="<?php echo home_url('/work/'); ?>" class="button">View Projects Archive</a>
        <a href="<?php echo admin_url(); ?>" class="button">Back to WordPress Admin</a>
    </p>
</body>
</html>