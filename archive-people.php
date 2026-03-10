<?php
/**
 * People Archive Template
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
                <?php get_template_part('template-parts/item-person', null); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="no-people">
                <h2><?php _e('No people found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No people have been added yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (have_posts()) : ?>
        <div class="pagination-wrapper">
            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('Previous', 'ricelipka-theme'),
                'next_text' => __('Next', 'ricelipka-theme'),
            ));
            ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>