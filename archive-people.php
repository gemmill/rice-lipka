<?php
/**
 * People Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

        <header class="page-header">
            <h1 class="page-title">People</h1>
            <div class="archive-description">
                <p>Team members and collaborators</p>
            </div>
        </header>

        <?php if (have_posts()) : ?>
            <div class="people-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/item-person', null, array(
                        'class' => 'person-card',
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
            <div class="no-people">
                <h2><?php _e('No people found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No team members have been added yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>

<?php get_footer(); ?>