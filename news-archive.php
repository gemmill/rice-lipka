<?php
/**
 * News Archive Template
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
                    <?php get_template_part('template-parts/item-news'); ?>
                <?php endwhile; ?>
         

            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('Previous', 'ricelipka-theme'),
                'next_text' => __('Next', 'ricelipka-theme'),
            ));
            ?>

        <?php else : ?>
            <div class="no-news">
                <h2><?php _e('No news found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No news articles have been published yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>