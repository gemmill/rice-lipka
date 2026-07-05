<?php
/**
 * Lectures Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>

    <div class="grid">
        <div id="lectures-masonry" class="masonry">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="masonry-item">
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-item lecture-item'); ?>>
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                            </header>

                            <div class="entry-meta">
                                <?php $lecture_date = get_field('date'); ?>
                                <?php $lecture_category = get_field('category'); ?>

                                <?php if ($lecture_date) : ?>
                                    <p class="lecture-date"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($lecture_date))); ?></p>
                                <?php endif; ?>

                                <?php if ($lecture_category) : ?>
                                    <p class="lecture-category"><?php echo esc_html($lecture_category); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <?php if (!have_posts()) : ?>
            <div class="no-lectures">
                <h2><?php _e('No lectures found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No lectures have been added yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
