<?php
/**
 * Template part for displaying people posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Use the person item component
get_template_part('template-parts/item-person', null, array(
    'class' => 'person-item post-item',
    'layout' => 'default',
    'image_size' => 'medium',
    'show_meta' => true,
    'show_excerpt' => true
));
?>