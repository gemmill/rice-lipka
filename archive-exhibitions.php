<?php
/**
 * Exhibitions Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>

    <div class="grid">
        <div id="exhibitions-masonry" class="masonry">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="masonry-item">
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-item exhibition-item'); ?>>
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                            </header>

                            <div class="entry-meta">
                                <?php $venue = get_field('venue'); ?>
                                <?php $start_date = get_field('start_date'); ?>
                                <?php $end_date = get_field('end_date'); ?>

                                <?php if ($venue) : ?>
                                    <p class="exhibition-venue"><?php echo esc_html($venue); ?></p>
                                <?php endif; ?>

                                <?php if ($start_date || $end_date) : ?>
                                    <p class="exhibition-dates">
                                        <?php
                                        if ($start_date) {
                                            echo esc_html(date_i18n(get_option('date_format'), strtotime($start_date)));
                                        }

                                        if ($start_date && $end_date) {
                                            echo ' - ';
                                        }

                                        if ($end_date) {
                                            echo esc_html(date_i18n(get_option('date_format'), strtotime($end_date)));
                                        }
                                        ?>
                                    </p>
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
            <div class="no-exhibitions">
                <h2><?php _e('No exhibitions found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No exhibitions have been added yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
