<?php
/**
 * Flush Rewrite Rules Script
 * 
 * Run this script to flush WordPress rewrite rules and ensure
 * custom post type archives work properly.
 */

// Load WordPress
require_once('../../../wp-config.php');
require_once('../../../wp-load.php');

// Flush rewrite rules
flush_rewrite_rules();

echo "Rewrite rules flushed successfully!\n";
echo "People archive should now be accessible at: " . home_url('/people/') . "\n";
echo "Awards archive should be accessible at: " . home_url('/awards/') . "\n";
?>