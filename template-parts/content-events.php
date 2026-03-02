<?php
/**
 * Template part for displaying event posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$event_fields = ricelipka_get_category_fields(get_the_ID());
$event_date = $event_fields['event_date'];
$event_time = $event_fields['event_time'];
$is_upcoming = $event_date && strtotime($event_date) >= strtotime('today');
$event_status = $is_upcoming ? 'upcoming' : 'past';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('event-item post-item'); ?> data-event-status="<?php echo esc_attr($event_status); ?>">
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail event-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="post-content event-content">
        <header class="entry-header">
            <h2 class="entry-title event-entry-title">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>
            
            <div class="entry-meta event-meta">
                <?php if ($event_date) : ?>
                    <div class="event-datetime">
                        <time class="event-date <?php echo $is_upcoming ? 'upcoming-date' : 'past-date'; ?>" 
                              datetime="<?php echo esc_attr($event_date); ?>">
                            <?php echo date('F j, Y', strtotime($event_date)); ?>
                        </time>
                        
                        <?php if ($event_time) : ?>
                            <time class="event-time" datetime="<?php echo esc_attr($event_time); ?>">
                                <?php echo date('g:i A', strtotime($event_time)); ?>
                            </time>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($event_fields['location']) : ?>
                    <div class="event-location">
                        <span class="location-icon">📍</span>
                        <?php echo esc_html($event_fields['location']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($event_fields['event_subcategory']) : ?>
                    <span class="event-subcategory subcategory-<?php echo esc_attr($event_fields['event_subcategory']); ?>">
                        <?php echo esc_html(ucfirst(str_replace('_', ' ', $event_fields['event_subcategory']))); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($event_fields['recurring_event']) : ?>
                    <span class="recurring-indicator">
                        <?php _e('Recurring Event', 'ricelipka-theme'); ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>
        
        <div class="entry-summary event-summary">
            <?php the_excerpt(); ?>
        </div>
        
        <footer class="entry-footer event-footer">
            <div class="event-actions">
                <a href="<?php the_permalink(); ?>" class="read-more event-read-more">
                    <?php _e('Event Details', 'ricelipka-theme'); ?>
                </a>
                
                <?php if ($is_upcoming && $event_fields['registration_link']) : ?>
                    <a href="<?php echo esc_url($event_fields['registration_link']); ?>" 
                       class="register-link" target="_blank" rel="noopener">
                        <?php _e('Register', 'ricelipka-theme'); ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <?php
            $external_links = $event_fields['external_links'];
            if ($external_links && is_array($external_links)) : ?>
                <div class="event-links">
                    <?php foreach ($external_links as $link) : ?>
                        <?php if ($link['url'] && $link['title']) : ?>
                            <a href="<?php echo esc_url($link['url']); ?>" 
                               class="external-link" target="_blank" rel="noopener">
                                <?php echo esc_html($link['title']); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </footer>
    </div>
    
</article>