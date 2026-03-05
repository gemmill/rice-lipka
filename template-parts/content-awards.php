<?php
/**
 * Template part for displaying award posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Use the award item component
get_template_part('template-parts/item-award', null, array(
    'class' => 'award-item post-item',
    'layout' => 'default',
    'image_size' => 'medium',
    'show_meta' => true,
    'show_excerpt' => true
));
?>