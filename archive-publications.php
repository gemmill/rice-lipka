<?php
/**
 * Publications Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>

    <div class="grid">
        <div id="publications-masonry" class="masonry">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="masonry-item">
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-item publication-item'); ?>>
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                            </header>

                            <div class="entry-meta">
                                <?php $publication_periodical = get_field('periodical'); ?>
                                <?php $publication_year = get_field('year'); ?>
                                <?php $publication_category = get_field('category'); ?>

                                <?php if ($publication_periodical) : ?>
                                    <p class="publication-periodical"><?php echo esc_html($publication_periodical); ?></p>
                                <?php endif; ?>

                                <?php if ($publication_year) : ?>
                                    <p class="publication-year"><?php echo esc_html($publication_year); ?></p>
                                <?php endif; ?>

                                <?php if ($publication_category) : ?>
                                    <p class="publication-category"><?php echo esc_html($publication_category); ?></p>
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
            <div class="no-publications">
                <h2><?php _e('No publications found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No publications have been added yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
