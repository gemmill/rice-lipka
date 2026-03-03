<?php
/**
 * Projects Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header">
            <?php
            // Check if we're filtering by project type
            $project_type = get_query_var('project_type_filter');
            
            if ($project_type) {
                $type_display = ricelipka_get_project_type_display($project_type);
                echo '<h1 class="page-title">' . esc_html($type_display) . ' Projects</h1>';
            } else {
                echo '<h1 class="page-title">All Projects</h1>';
            }
            ?>
            
            <nav class="project-type-filter">
                <ul class="filter-list">
                    <li class="filter-item <?php echo !$project_type ? 'active' : ''; ?>">
                        <a href="<?php echo home_url('/work/'); ?>">All Projects</a>
                    </li>
                    <?php
                    $project_types = array(
                        'cultural' => 'Cultural',
                        'academic' => 'Academic',
                        'offices' => 'Offices',
                        'retail_commercial' => 'Retail & Commercial',
                        'institutional' => 'Institutional',
                        'planning' => 'Planning',
                        'exhibitions' => 'Exhibitions',
                        'research_installation' => 'Research & Installation',
                    );
                    
                    foreach ($project_types as $type_key => $type_label) {
                        $active_class = ($project_type === $type_key) ? 'active' : '';
                        echo '<li class="filter-item ' . $active_class . '">';
                        echo '<a href="' . home_url('/work/' . $type_key . '/') . '">' . esc_html($type_label) . '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </nav>
        </header>

        <?php if (have_posts()) : ?>
            <div class="projects-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'projects-archive'); ?>
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
            <div class="no-projects">
                <h2><?php _e('No projects found', 'ricelipka-theme'); ?></h2>
                <?php if ($project_type) : ?>
                    <p><?php printf(__('No projects found in the %s category.', 'ricelipka-theme'), ricelipka_get_project_type_display($project_type)); ?></p>
                    <p><a href="<?php echo home_url('/work/'); ?>"><?php _e('View all projects', 'ricelipka-theme'); ?></a></p>
                <?php else : ?>
                    <p><?php _e('No projects have been published yet.', 'ricelipka-theme'); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>