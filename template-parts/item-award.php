<?php
/**
 * Award Item Component
 * 
 * Reusable component for displaying an award in listings
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Get award data
$award_id = get_the_ID();
$award_title = get_the_title();
$award_fields = ricelipka_get_post_type_fields();

// Component classes - can be customized via args
$component_class = isset($args['class']) ? $args['class'] : 'award-item';
$image_size = isset($args['image_size']) ? $args['image_size'] : 'medium';
$show_meta = isset($args['show_meta']) ? $args['show_meta'] : true;
$show_excerpt = isset($args['show_excerpt']) ? $args['show_excerpt'] : false;
$layout = isset($args['layout']) ? $args['layout'] : 'default'; // default, compact, minimal
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($component_class . ' award-item-' . $layout); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="award-image">
            <?php the_post_thumbnail($image_size, array(
                'alt' => esc_attr($award_title),
                'class' => 'award-thumbnail-img'
            )); ?>
        </div>
    <?php endif; ?>
    
    <div class="award-content">
        <h3 class="award-title"><?php echo esc_html($award_title); ?></h3>
        
        <?php if ($show_meta && $award_fields) : ?>
            <div class="award-meta">
                <?php if (!empty($award_fields['award_year'])) : ?>
                    <span class="award-year"><?php echo esc_html($award_fields['award_year']); ?></span>
                <?php endif; ?>
                
                <?php if (!empty($award_fields['award_organization'])) : ?>
                    <span class="award-organization"><?php echo esc_html($award_fields['award_organization']); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($show_excerpt) : ?>
            <div class="award-excerpt">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($show_meta && $award_fields) : ?>
            <?php 
            // Show associated project or project name text
            $associated_project = $award_fields['associated_project'] ?? null;
            $project_name_text = $award_fields['project_name_text'] ?? '';
            ?>
            
            <?php if ($associated_project && is_object($associated_project)) : ?>
                <div class="award-project">
                    <span class="project-label"><?php _e('Project:', 'ricelipka-theme'); ?></span>
                    <a href="<?php echo get_permalink($associated_project->ID); ?>" class="project-link">
                        <?php echo esc_html($associated_project->post_title); ?>
                    </a>
                </div>
            <?php elseif ($project_name_text) : ?>
                <div class="award-project">
                    <span class="project-label"><?php _e('Project:', 'ricelipka-theme'); ?></span>
                    <span class="project-name"><?php echo esc_html($project_name_text); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
</article>