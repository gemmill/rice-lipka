<?php
/**
 * Projects Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>
    
    <div class="projects-grid">
        <?php if (have_posts()) : ?>
          
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'projects-archive'); ?>
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
            <div class="no-projects">
                <h2><?php _e('No projects found', 'ricelipka-theme'); ?></h2>
                <?php 
                $project_type = get_query_var('project_type_filter');
                if ($project_type) : ?>
                    <p><?php printf(__('No projects found in the %s category.', 'ricelipka-theme'), ricelipka_get_project_type_display($project_type)); ?></p>
                    <p><a href="<?php echo home_url('/work/'); ?>"><?php _e('View all projects', 'ricelipka-theme'); ?></a></p>
                <?php else : ?>
                    <p><?php _e('No projects have been published yet.', 'ricelipka-theme'); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>