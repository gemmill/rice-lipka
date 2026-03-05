<?php
/**
 * Project Item Component
 * 
 * Reusable component for displaying a project in listings
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Get project data
$project_id = get_the_ID();
$project_title = get_the_title();
$project_permalink = get_the_permalink();
$project_fields = ricelipka_get_post_type_fields();

// Component classes - can be customized via args
$component_class = isset($args['class']) ? $args['class'] : 'project-item';
$image_size = isset($args['image_size']) ? $args['image_size'] : 'medium';
$layout = isset($args['layout']) ? $args['layout'] : 'default'; // default, compact, minimal

// Get project type and convert to camelCase for CSS class
$project_type = $project_fields['project_type'] ?? '';
$project_type_class = '';
if ($project_type) {
    // Convert snake_case to camelCase (e.g., retail_commercial -> retailCommercial)
    $project_type_class = lcfirst(str_replace('_', '', ucwords($project_type, '_')));
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($component_class . ' project-item-' . $layout . ($project_type_class ? ' ' . $project_type_class : '')); ?> data-project-type="<?php echo esc_attr($project_type); ?>">
    
    <a href="<?php echo esc_url($project_permalink); ?>" class="project-link">
        <div class="project-image-wrapper">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail($image_size, array(
                    'alt' => esc_attr($project_title),
                    'class' => 'project-image'
                )); ?>
            <?php else : ?>
                <div class="project-placeholder">
                    <div class="placeholder-content">
                        <span class="placeholder-text"><?php echo esc_html(substr($project_title, 0, 1)); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="project-overlay">
                <h2 class="project-title"><?php echo esc_html($project_title); ?></h2>
            </div>
        </div>
    </a>
    
</article>