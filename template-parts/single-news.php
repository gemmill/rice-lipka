<?php
/**
 * Template part for displaying news-specific fields in single posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$fields = $args['fields'] ?? array();
?>

<?php if (!empty($fields)) : ?>
    <div class="news-fields-container">
        
        <?php if ($fields['featured_image'] && is_array($fields['featured_image'])) : ?>
            <div class="news-featured-image">
                <img src="<?php echo esc_url($fields['featured_image']['url']); ?>" 
                     alt="<?php echo esc_attr($fields['featured_image']['alt'] ?: get_the_title()); ?>" />
            </div>
        <?php endif; ?>
        
        <div class="news-meta-details">
            <?php if ($fields['publication_date']) : ?>
                <div class="news-publication-date">
                    <strong><?php _e('Published:', 'ricelipka-theme'); ?></strong>
                    <time datetime="<?php echo esc_attr($fields['publication_date']); ?>">
                        <?php echo date('F j, Y', strtotime($fields['publication_date'])); ?>
                    </time>
                </div>
            <?php endif; ?>
            
            <?php if ($fields['subcategory']) : ?>
                <div class="news-subcategory-display">
                    <strong><?php _e('Category:', 'ricelipka-theme'); ?></strong>
                    <span class="subcategory-tag subcategory-<?php echo esc_attr($fields['subcategory']); ?>">
                        <?php echo esc_html(ucfirst(str_replace('_', ' ', $fields['subcategory']))); ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($fields['excerpt']) : ?>
            <div class="news-excerpt-display">
                <h3><?php _e('Summary', 'ricelipka-theme'); ?></h3>
                <p><?php echo wp_kses_post($fields['excerpt']); ?></p>
            </div>
        <?php endif; ?>
        
    </div>
<?php endif; ?>