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
                    $thumb_id  = get_post_thumbnail_id();
                    $full_url  = wp_get_attachment_image_url($thumb_id, 'full') ?: wp_get_attachment_image_url($thumb_id, 'large');
                    echo '<a href="' . esc_url($full_url) . '" class="project-lightbox-trigger" data-lightbox-src="' . esc_url($full_url) . '">';
                    the_post_thumbnail('large');
                    echo '</a>';
                } elseif (!empty($image_gallery)) {
                    $first_image = $image_gallery[0];
                    if (is_array($first_image)) {
                        $img_url  = $first_image['sizes']['large'] ?? $first_image['url'] ?? '';
                        $full_url = $first_image['url'] ?? $img_url;
                        $img_alt  = $first_image['alt'] ?? get_the_title();
                        echo '<a href="' . esc_url($full_url) . '" class="project-lightbox-trigger" data-lightbox-src="' . esc_url($full_url) . '">';
                        echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($img_alt) . '" />';
                        echo '</a>';
                    } else {
                        $full_url = wp_get_attachment_image_url($first_image, 'full') ?: wp_get_attachment_image_url($first_image, 'large');
                        echo '<a href="' . esc_url($full_url) . '" class="project-lightbox-trigger" data-lightbox-src="' . esc_url($full_url) . '">';
                        echo wp_get_attachment_image($first_image, 'large');
                        echo '</a>';
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
                                $full_url  = wp_get_attachment_image_url($image_id, 'full') ?: wp_get_attachment_image_url($image_id, 'large');
                                    $thumb_url = wp_get_attachment_image_url($image_id, 'medium') ?: wp_get_attachment_image_url($image_id, 'thumbnail') ?: $full_url;
                                $alt_text  = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                                $caption   = wp_get_attachment_caption($image_id) ?: '';
                                }
                                ?>
                                <div class="gallery-item">
                                    <a href="<?php echo esc_url($full_url); ?>" class="project-lightbox-trigger" data-lightbox-src="<?php echo esc_url($full_url); ?>">
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
            </article>
        <?php endwhile; ?>
    </div>
                        </div>

<style>
.project-lightbox-trigger { display: block; cursor: zoom-in; }
.project-lightbox-trigger img { display: block; width: 100%; height: auto; }
.project-lightbox-trigger:hover,
.project-lightbox-trigger:focus { opacity: 1 !important; }
#project-lightbox { position: fixed !important; inset: 0 !important; z-index: 2147483647 !important; background: #ffffff !important; display: none; align-items: center; justify-content: center; }
#project-lightbox.is-open { display: flex !important; }
#project-lightbox img { max-width: 90vw; max-height: 88vh; width: auto; height: auto; object-fit: contain; display: block; opacity: 0; transition: opacity 0.25s ease; }
#project-lightbox.is-loaded img { opacity: 1; }
#project-lightbox .pl-spinner { position: absolute; width: 48px; height: 48px; border: 3px solid rgba(0,0,0,0.15); border-top-color: var(--ricelipka-active-color, #000); border-radius: 50%; animation: pl-spin 0.9s linear infinite; display: none; }
#project-lightbox.is-loading .pl-spinner { display: block; }
@keyframes pl-spin { to { transform: rotate(360deg); } }
#project-lightbox .pl-btn { position: absolute; background: transparent; border: 0; color: var(--ricelipka-active-color, #000); cursor: pointer; line-height: 1; padding: 0.25rem 0.75rem; font-family: inherit; z-index: 2; }
#project-lightbox .pl-btn:hover, #project-lightbox .pl-btn:focus { opacity: 0.7; outline: none; }
#project-lightbox .pl-close { top: 1rem; right: 1.25rem; font-size: 2.75rem; }
#project-lightbox .pl-prev { left: 1rem; top: 50%; transform: translateY(-50%); font-size: 2.5rem; }
#project-lightbox .pl-next { right: 1rem; top: 50%; transform: translateY(-50%); font-size: 2.5rem; }
#project-lightbox .pl-counter { position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); color: var(--ricelipka-active-color, #000); font-size: 0.85rem; opacity: 0.75; z-index: 2; }
body.pl-open { overflow: hidden; }
</style>

<?php get_footer(); ?>