<?php
/**
 * Projects Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>
    
    <div class="grid">
       
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="masonry-item">
                        <?php get_template_part('template-parts/item-project'); ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
       
        
       
    </div>
</div>

<?php get_footer(); ?>