<?php
/**
 * Work Archive Template
 * 
 * Template for /work/archive/ - displays all projects in a sortable table format
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>
     <div class="grid">
        <div class="not-grid">
        <table id="projects-table" class="projects-table">
            <thead>
                <tr>
                    <th class="sortable" data-sort="year">YEAR</th>
                    <th class="thumbnail-header"></th>
                    <th class="sortable" data-sort="project">PROJECT</th>
                    <th class="sortable" data-sort="client">CLIENT</th>
                    <th class="sortable" data-sort="location">LOCATION</th>
                    <th class="sortable" data-sort="category">CATEGORY</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get all projects
                $projects_query = new WP_Query(array(
                    'post_type' => 'projects',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'meta_value_num',
                    'meta_key' => 'project_year',
                    'order' => 'DESC'
                ));
                
                if ($projects_query->have_posts()) :
                    while ($projects_query->have_posts()) : $projects_query->the_post();
                        $project_fields = ricelipka_get_post_type_fields();
                        $project_year = $project_fields['project_year'] ?: '';
                        $client = $project_fields['client'] ?: '';
                        $location = $project_fields['location'] ?: '';
                        $project_type = $project_fields['project_type'] ?: '';
                        $category_display = ricelipka_get_project_type_display($project_type);
                ?>
                <tr class="project-row" data-year="<?php echo esc_attr($project_year); ?>" data-project="<?php echo esc_attr(get_the_title()); ?>" data-client="<?php echo esc_attr($client); ?>" data-location="<?php echo esc_attr($location); ?>" data-category="<?php echo esc_attr($category_display); ?>">
                    <td class="year-cell"><?php echo esc_html($project_year); ?></td>
                    <td class="thumbnail-cell">
                        <div class="project-thumbnail">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', array(
                                    'alt' => esc_attr(get_the_title()),
                                    'class' => 'project-thumbnail-img'
                                )); ?>
                            <?php else : ?>
                                <div class="project-placeholder">
                                    <div class="placeholder-content"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="project-cell">
                        <a href="<?php the_permalink(); ?>" class="project-link heading">
                            <?php the_title(); ?>
                        </a>
                    </td>
                    <td class="client-cell"><?php echo esc_html($client); ?></td>
                    <td class="location-cell"><?php echo esc_html($location); ?></td>
                    <td class="category-cell"><?php echo esc_html($category_display); ?></td>
                </tr>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </tbody>
        </table>
            </div>
            </div>
</div>

<?php get_footer(); ?>