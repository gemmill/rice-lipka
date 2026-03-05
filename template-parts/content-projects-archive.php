<?php
/**
 * Template part for displaying projects in archive view
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Use the project item component with archive-specific settings
get_template_part('template-parts/item-project', null, array(
    'class' => 'project-card',
    'layout' => 'default',
    'image_size' => 'medium'
));
?>