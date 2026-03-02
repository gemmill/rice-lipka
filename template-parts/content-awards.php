<?php
/**
 * Template part for displaying award posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$award_fields = ricelipka_get_category_fields(get_the_ID());
$associated_project = $award_fields['associated_project'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('award-item post-item'); ?>>
    
    <div class="award-content-wrapper">
        <?php if (has_post_thumbnail()) : ?>
            <div class="post-thumbnail award-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium'); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="post-content award-content">
            <header class="entry-header">
                <h2 class="entry-title award-entry-title">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>
                
                <div class="entry-meta award-meta">
                    <?php if ($award_fields['awarding_organization']) : ?>
                        <div class="awarding-organization">
                            <strong><?php _e('Awarded by:', 'ricelipka-theme'); ?></strong>
                            <?php echo esc_html($award_fields['awarding_organization']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($award_fields['date_received']) : ?>
                        <time class="award-date" datetime="<?php echo esc_attr($award_fields['date_received']); ?>">
                            <?php echo date('F j, Y', strtotime($award_fields['date_received'])); ?>
                        </time>
                    <?php endif; ?>
                </div>
            </header>
            
            <?php if ($associated_project && is_object($associated_project)) : ?>
                <div class="associated-project">
                    <strong><?php _e('Project:', 'ricelipka-theme'); ?></strong>
                    <a href="<?php echo get_permalink($associated_project->ID); ?>" class="project-link">
                        <?php echo esc_html($associated_project->post_title); ?>
                    </a>
                </div>
            <?php elseif ($award_fields['project_name_text']) : ?>
                <div class="associated-project">
                    <strong><?php _e('Project:', 'ricelipka-theme'); ?></strong>
                    <span class="project-name-text">
                        <?php echo esc_html($award_fields['project_name_text']); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <div class="entry-summary award-summary">
                <?php the_excerpt(); ?>
            </div>
            
            <footer class="entry-footer award-footer">
                <div class="award-actions">
                    <a href="<?php the_permalink(); ?>" class="read-more award-read-more">
                        <?php _e('View Award Details', 'ricelipka-theme'); ?>
                    </a>
                    
                    <?php if ($associated_project && is_object($associated_project)) : ?>
                        <a href="<?php echo get_permalink($associated_project->ID); ?>" class="view-project">
                            <?php _e('View Project', 'ricelipka-theme'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </footer>
        </div>
    </div>
    
</article>