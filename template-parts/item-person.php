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
$component_class = 'item-person';
$image_size = 'large';
?>

<article id="item-<?php the_ID(); ?>" <?php post_class($component_class); ?>>
    
    <h3><?php echo $person_title ?></h3>

    <div class="person-meta">
        <?php if (!empty($person_fields['person_title'])) : ?>
            <span class="person-job-title"><?php echo esc_html($person_fields['person_title']); ?></span>
        <?php endif; ?>
        <?php if (!empty($person_fields['person_associations'])) : ?>
            <div class="person-associations">
                <span class="associations-text"><?php echo esc_html($person_fields['person_associations']); ?></span>
            </div>
        <?php endif; ?>
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



    
                <?php the_content(); ?>
  
        
    </div>
    
</article>