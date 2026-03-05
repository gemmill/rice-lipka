<?php
/**
 * Person Item Component
 * 
 * Reusable component for displaying a person in listings
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Get person data
$person_id = get_the_ID();
$person_title = get_the_title();
$person_fields = ricelipka_get_post_type_fields();

// Component classes - can be customized via args
$component_class = isset($args['class']) ? $args['class'] : 'person-item';
$image_size = isset($args['image_size']) ? $args['image_size'] : 'medium';
$show_meta = isset($args['show_meta']) ? $args['show_meta'] : true;
$show_excerpt = isset($args['show_excerpt']) ? $args['show_excerpt'] : false;
$layout = isset($args['layout']) ? $args['layout'] : 'default'; // default, compact, minimal
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($component_class . ' person-item-' . $layout); ?>>
    
    <div class="person-image">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail($image_size, array(
                'alt' => esc_attr($person_title),
                'class' => 'person-thumbnail-img'
            )); ?>
        <?php else : ?>
            <div class="person-placeholder">
                <div class="placeholder-content">
                    <span class="placeholder-text"><?php echo esc_html(substr($person_title, 0, 1)); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="person-content">
        <h3 class="person-title"><?php echo esc_html($person_title); ?></h3>
        
        <?php if ($show_meta && $person_fields) : ?>
            <div class="person-meta">
                <?php if (!empty($person_fields['role'])) : ?>
                    <span class="person-role"><?php echo esc_html($person_fields['role']); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($show_excerpt) : ?>
            <div class="person-excerpt">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($show_meta && !empty($person_fields['person_associations'])) : ?>
            <div class="person-projects">
                <span class="projects-label"><?php _e('Projects:', 'ricelipka-theme'); ?></span>
                <?php
                $project_names = array();
                foreach ($person_fields['person_associations'] as $project) {
                    $project_names[] = $project->post_title;
                }
                echo esc_html(implode(', ', array_slice($project_names, 0, 3)));
                if (count($project_names) > 3) {
                    echo ' <span class="more-projects">+' . (count($project_names) - 3) . ' more</span>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    
</article>