<?php
/**
 * Template Name: About Page
 * Template for About page
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main about-page">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('about-content'); ?>>
            
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
            
            <?php
            // Display child pages with full content if any exist
            get_template_part('template-parts/child-pages-display', null, array('page_id' => get_the_ID()));
            ?>
            
        </article>
        
    <?php endwhile; ?>
    
</main>

<?php
get_footer();