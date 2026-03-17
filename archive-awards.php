<?php
/**
 * Awards Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>
    
    <div class="grid">
        <div id="awards-masonry" class="masonry">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="masonry-item">
                        <?php get_template_part('template-parts/item-award', null, array(
                            'class' => 'award-card',
                            'layout' => 'default',
                            'image_size' => 'medium',
                            'show_meta' => true,
                            'show_excerpt' => true
                        )); ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        
        <div id="awards-loading" class="awards-loading" style="display: none;">
            <p>Loading more awards...</p>
        </div>
        
        <?php if (!have_posts()) : ?>
            <div class="no-awards">
                <h2><?php _e('No awards found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No awards have been added yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>