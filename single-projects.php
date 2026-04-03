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
    


    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-project'); ?>>
                <header class="project-header">
                    <h1 class="project-title"><?php the_title(); ?></h1>
                    
                    <?php
                 
                        $project_year = get_field('project_year');
                        $client = get_field('client');
                        $location = get_field('location');
                        $project_type = get_field('project_type');
                        $image_gallery = get_field('image_gallery');
                   
                    

                    ?>
                       

                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="project-featured-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>


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

                <div class="project-content">


                
                    <?php the_content(); ?>
                </div>

                <?php
                // Display image gallery if it exists
                if (!empty($image_gallery) && is_array($image_gallery)) :
                ?>
                    <div class="project-gallery">
                        <h2>Project Gallery</h2>
                        <div class="gallery-grid">
                            <?php foreach ($image_gallery as $image) : ?>
                                <?php
                                // Handle both image arrays and image IDs
                                if (is_array($image)) {
                                    // Image is already an array with URL and sizes
                                    $full_url = $image['url'] ?? $image['sizes']['large'] ?? '';
                                    $thumb_url = $image['sizes']['medium'] ?? $image['sizes']['thumbnail'] ?? $image['url'] ?? '';
                                    $alt_text = $image['alt'] ?? get_the_title();
                                    $caption = $image['caption'] ?? '';
                                } else {
                                    // Image is just an ID, get the data
                                    $image_id = $image;
                                    $full_url = wp_get_attachment_image_url($image_id, 'large') ?: wp_get_attachment_image_url($image_id, 'full');
                                    $thumb_url = wp_get_attachment_image_url($image_id, 'medium') ?: wp_get_attachment_image_url($image_id, 'thumbnail') ?: $full_url;
                                    $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                                    $caption = wp_get_attachment_caption($image_id) ?: '';
                                }
                                ?>
                                <div class="gallery-item">
                                    <a href="<?php echo esc_url($full_url); ?>" data-lightbox="project-gallery">
                                        <img src="<?php echo esc_url($thumb_url); ?>" 
                                             alt="<?php echo esc_attr($alt_text); ?>" />
                                    </a>
                                    <?php if (!empty($caption)) : ?>
                                        <p class="gallery-caption"><?php echo esc_html($caption); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
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
                        </div>

<?php get_footer(); ?>