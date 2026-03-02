<?php
/**
 * Template part for displaying people posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$person_fields = ricelipka_get_post_type_fields(get_the_ID());
$person_role = $person_fields['person_role'] ?: 'team-member';

// Display person photo or default
$person_photo = $person_fields['person_photo'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('person-item post-item'); ?> data-person-role="<?php echo esc_attr($person_role); ?>">
    
    <div class="person-photo">
        <?php if ($person_photo && isset($person_photo['url'])) : ?>
            <img src="<?php echo esc_url($person_photo['url']); ?>" 
                 alt="<?php echo esc_attr($person_photo['alt'] ?: get_the_title()); ?>" 
                 class="person-image" />
        <?php elseif (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium', array('class' => 'person-image')); ?>
        <?php else : ?>
            <div class="person-placeholder">
                <span class="person-initials">
                    <?php echo esc_html(substr(get_the_title(), 0, 2)); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="post-content person-content">
        <header class="entry-header">
            <h2 class="entry-title person-name">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>
            
            <?php if ($person_fields['person_role']) : ?>
                <div class="person-role role-<?php echo esc_attr($person_fields['person_role']); ?>">
                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $person_fields['person_role']))); ?>
                </div>
            <?php endif; ?>
        </header>
        
        <?php if ($person_fields['person_bio']) : ?>
            <div class="person-bio">
                <?php echo wp_kses_post(wpautop($person_fields['person_bio'])); ?>
            </div>
        <?php else : ?>
            <div class="entry-summary person-summary">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($person_fields['person_associations'] && is_array($person_fields['person_associations'])) : ?>
            <div class="person-projects">
                <h4><?php _e('Associated Projects:', 'ricelipka-theme'); ?></h4>
                <ul class="project-list">
                    <?php foreach ($person_fields['person_associations'] as $project) : ?>
                        <li>
                            <a href="<?php echo get_permalink($project->ID); ?>">
                                <?php echo esc_html($project->post_title); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <footer class="entry-footer person-footer">
            <a href="<?php the_permalink(); ?>" class="read-more person-read-more">
                <?php _e('View Profile', 'ricelipka-theme'); ?>
            </a>
        </footer>
    </div>
    
</article>