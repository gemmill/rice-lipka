<?php
/**
 * Template part for displaying project-specific fields in single posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$fields = $args['fields'] ?? array();
?>

<?php if (!empty($fields)) : ?>
    <div class="project-fields-container">
        
        <?php if ($fields['image_gallery'] && is_array($fields['image_gallery'])) : ?>
            <div class="project-gallery">
                <h3><?php _e('Project Gallery', 'ricelipka-theme'); ?></h3>
                <div class="gallery-grid">
                    <?php foreach ($fields['image_gallery'] as $image) : ?>
                        <div class="gallery-item">
                            <a href="<?php echo esc_url($image['url']); ?>" data-lightbox="project-gallery">
                                <img src="<?php echo esc_url($image['sizes']['medium'] ?? $image['url']); ?>" 
                                     alt="<?php echo esc_attr($image['alt'] ?: get_the_title()); ?>" />
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="project-details-grid">
            <div class="project-info">
                <h3><?php _e('Project Information', 'ricelipka-theme'); ?></h3>
                
                <?php if ($fields['completion_status']) : ?>
                    <div class="project-detail">
                        <strong><?php _e('Status:', 'ricelipka-theme'); ?></strong>
                        <span class="project-status status-<?php echo esc_attr($fields['completion_status']); ?>">
                            <?php echo esc_html(ucfirst(str_replace('_', ' ', $fields['completion_status']))); ?>
                        </span>
                        
                        <?php if ($fields['completion_status'] === 'in_progress' && $fields['completion_percentage']) : ?>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo intval($fields['completion_percentage']); ?>%"></div>
                                <span class="progress-text"><?php echo intval($fields['completion_percentage']); ?>% Complete</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($fields['project_type']) : ?>
                    <div class="project-detail">
                        <strong><?php _e('Type:', 'ricelipka-theme'); ?></strong>
                        <span class="project-type-display">
                            <?php echo esc_html(ucfirst(str_replace('_', ' ', $fields['project_type']))); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <?php if ($fields['client']) : ?>
                    <div class="project-detail">
                        <strong><?php _e('Client:', 'ricelipka-theme'); ?></strong>
                        <span><?php echo esc_html($fields['client']); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($fields['location']) : ?>
                    <div class="project-detail">
                        <strong><?php _e('Location:', 'ricelipka-theme'); ?></strong>
                        <span><?php echo esc_html($fields['location']); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($fields['project_metadata'] && is_array($fields['project_metadata'])) : ?>
                <div class="project-metadata">
                    <h3><?php _e('Project Details', 'ricelipka-theme'); ?></h3>
                    
                    <?php if ($fields['project_metadata']['square_footage']) : ?>
                        <div class="project-detail">
                            <strong><?php _e('Square Footage:', 'ricelipka-theme'); ?></strong>
                            <span><?php echo number_format($fields['project_metadata']['square_footage']); ?> sq ft</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($fields['project_metadata']['budget']) : ?>
                        <div class="project-detail">
                            <strong><?php _e('Budget:', 'ricelipka-theme'); ?></strong>
                            <span><?php echo esc_html($fields['project_metadata']['budget']); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($fields['project_metadata']['start_date']) : ?>
                        <div class="project-detail">
                            <strong><?php _e('Start Date:', 'ricelipka-theme'); ?></strong>
                            <time datetime="<?php echo esc_attr($fields['project_metadata']['start_date']); ?>">
                                <?php echo date('F j, Y', strtotime($fields['project_metadata']['start_date'])); ?>
                            </time>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($fields['project_metadata']['end_date']) : ?>
                        <div class="project-detail">
                            <strong><?php _e('End Date:', 'ricelipka-theme'); ?></strong>
                            <time datetime="<?php echo esc_attr($fields['project_metadata']['end_date']); ?>">
                                <?php echo date('F j, Y', strtotime($fields['project_metadata']['end_date'])); ?>
                            </time>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
<?php endif; ?>