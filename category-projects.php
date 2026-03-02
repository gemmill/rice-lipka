<?php
/**
 * The template for displaying Projects category archives
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main projects-archive category-archive">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <header class="page-header projects-header category-header">
                <h1 class="page-title projects-title category-title">
                    <?php single_cat_title(); ?>
                </h1>
                
                <?php
                $category = get_queried_object();
                if ($category->description) : ?>
                    <div class="category-description projects-description">
                        <?php echo wp_kses_post($category->description); ?>
                    </div>
                <?php endif; ?>
                
                <div class="category-count">
                    <?php
                    $count = $category->count;
                    printf(
                        _n('%d project', '%d projects', $count, 'ricelipka-theme'),
                        $count
                    );
                    ?>
                </div>
                
                <!-- Project type filter -->
                <div class="project-filters category-nav">
                    <ul>
                        <li><a href="#" class="filter-btn active" data-filter="all"><?php _e('All Projects', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="civic_architecture"><?php _e('Civic Architecture', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="cultural_projects"><?php _e('Cultural Projects', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="educational_buildings"><?php _e('Educational Buildings', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="public_works"><?php _e('Public Works', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="residential"><?php _e('Residential', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="commercial"><?php _e('Commercial', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="mixed_use"><?php _e('Mixed Use', 'ricelipka-theme'); ?></a></li>
                    </ul>
                </div>
            </header>
            
            <div class="posts-container projects-container projects-grid posts-grid">
                <?php while (have_posts()) : the_post(); ?>
                    
                    <?php
                    // Get ACF fields for projects
                    $project_fields = ricelipka_get_post_type_fields(get_the_ID());
                    $project_type = $project_fields['project_type'] ?: 'general';
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('project-item post-item post-card'); ?> data-project-type="<?php echo esc_attr($project_type); ?>">
                        
                        <?php
                        // Display project gallery or featured image
                        $image_gallery = $project_fields['image_gallery'];
                        $featured_image = null;
                        
                        if ($image_gallery && is_array($image_gallery) && !empty($image_gallery)) {
                            $featured_image = $image_gallery[0]; // First image from gallery
                        } elseif (has_post_thumbnail()) {
                            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                        }
                        ?>
                        
                        <?php if ($featured_image) : ?>
                            <div class="post-thumbnail project-thumbnail post-card-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (is_array($featured_image) && isset($featured_image['url'])) : ?>
                                        <img src="<?php echo esc_url($featured_image['url']); ?>" 
                                             alt="<?php echo esc_attr($featured_image['alt'] ?: get_the_title()); ?>" />
                                    <?php else : ?>
                                        <?php the_post_thumbnail('large'); ?>
                                    <?php endif; ?>
                                </a>
                                
                                <?php if ($image_gallery && count($image_gallery) > 1) : ?>
                                    <span class="gallery-count">
                                        📷 <?php printf(__('%d Images', 'ricelipka-theme'), count($image_gallery)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content project-content post-card-content">
                            <div class="post-card-meta">
                                <?php if ($project_fields['completion_status']) : ?>
                                    <span class="project-status post-card-category status-<?php echo esc_attr($project_fields['completion_status']); ?>">
                                        <?php echo esc_html(ucfirst(str_replace('_', ' ', $project_fields['completion_status']))); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($project_fields['project_type']) : ?>
                                    <span class="project-type type-<?php echo esc_attr($project_fields['project_type']); ?>">
                                        <?php echo esc_html(ucfirst(str_replace('_', ' ', $project_fields['project_type']))); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($project_fields['completion_status'] === 'in_progress' && $project_fields['completion_percentage']) : ?>
                                    <span class="completion-percentage">
                                        🔄 <?php printf(__('%d%% Complete', 'ricelipka-theme'), $project_fields['completion_percentage']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <header class="entry-header">
                                <h2 class="entry-title project-entry-title post-card-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                            </header>
                            
                            <div class="project-details">
                                <?php if ($project_fields['client']) : ?>
                                    <div class="project-client">
                                        <strong><?php _e('Client:', 'ricelipka-theme'); ?></strong>
                                        <?php echo esc_html($project_fields['client']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($project_fields['location']) : ?>
                                    <div class="project-location">
                                        <strong><?php _e('Location:', 'ricelipka-theme'); ?></strong>
                                        📍 <?php echo esc_html($project_fields['location']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="entry-summary project-summary post-card-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <footer class="entry-footer project-footer post-card-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more project-read-more read-more-btn">
                                    <?php _e('View Project', 'ricelipka-theme'); ?> →
                                </a>
                                
                                <div class="post-card-tags">
                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags) {
                                        foreach ($tags as $tag) {
                                            echo '<a href="' . get_tag_link($tag->term_id) . '" class="post-tag">' . esc_html($tag->name) . '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                            </footer>
                        </div>
                        
                    </article>
                    
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            echo '<div class="pagination-wrapper">';
            the_posts_pagination(array(
                'prev_text' => __('← Previous Projects', 'ricelipka-theme'),
                'next_text' => __('Next Projects →', 'ricelipka-theme'),
                'class' => 'pagination'
            ));
            echo '</div>';
            ?>
            
        <?php else : ?>
            
            <section class="no-results projects-no-results">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('No Projects Found', 'ricelipka-theme'); ?></h1>
                </header>
                
                <div class="page-content">
                    <p><?php _e('There are currently no projects available. Please check back later.', 'ricelipka-theme'); ?></p>
                </div>
            </section>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();