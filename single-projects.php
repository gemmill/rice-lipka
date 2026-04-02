<?php
/**
 * Single Project Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>
    
    <div class="grid">
<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-project'); ?>>
                <header class="project-header">
                    <h1 class="project-title"><?php the_title(); ?></h1>
                    
                    <?php
                    // Get ACF fields directly with fallbacks
                    if (function_exists('get_field')) {
                        $project_year = get_field('project_year');
                        $client = get_field('client');
                        $location = get_field('location');
                        $project_type = get_field('project_type');
                        $image_gallery = get_field('image_gallery');
                    } else {
                        // Fallback to post meta if ACF is not available
                        $project_year = get_post_meta(get_the_ID(), 'project_year', true);
                        $client = get_post_meta(get_the_ID(), 'client', true);
                        $location = get_post_meta(get_the_ID(), 'location', true);
                        $project_type = get_post_meta(get_the_ID(), 'project_type', true);
                        $image_gallery = get_post_meta(get_the_ID(), 'image_gallery', true);
                    }
                    
                    // Check if we have any fields to display
                    $has_fields = !empty($project_year) || !empty($client) || !empty($location) || !empty($project_type);
                    
                    if ($has_fields) :
                    ?>
                        <div class="project-meta">
                            <?php if (!empty($project_year)) : ?>
                                <span class="project-year"><?php echo esc_html($project_year); ?></span>
                            <?php endif; ?>
                            
                            <?php if (!empty($project_type)) : ?>
                                <span class="project-type">
                                    <a href="<?php echo home_url('/work/' . $project_type . '/'); ?>">
                                        <?php echo esc_html(ricelipka_get_project_type_display($project_type)); ?>
                                    </a>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($client)) : ?>
                                <span class="project-client">
                                    <strong>Client:</strong> <?php echo esc_html($client); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($location)) : ?>
                                <span class="project-location">
                                    <strong>Location:</strong> <?php echo esc_html($location); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="project-featured-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="project-content">
                    <?php the_content(); ?>
                </div>

                <?php
                // Display image gallery if it exists
                if (!empty($image_gallery) && is_array($image_gallery)) :
                ?>
                
    

                            <?php foreach ($image_gallery as $image) : ?>
                        
                                <div class="gallery-item">
                                    <a href="<?php echo esc_url($image['url'] ?? ''); ?>" data-lightbox="project-gallery">
                                        <img src="<?php echo esc_url($image['sizes']['medium'] ?? $image['url'] ?? ''); ?>" 
                                             alt="<?php echo esc_attr($image['alt'] ?? get_the_title()); ?>" />
                                    </a>
                                    <?php if (!empty($image['caption'] ?? '')) : ?>
                                        <p class="gallery-caption"><?php echo esc_html($image['caption'] ?? ''); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>

                <?php endif; ?>

                <nav class="project-navigation">
                    <div class="nav-links">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        ?>
                        
                        <?php if ($prev_post) : ?>
                            <div class="nav-previous">
                                <a href="<?php echo get_permalink($prev_post); ?>" rel="prev">
                                    <span class="nav-subtitle">Previous Project</span>
                                    <span class="nav-title"><?php echo get_the_title($prev_post); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="nav-back">
                            <a href="<?php echo home_url('/work/'); ?>" class="back-to-projects">
                                All Projects
                            </a>
                        </div>
                        
                        <?php if ($next_post) : ?>
                            <div class="nav-next">
                                <a href="<?php echo get_permalink($next_post); ?>" rel="next">
                                    <span class="nav-subtitle">Next Project</span>
                                    <span class="nav-title"><?php echo get_the_title($next_post); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </nav>
            </article>
        <?php endwhile; ?>
    </div>
</main>
                        </div>
                        </div>

<?php get_footer(); ?>