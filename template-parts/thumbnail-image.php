<?php
/**
 * Thumbnail Image Component
 * 
 * Reusable component for displaying images with consistent 3:2 aspect ratio
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Get component arguments
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$image_size = isset($args['image_size']) ? $args['image_size'] : 'medium';
$alt_text = isset($args['alt_text']) ? $args['alt_text'] : get_the_title($post_id);
$show_placeholder = isset($args['show_placeholder']) ? $args['show_placeholder'] : true;
$placeholder_text = isset($args['placeholder_text']) ? $args['placeholder_text'] : '';
$additional_classes = isset($args['additional_classes']) ? $args['additional_classes'] : '';

// Build CSS classes
$css_classes = 'thumbnail-image';
if ($additional_classes) {
    $css_classes .= ' ' . $additional_classes;
}
?>

<div class="<?php echo esc_attr($css_classes); ?>">
    <?php if (has_post_thumbnail($post_id)) : ?>
        <?php echo get_the_post_thumbnail($post_id, $image_size, array(
            'alt' => esc_attr($alt_text)
        )); ?>
    <?php elseif ($show_placeholder) : ?>
        <div class="thumbnail-placeholder">

        </div>
    <?php endif; ?>
</div>