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

<div class="award">
    
    <?php get_template_part('template-parts/thumbnail-image', null, array(
        'post_id' => $award_id,
        'image_size' => $image_size,
        'alt_text' => $award_title,
        'show_placeholder' => true,
        'placeholder_text' => ''
    )); ?>
    
    <div>
        <h3 ><?php echo esc_html($award_title); ?></h3>
        
        <?php if ($show_meta && $award_fields) : ?>
            <?php 
            // Show associated project or project name text
            $associated_project = $award_fields['associated_project'] ?? null;
            $project_name_text = $award_fields['project_name_text'] ?? '';
            $date_received = $award_fields['date_received'] ?? '';
            ?>
            
            <?php if ($associated_project && is_object($associated_project)) : ?>
                <div>
                    <a href="<?php echo get_permalink($associated_project->ID); ?>" class="project-link">
                        <?php echo esc_html($associated_project->post_title); ?>
                    </a>
                </div>
            <?php elseif ($project_name_text) : ?>
                <div>
                    <span class="project-name"><?php echo esc_html($project_name_text); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($date_received)) : ?>
                <div>
                    <?php 
                    // Extract year from date
                    $year = date('Y', strtotime($date_received));
                    echo esc_html($year);
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
            </div>