<?php
/**
 * Template part for displaying project posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$project_fields = ricelipka_get_category_fields(get_the_ID());
$project_type = $project_fields['project_type'] ?: 'general';

// Display project gallery or featured image
$image_gallery = $project_fields['image_gallery'];
$featured_image = null;

if ($image_gallery && is_array($image_gallery) && !empty($image_gallery)) {
    $featured_image = $image_gallery[0]; // First image from gallery
} elseif (has_post_thumbnail()) {
    $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('project-item post-item'); ?> data-project-type="<?php echo esc_attr($project_type); ?>">
    
    <?php if ($featured_image) : ?>
        <div class="post-thumbnail project-thumbnail">
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
                    <?php printf(__('%d Images', 'ricelipka-theme'), count($image_gallery)); ?>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="post-content project-content">
        <header class="entry-header">
            <h2 class="entry-title project-entry-title">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>
            
            <div class="entry-meta project-meta">
                <?php if ($project_fields['completion_status']) : ?>
                    <span class="project-status status-<?php echo esc_attr($project_fields['completion_status']); ?>">
                        <?php echo esc_html(ucfirst(str_replace('_', ' ', $project_fields['completion_status']))); ?>
                    </span>
                    
                    <?php if ($project_fields['completion_status'] === 'in_progress' && $project_fields['completion_percentage']) : ?>
                        <span class="completion-percentage">
                            <?php printf(__('%d%% Complete', 'ricelipka-theme'), $project_fields['completion_percentage']); ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if ($project_fields['project_type']) : ?>
                    <span class="project-type type-<?php echo esc_attr($project_fields['project_type']); ?>">
                        <?php echo esc_html(ucfirst(str_replace('_', ' ', $project_fields['project_type']))); ?>
                    </span>
                <?php endif; ?>
            </div>
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
                    <?php echo esc_html($project_fields['location']); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="entry-summary project-summary">
            <?php the_excerpt(); ?>
        </div>
        
        <footer class="entry-footer project-footer">
            <a href="<?php the_permalink(); ?>" class="read-more project-read-more">
                <?php _e('View Project', 'ricelipka-theme'); ?>
            </a>
        </footer>
    </div>
    
</article>