<?php
/**
 * News Archive Template (Regular Posts)
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">News</h1>
            <div class="archive-description">
                <p><?php _e('Latest news and updates from our studio.', 'ricelipka-theme'); ?></p>
            </div>
        </header>

        <?php if (have_posts()) : ?>
            <div class="news-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'news-archive'); ?>
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
            <div class="no-news">
                <h2><?php _e('No news found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No news articles have been published yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>