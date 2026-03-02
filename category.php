<?php
/**
 * The template for displaying category archives
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <header class="page-header category-header">
                <?php
                $category = get_queried_object();
                $category_slug = $category->slug;
                ?>
                <h1 class="page-title category-title category-<?php echo esc_attr($category_slug); ?>">
                    <?php single_cat_title(); ?>
                </h1>
                
                <?php if ($category->description) : ?>
                    <div class="category-description">
                        <?php echo wp_kses_post($category->description); ?>
                    </div>
                <?php endif; ?>
            </header>
            
            <div class="posts-container category-<?php echo esc_attr($category_slug); ?>-container">
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-item category-post'); ?>>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php 
                                    // Different thumbnail sizes based on category
                                    $thumbnail_size = ($category_slug === 'projects') ? 'large' : 'medium';
                                    the_post_thumbnail($thumbnail_size); 
                                    ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <div class="entry-meta">
                                    <?php if ($category_slug === 'events') : ?>
                                        <?php
                                        // Display event date if available
                                        $event_date = get_field('event_date');
                                        if ($event_date) : ?>
                                            <time class="event-date" datetime="<?php echo esc_attr($event_date); ?>">
                                                <?php echo date('F j, Y', strtotime($event_date)); ?>
                                            </time>
                                        <?php endif; ?>
                                    <?php elseif ($category_slug === 'projects') : ?>
                                        <?php
                                        // Display project status if available
                                        $completion_status = get_field('completion_status');
                                        if ($completion_status) : ?>
                                            <span class="project-status status-<?php echo esc_attr($completion_status); ?>">
                                                <?php echo esc_html(ucfirst(str_replace('_', ' ', $completion_status))); ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <time class="published" datetime="<?php echo get_the_date('c'); ?>">
                                            <?php echo get_the_date(); ?>
                                        </time>
                                    <?php endif; ?>
                                </div>
                            </header>
                            
                            <div class="entry-summary">
                                <?php
                                // Custom excerpt handling based on category
                                if ($category_slug === 'projects') {
                                    // For projects, show project type and location if available
                                    $project_type = get_field('project_type');
                                    $location = get_field('location');
                                    
                                    if ($project_type || $location) {
                                        echo '<div class="project-meta">';
                                        if ($project_type) {
                                            echo '<span class="project-type">' . esc_html(ucfirst($project_type)) . '</span>';
                                        }
                                        if ($location) {
                                            echo '<span class="project-location">' . esc_html($location) . '</span>';
                                        }
                                        echo '</div>';
                                    }
                                }
                                
                                the_excerpt();
                                ?>
                            </div>
                        </div>
                        
                    </article>
                    
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'prev_text' => __('Previous', 'ricelipka-theme'),
                'next_text' => __('Next', 'ricelipka-theme'),
            ));
            ?>
            
        <?php else : ?>
            
            <section class="no-results">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('Nothing Found', 'ricelipka-theme'); ?></h1>
                </header>
                
                <div class="page-content">
                    <p><?php _e('It seems we can\'t find what you\'re looking for in this category. Perhaps searching can help.', 'ricelipka-theme'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            </section>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();