<?php
/**
 * Template Name: About Page
 * Template for About page
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>
    
    <div class="grid">
    <?php while (have_posts()) : the_post(); ?>
        
       
            <?php the_content(); ?>
       
            <?php
            // Display child pages with full content if any exist
            get_template_part('template-parts/child-pages-display', null, array('page_id' => get_the_ID()));
            ?>
            

    <?php endwhile; ?>
</div>
</div>

<?php
get_footer();