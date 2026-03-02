<?php
/**
 * Event Details Block Patterns and Templates
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register event details patterns and templates
 */
function ricelipka_register_event_patterns() {
    
    // Upcoming Event Layout Pattern
    register_block_pattern(
        'ricelipka/event-upcoming',
        array(
            'title'       => __('Upcoming Event Layout', 'ricelipka-theme'),
            'description' => __('Comprehensive layout for upcoming events with prominent call-to-action and detailed information.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/event-details {"data":{"event_title":"[Event Name]","event_date":"' . date('Y-m-d', strtotime('+2 weeks')) . '","event_time":"18:00","location":"[Venue Name, Address]","registration_link":"https://example.com/register","recurring_event":false},"mode":"preview","align":"wide"} /-->

<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem","left":"2rem","right":"2rem"}},"color":{"background":"#f8f9fa"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-background" style="background-color:#f8f9fa;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">🎯 Don\'t Miss This Event!</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">Join us for an exciting [event type] featuring [main attraction or speaker]. This [duration] event will explore [topics or themes] and provide opportunities to [networking, learning, or engagement opportunities].</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","textColor":"white","style":{"border":{"radius":"8px"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background wp-element-button" href="#register" style="border-radius:8px">Register Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"><!-- wp:heading {"level":3} -->
<h3>Event Highlights</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>[Key presentation or activity]</li><li>[Networking or social component]</li><li>[Special features or attractions]</li><li>[Refreshments or meals if applicable]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Who Should Attend</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This event is perfect for [target audience]. Whether you are [audience description], you will find valuable [benefits or takeaways].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>What to Expect</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Attendees will experience [detailed description of event activities, presentations, or experiences]. The event format includes [structure and timing details].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:heading {"level":4} -->
<h4>Event Details</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li><strong>Date:</strong> [Event Date]</li><li><strong>Time:</strong> [Start Time] - [End Time]</li><li><strong>Location:</strong> [Full Address]</li><li><strong>Cost:</strong> [Free/Price]</li><li><strong>Capacity:</strong> [Number] attendees</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":4} -->
<h4>Registration Information</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Registration Deadline:</strong> [Date]<br><strong>Contact:</strong> [Contact Information]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Space is limited, so please register early to secure your spot!</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Accessibility</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This venue is fully accessible. If you have specific accommodation needs, please contact us when registering.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
            'categories'  => array('ricelipka-blocks', 'upcoming'),
            'keywords'    => array('upcoming', 'event', 'registration', 'call-to-action'),
            'blockTypes'  => array('acf/event-details'),
        )
    );
    
    // Public Event Pattern
    register_block_pattern(
        'ricelipka/event-public',
        array(
            'title'       => __('Public Event', 'ricelipka-theme'),
            'description' => __('Template for public events like open houses, lectures, or community gatherings.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/event-details {"data":{"event_title":"[Event Name]","event_date":"' . date('Y-m-d', strtotime('+2 weeks')) . '","event_time":"18:00","location":"[Venue Name, Address]","registration_link":"https://example.com/register","recurring_event":false},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>Join us for an exciting [event type] featuring [main attraction or speaker]. This [duration] event will explore [topics or themes] and provide opportunities to [networking, learning, or engagement opportunities].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Event Highlights</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>[Key presentation or activity]</li><li>[Networking or social component]</li><li>[Special features or attractions]</li><li>[Refreshments or meals if applicable]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Who Should Attend</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This event is perfect for [target audience]. Whether you are [audience description], you will find valuable [benefits or takeaways].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Registration Information</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Cost:</strong> [Free/Price]<br><strong>Capacity:</strong> [Number] attendees<br><strong>Registration Deadline:</strong> [Date]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Space is limited, so please register early to secure your spot!</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'public'),
            'keywords'    => array('event', 'public', 'open house', 'lecture'),
            'blockTypes'  => array('acf/event-details'),
        )
    );
    
    // Professional Workshop Pattern
    register_block_pattern(
        'ricelipka/event-workshop',
        array(
            'title'       => __('Professional Workshop', 'ricelipka-theme'),
            'description' => __('Template for professional development workshops and training sessions.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/event-details {"data":{"event_title":"[Workshop Title]","event_date":"' . date('Y-m-d', strtotime('+3 weeks')) . '","event_time":"09:00","location":"[Training Facility, Address]","registration_link":"https://example.com/register","recurring_event":false},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>Enhance your professional skills with this comprehensive [duration] workshop on [topic]. Led by [instructor/facilitator], this hands-on session will provide practical knowledge and tools for [professional application].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Learning Objectives</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>By the end of this workshop, participants will be able to:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>[Specific skill or knowledge outcome]</li><li>[Practical application ability]</li><li>[Professional competency gained]</li><li>[Tool or technique mastered]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Workshop Schedule</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li><strong>9:00 AM - 10:30 AM:</strong> [Session 1 topic]</li><li><strong>10:45 AM - 12:15 PM:</strong> [Session 2 topic]</li><li><strong>1:15 PM - 2:45 PM:</strong> [Session 3 topic]</li><li><strong>3:00 PM - 4:30 PM:</strong> [Session 4 topic]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Prerequisites & Materials</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Prerequisites:</strong> [Required background or experience]<br><strong>Materials Provided:</strong> [What\'s included]<br><strong>Bring:</strong> [What participants should bring]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Continuing Education Credits:</strong> [Number] credits available for [professional organizations].</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'workshop'),
            'keywords'    => array('workshop', 'training', 'professional', 'education'),
            'blockTypes'  => array('acf/event-details'),
        )
    );
    
    // Recurring Event Pattern
    register_block_pattern(
        'ricelipka/event-recurring',
        array(
            'title'       => __('Recurring Event Series', 'ricelipka-theme'),
            'description' => __('Template for events that happen regularly, like monthly meetings or annual conferences.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/event-details {"data":{"event_title":"[Event Series Name] - [Month/Session]","event_date":"' . date('Y-m-d', strtotime('first friday of next month')) . '","event_time":"12:00","location":"[Regular Venue]","registration_link":"https://example.com/register","recurring_event":true},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>Join us for the [frequency] [event series name], a [description of series purpose]. This [month/session] we will focus on [specific topic or theme for this session].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>This Session: [Specific Topic]</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[Description of this specific session\'s content, speakers, or activities]</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>About the Series</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The [event series name] meets [frequency] to [series purpose and goals]. Each session features [typical format or structure] and provides opportunities for [networking, learning, or professional development].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Upcoming Sessions</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li><strong>[Next date]:</strong> [Next topic]</li><li><strong>[Following date]:</strong> [Following topic]</li><li><strong>[Future date]:</strong> [Future topic]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Series Membership</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>You can attend individual sessions or become a series member for [benefits]. Series membership includes [membership benefits and pricing].</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'recurring'),
            'keywords'    => array('recurring', 'series', 'monthly', 'regular'),
            'blockTypes'  => array('acf/event-details'),
        )
    );
}
add_action('init', 'ricelipka_register_event_patterns');

/**
 * Register event details block templates
 */
function ricelipka_register_event_templates() {
    // Add event-specific template when creating new event posts
    add_filter('default_content', function($content, $post) {
        if (isset($_GET['category']) && $_GET['category'] === 'events') {
            return '<!-- wp:acf/event-details {"data":{"event_title":"","event_date":"' . date('Y-m-d', strtotime('+1 week')) . '","event_time":"18:00","location":"","registration_link":"","recurring_event":false}} /-->' . "\n\n" . 
                   '<!-- wp:paragraph {"placeholder":"Describe your event here..."} -->' . "\n" .
                   '<p>Describe your event here...</p>' . "\n" .
                   '<!-- /wp:paragraph -->';
        }
        return $content;
    }, 10, 2);
}
add_action('init', 'ricelipka_register_event_templates', 20);

/**
 * Add drag-and-drop reordering support for event blocks
 */
function ricelipka_event_block_editor_settings($settings, $context) {
    if (isset($context->post) && $context->post->post_type === 'post') {
        // Get post categories to determine if this is an event post
        $categories = get_the_category($context->post->ID);
        $is_event_post = false;
        
        foreach ($categories as $category) {
            if ($category->slug === 'events') {
                $is_event_post = true;
                break;
            }
        }
        
        if ($is_event_post) {
            // Enable drag-and-drop reordering and add pattern categories
            $settings['supportsLayout'] = true;
            $settings['__experimentalBlockPatterns'] = true;
            $settings['__experimentalBlockPatternCategories'] = array_merge(
                $settings['__experimentalBlockPatternCategories'] ?? array(),
                array(
                    array(
                        'name' => 'ricelipka-events',
                        'label' => __('Event Layouts', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'upcoming',
                        'label' => __('Upcoming Events', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'public',
                        'label' => __('Public Events', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'workshop',
                        'label' => __('Workshops & Training', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'recurring',
                        'label' => __('Recurring Events', 'ricelipka-theme'),
                    )
                )
            );
        }
    }
    
    return $settings;
}
add_filter('block_editor_settings_all', 'ricelipka_event_block_editor_settings', 10, 2);