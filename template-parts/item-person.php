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
$image_size = isset($args['image_size']) ? $args['image_size'] : 'large';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($component_class . ' person-item'); ?>>
    
    <h3><?php echo $person_title ?></h3>

    <div class="person-meta">
        <?php if (!empty($person_fields['person_title'])) : ?>
            <span class="person-job-title"><?php echo esc_html($person_fields['person_title']); ?></span>
        <?php endif; ?>
        <div class="person-associations">
            <span class="associations-text"><?php echo esc_html($person_fields['person_associations']); ?></span>
        </div>
    </div>

    <div class="person-image">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail($image_size, array(
                'alt' => esc_attr($person_title),
                'class' => 'person-thumbnail-img'
            )); ?>
       
        <?php endif; ?>
    </div>
    
    <div class="person-content">



        

            <div class="person-content">
                <?php the_content(); ?>
            </div>
        
    </div>
    
</article>