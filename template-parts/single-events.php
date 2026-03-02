<?php
/**
 * Template part for displaying event-specific fields in single posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$fields = $args['fields'] ?? array();
$is_upcoming = $fields['event_date'] && strtotime($fields['event_date']) >= strtotime('today');
?>

<?php if (!empty($fields)) : ?>
    <div class="event-fields-container">
        
        <div class="event-header-info">
            <?php if ($fields['event_date'] || $fields['event_time']) : ?>
                <div class="event-datetime-display">
                    <h3><?php _e('Event Details', 'ricelipka-theme'); ?></h3>
                    
                    <?php if ($fields['event_date']) : ?>
                        <div class="event-detail">
                            <strong><?php _e('Date:', 'ricelipka-theme'); ?></strong>
                            <time class="event-date <?php echo $is_upcoming ? 'upcoming-date' : 'past-date'; ?>" 
                                  datetime="<?php echo esc_attr($fields['event_date']); ?>">
                                <?php echo date('l, F j, Y', strtotime($fields['event_date'])); ?>
                            </time>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($fields['event_time']) : ?>
                        <div class="event-detail">
                            <strong><?php _e('Time:', 'ricelipka-theme'); ?></strong>
                            <time class="event-time" datetime="<?php echo esc_attr($fields['event_time']); ?>">
                                <?php echo date('g:i A', strtotime($fields['event_time'])); ?>
                            </time>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($fields['location']) : ?>
                <div class="event-location-display">
                    <div class="event-detail">
                        <strong><?php _e('Location:', 'ricelipka-theme'); ?></strong>
                        <span class="location-text"><?php echo esc_html($fields['location']); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($fields['recurring_event']) : ?>
                <div class="event-detail">
                    <span class="recurring-indicator">
                        <?php _e('This is a recurring event', 'ricelipka-theme'); ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($is_upcoming && $fields['registration_link']) : ?>
            <div class="event-registration">
                <a href="<?php echo esc_url($fields['registration_link']); ?>" 
                   class="register-button" target="_blank" rel="noopener">
                    <?php _e('Register for this Event', 'ricelipka-theme'); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <?php
        // Display external links if available
        $external_links = $fields['external_links'];
        if ($external_links && is_array($external_links)) : ?>
            <div class="event-external-links">
                <h3><?php _e('Related Links', 'ricelipka-theme'); ?></h3>
                <ul class="external-links-list">
                    <?php foreach ($external_links as $link) : ?>
                        <?php if ($link['url'] && $link['title']) : ?>
                            <li>
                                <a href="<?php echo esc_url($link['url']); ?>" 
                                   class="external-link" target="_blank" rel="noopener">
                                    <?php echo esc_html($link['title']); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($is_upcoming) : ?>
            <div class="event-status-notice upcoming-notice">
                <strong><?php _e('Upcoming Event', 'ricelipka-theme'); ?></strong>
                <?php
                $days_until = ceil((strtotime($fields['event_date']) - time()) / (60 * 60 * 24));
                if ($days_until > 0) {
                    printf(__('This event is in %d day%s.', 'ricelipka-theme'), $days_until, $days_until !== 1 ? 's' : '');
                } else {
                    _e('This event is today!', 'ricelipka-theme');
                }
                ?>
            </div>
        <?php else : ?>
            <div class="event-status-notice past-notice">
                <strong><?php _e('Past Event', 'ricelipka-theme'); ?></strong>
                <?php _e('This event has already taken place.', 'ricelipka-theme'); ?>
            </div>
        <?php endif; ?>
        
    </div>
<?php endif; ?>