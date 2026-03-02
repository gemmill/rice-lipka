<?php
/**
 * Template part for displaying people-specific fields in single posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$fields = $args['fields'] ?? array();
?>

<?php if (!empty($fields)) : ?>
    <div class="people-fields-container">
        
        <div class="person-profile-grid">
            <?php if ($fields['person_photo'] && isset($fields['person_photo']['url'])) : ?>
                <div class="person-photo-large">
                    <img src="<?php echo esc_url($fields['person_photo']['url']); ?>" 
                         alt="<?php echo esc_attr($fields['person_photo']['alt'] ?: get_the_title()); ?>" 
                         class="person-profile-image" />
                </div>
            <?php endif; ?>
            
            <div class="person-info">
                <?php if ($fields['person_role']) : ?>
                    <div class="person-detail">
                        <strong><?php _e('Role:', 'ricelipka-theme'); ?></strong>
                        <span class="person-role-display role-<?php echo esc_attr($fields['person_role']); ?>">
                            <?php echo esc_html(ucfirst(str_replace('_', ' ', $fields['person_role']))); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <?php if ($fields['person_bio']) : ?>
                    <div class="person-detail">
                        <strong><?php _e('Biography:', 'ricelipka-theme'); ?></strong>
                        <div class="person-bio-full">
                            <?php echo wp_kses_post(wpautop($fields['person_bio'])); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($fields['person_contact'] && is_array($fields['person_contact'])) : ?>
                    <div class="person-contact">
                        <h3><?php _e('Contact Information', 'ricelipka-theme'); ?></h3>
                        
                        <?php if (!empty($fields['person_contact']['email'])) : ?>
                            <div class="contact-detail">
                                <strong><?php _e('Email:', 'ricelipka-theme'); ?></strong>
                                <a href="mailto:<?php echo esc_attr($fields['person_contact']['email']); ?>">
                                    <?php echo esc_html($fields['person_contact']['email']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($fields['person_contact']['phone'])) : ?>
                            <div class="contact-detail">
                                <strong><?php _e('Phone:', 'ricelipka-theme'); ?></strong>
                                <a href="tel:<?php echo esc_attr($fields['person_contact']['phone']); ?>">
                                    <?php echo esc_html($fields['person_contact']['phone']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($fields['person_contact']['linkedin'])) : ?>
                            <div class="contact-detail">
                                <strong><?php _e('LinkedIn:', 'ricelipka-theme'); ?></strong>
                                <a href="<?php echo esc_url($fields['person_contact']['linkedin']); ?>" 
                                   target="_blank" rel="noopener noreferrer">
                                    <?php _e('View Profile', 'ricelipka-theme'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($fields['person_associations'] && is_array($fields['person_associations'])) : ?>
            <div class="person-projects-detailed">
                <h3><?php _e('Associated Projects', 'ricelipka-theme'); ?></h3>
                <div class="projects-grid">
                    <?php foreach ($fields['person_associations'] as $project) : ?>
                        <div class="project-card">
                            <?php if (has_post_thumbnail($project->ID)) : ?>
                                <div class="project-thumbnail">
                                    <a href="<?php echo get_permalink($project->ID); ?>">
                                        <?php echo get_the_post_thumbnail($project->ID, 'medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="project-info">
                                <h4>
                                    <a href="<?php echo get_permalink($project->ID); ?>">
                                        <?php echo esc_html($project->post_title); ?>
                                    </a>
                                </h4>
                                
                                <?php if ($project->post_excerpt) : ?>
                                    <p class="project-excerpt">
                                        <?php echo esc_html($project->post_excerpt); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <a href="<?php echo get_permalink($project->ID); ?>" class="project-link">
                                    <?php _e('View Project', 'ricelipka-theme'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
<?php endif; ?>