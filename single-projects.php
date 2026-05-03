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
            <?php
            $project_year  = get_field('project_year');
            $client        = get_field('client');
            $location      = get_field('location');
            $project_type  = get_field('project_type');
            $image_gallery = get_field('image_gallery');
            $project_status = get_field('completion_status');
                    ?>
                       
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-project'); ?>>

                <div class="project-featured-image">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('large');
                } elseif (!empty($image_gallery)) {
                    $first_image = $image_gallery[0];
                    if (is_array($first_image)) {
                        $img_url = $first_image['sizes']['large'] ?? $first_image['url'] ?? '';
                        $img_alt = $first_image['alt'] ?? get_the_title();
                        echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($img_alt) . '" />';
                    } else {
                        echo wp_get_attachment_image($first_image, 'large');
                    }
                }
                ?>
                </div>
           
              
                    <h1 class="project-title"><?php the_title(); ?></h1>

 <div>
                    <?php
                    $meta_parts = array();
                    if (!empty($client)) {
                        $meta_parts[] = esc_html($client);
                    }
                    if (!empty($location)) {
                        $meta_parts[] = esc_html($location);
                    }
                    if (!empty($meta_parts)) : ?>
                        <span class="project-client-location">
                            <?php echo implode(', ', $meta_parts); ?>
                        </span>
                    <?php endif; ?>
                    </div>


                <div class="project-content">
                    <?php the_content(); ?>
                </div>


               <div class="project-content">
                    <?php
                    $status_year_parts = array();
                    if (!empty($project_status)) {
                        $status_year_parts[] = esc_html(ucfirst($project_status));
                    }
                    if (!empty($project_year)) {
                        $status_year_parts[] = esc_html($project_year);
                    }
                    if (!empty($status_year_parts)) : ?>
                        <span class="project-status-year"><?php echo implode(' ', $status_year_parts); ?></span>
                    <?php endif; ?>       
                </div>
                <?php if (!empty($image_gallery) && is_array($image_gallery)) : ?>
                    <div class="project-gallery">
                            <?php foreach ($image_gallery as $image) : ?>
                                <?php
                            if (is_array($image)) {
                                $full_url  = $image['url'] ?? $image['sizes']['large'] ?? '';
                                $thumb_url = $image['sizes']['large'] ?? $image['url'] ?? '';
                                $alt_text  = $image['alt'] ?? get_the_title();
                                $caption   = $image['caption'] ?? '';
                                } else {
                                $image_id  = $image;
                                $full_url  = wp_get_attachment_image_url($image_id, 'large') ?: wp_get_attachment_image_url($image_id, 'full');
                                    $thumb_url = wp_get_attachment_image_url($image_id, 'medium') ?: wp_get_attachment_image_url($image_id, 'thumbnail') ?: $full_url;
                                $alt_text  = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                                $caption   = wp_get_attachment_caption($image_id) ?: '';
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