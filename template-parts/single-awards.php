<?php
/**
 * Template part for displaying award-specific fields in single posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$fields = $args['fields'] ?? array();
$associated_project = $fields['associated_project'];
?>

<?php if (!empty($fields)) : ?>
    <div class="award-fields-container">
        
        <div class="award-header-info">
            <?php if ($fields['recognition_image'] && is_array($fields['recognition_image'])) : ?>
                <div class="award-recognition-image">
                    <img src="<?php echo esc_url($fields['recognition_image']['url']); ?>" 
                         alt="<?php echo esc_attr($fields['recognition_image']['alt'] ?: get_the_title()); ?>" />
                </div>
            <?php endif; ?>
            
            <div class="award-details">
                <h3><?php _e('Award Information', 'ricelipka-theme'); ?></h3>
                
                <?php if ($fields['awarding_organization']) : ?>
                    <div class="award-detail">
                        <strong><?php _e('Awarded by:', 'ricelipka-theme'); ?></strong>
                        <span class="awarding-organization">
                            <?php echo esc_html($fields['awarding_organization']); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <?php if ($fields['date_received']) : ?>
                    <div class="award-detail">
                        <strong><?php _e('Date Received:', 'ricelipka-theme'); ?></strong>
                        <time class="award-date" datetime="<?php echo esc_attr($fields['date_received']); ?>">
                            <?php echo date('F j, Y', strtotime($fields['date_received'])); ?>
                        </time>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($associated_project && is_object($associated_project)) : ?>
            <div class="associated-project-display">
                <h3><?php _e('Associated Project', 'ricelipka-theme'); ?></h3>
                
                <div class="project-card">
                    <?php
                    // Get project thumbnail
                    $project_thumbnail = get_the_post_thumbnail($associated_project->ID, 'medium');
                    if ($project_thumbnail) : ?>
                        <div class="project-thumbnail">
                            <a href="<?php echo get_permalink($associated_project->ID); ?>">
                                <?php echo $project_thumbnail; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="project-info">
                        <h4 class="project-title">
                            <a href="<?php echo get_permalink($associated_project->ID); ?>">
                                <?php echo esc_html($associated_project->post_title); ?>
                            </a>
                        </h4>
                        
                        <?php if ($associated_project->post_excerpt) : ?>
                            <div class="project-excerpt">
                                <?php echo wp_kses_post($associated_project->post_excerpt); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="project-actions">
                            <a href="<?php echo get_permalink($associated_project->ID); ?>" 
                               class="view-project-button">
                                <?php _e('View Project Details', 'ricelipka-theme'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="award-achievement-notice">
            <div class="achievement-badge">
                <span class="achievement-icon">🏆</span>
                <div class="achievement-text">
                    <strong><?php _e('Recognition Achievement', 'ricelipka-theme'); ?></strong>
                    <p><?php _e('This award recognizes excellence in architectural design and innovation.', 'ricelipka-theme'); ?></p>
                </div>
            </div>
        </div>
        
    </div>
<?php endif; ?>