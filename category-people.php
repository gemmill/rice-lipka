<?php
/**
 * Template for displaying People category archive
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="container">
    <main id="main" class="site-main people-archive">
        
        <header class="page-header">
            <h1 class="page-title"><?php _e('People', 'ricelipka-theme'); ?></h1>
            <div class="archive-description">
                <p><?php _e('Meet the team behind Rice+Lipka Architects and our collaborators.', 'ricelipka-theme'); ?></p>
            </div>
        </header>

        <?php if (have_posts()) : ?>
            
            <div class="people-filters">
                <button class="filter-btn active" data-filter="all">
                    <?php _e('All People', 'ricelipka-theme'); ?>
                </button>
                <button class="filter-btn" data-filter="principal">
                    <?php _e('Principals', 'ricelipka-theme'); ?>
                </button>
                <button class="filter-btn" data-filter="associate">
                    <?php _e('Associates', 'ricelipka-theme'); ?>
                </button>
                <button class="filter-btn" data-filter="architect">
                    <?php _e('Architects', 'ricelipka-theme'); ?>
                </button>
                <button class="filter-btn" data-filter="designer">
                    <?php _e('Designers', 'ricelipka-theme'); ?>
                </button>
                <button class="filter-btn" data-filter="consultant">
                    <?php _e('Consultants', 'ricelipka-theme'); ?>
                </button>
            </div>

            <div class="people-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'people'); ?>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_navigation(array(
                'prev_text' => __('&larr; Previous People', 'ricelipka-theme'),
                'next_text' => __('Next People &rarr;', 'ricelipka-theme'),
            ));
            ?>

        <?php else : ?>
            
            <div class="no-posts-found">
                <h2><?php _e('No People Found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('There are currently no people profiles to display.', 'ricelipka-theme'); ?></p>
            </div>

        <?php endif; ?>

    </main>
</div>

<?php get_footer(); ?>