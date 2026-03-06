<?php
/**
 * Flush WordPress rewrite rules
 * Run this file once to update permalink structure for custom post types
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user has admin privileges
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator to run this script.');
}

// Flush rewrite rules
flush_rewrite_rules();

echo "Rewrite rules have been flushed successfully!\n";
echo "The /people/ and /awards/ URLs should now work properly.\n";
echo "You can delete this file after running it.\n";
?>