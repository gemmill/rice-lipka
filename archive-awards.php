<?php
/**
 * Awards Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

        <header class="page-header">
            <h1 class="page-title">Awards</h1>
            <div class="archive-description">
                <p>Recognition and achievements</p>
            </div>
        </header>

        <?php if (have_posts()) : ?>
            <div class="awards-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/item-award', null, array(
                        'class' => 'award-card',
                        'layout' => 'default',
                        'image_size' => 'medium',
                        'show_meta' => true,
                        'show_excerpt' => true
                    )); ?>
                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('Previous', 'ricelipka-theme'),
                'next_text' => __('Next', 'ricelipka-theme'),
            ));
            ?>

        <?php else : ?>
            <div class="no-awards">
                <h2><?php _e('No awards found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No awards have been added yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>

<?php get_footer(); ?>