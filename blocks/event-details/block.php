<?php
/**
 * Event Details Block Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int $post_id The post ID this block is saved to.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Create id attribute allowing for custom "anchor" value.
$id = 'event-details-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'event-details-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get ACF fields
$event_date = get_field('event_date');
$event_time = get_field('event_time');
$location = get_field('location');
$external_links = get_field('external_links');
$registration_link = get_field('registration_link');
$recurring_event = get_field('recurring_event');

// Use post title
$event_title = get_the_title();

// Handle preview mode
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];
if ($is_preview) {
    $event_title = 'Sample Architecture Event';
    $event_date = $block['data']['event_date'] ?? date('Y-m-d', strtotime('+1 week'));
    $event_time = $block['data']['event_time'] ?? '18:00';
    $location = $block['data']['location'] ?? 'Sample Venue, City';
    $registration_link = $block['data']['registration_link'] ?? 'https://example.com/register';
    $recurring_event = $block['data']['recurring_event'] ?? false;
    
    // Create sample external links for preview
    $external_links = array(
        array(
            'link_text' => 'Event Website',
            'link_url' => 'https://example.com/event',
            'link_description' => 'Official event website with full details'
        ),
        array(
            'link_text' => 'Venue Information',
            'link_url' => 'https://example.com/venue',
            'link_description' => 'Directions and venue details'
        )
    );
}

// Don't render if no event title
if (empty($event_title) && !$is_preview) {
    if (is_admin()) {
        echo '<div class="acf-block-placeholder"><p>' . __('Please enter a post title to display this block.', 'ricelipka-theme') . '</p></div>';
    }
    return;
}

// Calculate event status and countdown
$event_datetime = null;
$is_past_event = false;
$countdown_data = null;

if ($event_date) {
    $event_datetime_str = $event_date;
    if ($event_time) {
        $event_datetime_str .= ' ' . $event_time;
    }
    $event_datetime = strtotime($event_datetime_str);
    $is_past_event = $event_datetime < time();
    
    if (!$is_past_event) {
        $countdown_data = array(
            'target' => date('c', $event_datetime),
            'days' => floor(($event_datetime - time()) / (60 * 60 * 24)),
            'hours' => floor((($event_datetime - time()) % (60 * 60 * 24)) / (60 * 60)),
            'minutes' => floor((($event_datetime - time()) % (60 * 60)) / 60)
        );
    }
}

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" 
     data-event-date="<?php echo $event_datetime ? esc_attr(date('c', $event_datetime)) : ''; ?>"
     data-is-past="<?php echo $is_past_event ? 'true' : 'false'; ?>">
    
    <?php if ($is_preview) : ?>
        <div class="block-preview-indicator">
            <span>📅 Event Details Preview</span>
        </div>
    <?php endif; ?>
    
    <!-- Event Header -->
    <header class="event-header">
        <h2 class="event-title"><?php echo esc_html($event_title); ?></h2>
        
        <?php if ($recurring_event) : ?>
            <span class="event-recurring-badge">
                <span class="recurring-icon">🔄</span>
                <?php _e('Recurring Event', 'ricelipka-theme'); ?>
            </span>
        <?php endif; ?>
        
        <?php if ($is_past_event) : ?>
            <span class="event-status-badge past-event">
                <?php _e('Past Event', 'ricelipka-theme'); ?>
            </span>
        <?php elseif ($event_datetime) : ?>
            <span class="event-status-badge upcoming-event">
                <?php _e('Upcoming Event', 'ricelipka-theme'); ?>
            </span>
        <?php endif; ?>
    </header>
    
    <!-- Event Meta Information -->
    <div class="event-meta">
        <?php if ($event_date) : ?>
            <div class="event-date-time">
                <div class="event-date">
                    <span class="date-icon">📅</span>
                    <time datetime="<?php echo esc_attr($event_date); ?>">
                        <?php echo date_i18n('l, F j, Y', strtotime($event_date)); ?>
                    </time>
                </div>
                
                <?php if ($event_time) : ?>
                    <div class="event-time">
                        <span class="time-icon">🕐</span>
                        <time datetime="<?php echo esc_attr($event_time); ?>">
                            <?php echo date_i18n('g:i A', strtotime($event_time)); ?>
                        </time>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($location) : ?>
            <div class="event-location">
                <span class="location-icon">📍</span>
                <address><?php echo esc_html($location); ?></address>
                <button class="location-map-btn" data-location="<?php echo esc_attr($location); ?>">
                    <?php _e('View on Map', 'ricelipka-theme'); ?>
                </button>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Countdown Timer (for upcoming events) -->
    <?php if (!$is_past_event && $countdown_data && !$is_preview) : ?>
        <div class="event-countdown" data-target="<?php echo esc_attr($countdown_data['target']); ?>">
            <h3 class="countdown-title"><?php _e('Event Starts In:', 'ricelipka-theme'); ?></h3>
            <div class="countdown-timer">
                <div class="countdown-unit">
                    <span class="countdown-number" data-unit="days"><?php echo $countdown_data['days']; ?></span>
                    <span class="countdown-label"><?php _e('Days', 'ricelipka-theme'); ?></span>
                </div>
                <div class="countdown-unit">
                    <span class="countdown-number" data-unit="hours"><?php echo $countdown_data['hours']; ?></span>
                    <span class="countdown-label"><?php _e('Hours', 'ricelipka-theme'); ?></span>
                </div>
                <div class="countdown-unit">
                    <span class="countdown-number" data-unit="minutes"><?php echo $countdown_data['minutes']; ?></span>
                    <span class="countdown-label"><?php _e('Minutes', 'ricelipka-theme'); ?></span>
                </div>
                <div class="countdown-unit">
                    <span class="countdown-number" data-unit="seconds">0</span>
                    <span class="countdown-label"><?php _e('Seconds', 'ricelipka-theme'); ?></span>
                </div>
            </div>
        </div>
    <?php elseif ($is_preview && !$is_past_event) : ?>
        <div class="event-countdown preview-countdown">
            <h3 class="countdown-title"><?php _e('Event Starts In:', 'ricelipka-theme'); ?></h3>
            <div class="countdown-timer">
                <div class="countdown-unit">
                    <span class="countdown-number">7</span>
                    <span class="countdown-label"><?php _e('Days', 'ricelipka-theme'); ?></span>
                </div>
                <div class="countdown-unit">
                    <span class="countdown-number">12</span>
                    <span class="countdown-label"><?php _e('Hours', 'ricelipka-theme'); ?></span>
                </div>
                <div class="countdown-unit">
                    <span class="countdown-number">34</span>
                    <span class="countdown-label"><?php _e('Minutes', 'ricelipka-theme'); ?></span>
                </div>
                <div class="countdown-unit">
                    <span class="countdown-number">56</span>
                    <span class="countdown-label"><?php _e('Seconds', 'ricelipka-theme'); ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Event Description -->
    <?php if (get_the_content() && !$is_preview) : ?>
        <div class="event-description">
            <?php the_content(); ?>
        </div>
    <?php elseif ($is_preview) : ?>
        <div class="event-description">
            <p><?php _e('Join us for an exciting architectural event featuring presentations from leading industry professionals. This event will showcase innovative design approaches and sustainable building practices.', 'ricelipka-theme'); ?></p>
            <p><?php _e('Network with fellow architects, designers, and industry experts while exploring the latest trends in civic architecture and community-focused design.', 'ricelipka-theme'); ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Registration and Action Buttons -->
    <div class="event-actions">
        <?php if ($registration_link && !$is_past_event) : ?>
            <a href="<?php echo esc_url($registration_link); ?>" 
               class="event-register-btn" 
               target="_blank" 
               rel="noopener noreferrer">
                <?php _e('Register Now', 'ricelipka-theme'); ?>
                <span class="register-icon">→</span>
            </a>
        <?php endif; ?>
        
        <?php if ($event_datetime) : ?>
            <button class="add-to-calendar-btn" data-event-data="<?php echo esc_attr(json_encode(array(
                'title' => $event_title,
                'start' => date('Ymd\THis', $event_datetime),
                'end' => date('Ymd\THis', $event_datetime + 7200), // 2 hours default
                'location' => $location,
                'description' => wp_strip_all_tags(get_the_content())
            ))); ?>">
                <span class="calendar-icon">📅</span>
                <?php _e('Add to Calendar', 'ricelipka-theme'); ?>
            </button>
        <?php endif; ?>
        
        <button class="share-event-btn">
            <span class="share-icon">📤</span>
            <?php _e('Share Event', 'ricelipka-theme'); ?>
        </button>
    </div>
    
    <!-- External Links -->
    <?php if ($external_links && is_array($external_links) && count($external_links) > 0) : ?>
        <div class="event-external-links">
            <h3><?php _e('Related Links', 'ricelipka-theme'); ?></h3>
            <ul class="external-links-list">
                <?php foreach ($external_links as $link) : 
                    if (empty($link['link_url']) || empty($link['link_text'])) continue;
                ?>
                    <li class="external-link-item">
                        <a href="<?php echo esc_url($link['link_url']); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="external-link">
                            <span class="link-text"><?php echo esc_html($link['link_text']); ?></span>
                            <span class="external-icon">↗</span>
                        </a>
                        <?php if (!empty($link['link_description'])) : ?>
                            <p class="link-description"><?php echo esc_html($link['link_description']); ?></p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- Calendar Integration Modal (hidden by default) -->
    <div class="calendar-modal" id="calendar-modal-<?php echo esc_attr($block['id']); ?>" style="display: none;">
        <div class="calendar-modal-content">
            <div class="calendar-modal-header">
                <h3><?php _e('Add to Calendar', 'ricelipka-theme'); ?></h3>
                <button class="calendar-modal-close" aria-label="<?php esc_attr_e('Close calendar modal', 'ricelipka-theme'); ?>">&times;</button>
            </div>
            <div class="calendar-modal-body">
                <p><?php _e('Choose your preferred calendar application:', 'ricelipka-theme'); ?></p>
                <div class="calendar-options">
                    <a href="#" class="calendar-option google-calendar" data-calendar="google">
                        <span class="calendar-icon">📅</span>
                        <?php _e('Google Calendar', 'ricelipka-theme'); ?>
                    </a>
                    <a href="#" class="calendar-option outlook-calendar" data-calendar="outlook">
                        <span class="calendar-icon">📅</span>
                        <?php _e('Outlook', 'ricelipka-theme'); ?>
                    </a>
                    <a href="#" class="calendar-option ics-download" data-calendar="ics">
                        <span class="calendar-icon">📥</span>
                        <?php _e('Download ICS File', 'ricelipka-theme'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Share Modal (hidden by default) -->
    <div class="share-modal" id="share-modal-<?php echo esc_attr($block['id']); ?>" style="display: none;">
        <div class="share-modal-content">
            <div class="share-modal-header">
                <h3><?php _e('Share Event', 'ricelipka-theme'); ?></h3>
                <button class="share-modal-close" aria-label="<?php esc_attr_e('Close share modal', 'ricelipka-theme'); ?>">&times;</button>
            </div>
            <div class="share-modal-body">
                <div class="share-options">
                    <a href="#" class="share-option facebook" data-share="facebook">
                        <span class="share-icon">f</span>
                        <?php _e('Facebook', 'ricelipka-theme'); ?>
                    </a>
                    <a href="#" class="share-option twitter" data-share="twitter">
                        <span class="share-icon">𝕏</span>
                        <?php _e('Twitter', 'ricelipka-theme'); ?>
                    </a>
                    <a href="#" class="share-option linkedin" data-share="linkedin">
                        <span class="share-icon">in</span>
                        <?php _e('LinkedIn', 'ricelipka-theme'); ?>
                    </a>
                    <a href="#" class="share-option email" data-share="email">
                        <span class="share-icon">✉</span>
                        <?php _e('Email', 'ricelipka-theme'); ?>
                    </a>
                    <button class="share-option copy-link" data-share="copy">
                        <span class="share-icon">🔗</span>
                        <?php _e('Copy Link', 'ricelipka-theme'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($is_preview) : ?>
        <div class="block-preview-footer">
            <small><?php _e('This is how your event details will appear to visitors', 'ricelipka-theme'); ?></small>
        </div>
    <?php endif; ?>
</div>

<?php if ($is_preview) : ?>
<style>
.block-preview-indicator {
    background: #0073aa;
    color: white;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: 1rem;
    border-radius: 4px;
}

.block-preview-footer {
    background: #f0f0f1;
    padding: 0.75rem 1rem;
    text-align: center;
    color: #646970;
    border-top: 1px solid #dcdcde;
    margin-top: 1rem;
}

.block-preview-footer small {
    font-size: 0.8125rem;
}

.preview-countdown .countdown-timer {
    opacity: 0.8;
}
</style>
<?php endif; ?>