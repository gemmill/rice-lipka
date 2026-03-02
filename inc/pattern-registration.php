<?php
/**
 * Centralized Pattern Registration System
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register all block patterns and pattern categories
 */
function ricelipka_register_all_patterns() {
    // Register pattern categories first
    ricelipka_register_pattern_categories();
    
    // Register patterns for each content type
    ricelipka_register_content_patterns();
    
    // Add pattern support to block editor
    add_theme_support('block-patterns');
    add_theme_support('core-block-patterns');
}
add_action('init', 'ricelipka_register_all_patterns');

/**
 * Register pattern categories
 */
function ricelipka_register_pattern_categories() {
    $categories = array(
        'ricelipka-blocks' => array(
            'label' => __('Rice+Lipka Blocks', 'ricelipka-theme'),
            'description' => __('Custom blocks for Rice+Lipka Architects content', 'ricelipka-theme'),
        ),
        'featured' => array(
            'label' => __('Featured Content', 'ricelipka-theme'),
            'description' => __('Prominent layouts for major announcements and featured stories', 'ricelipka-theme'),
        ),
        'updates' => array(
            'label' => __('Updates & Announcements', 'ricelipka-theme'),
            'description' => __('Quick update formats and brief announcements', 'ricelipka-theme'),
        ),
        'awards' => array(
            'label' => __('Awards & Recognition', 'ricelipka-theme'),
            'description' => __('Award announcements and recognition displays', 'ricelipka-theme'),
        ),
        'showcase' => array(
            'label' => __('Project Showcase', 'ricelipka-theme'),
            'description' => __('Comprehensive project presentation layouts', 'ricelipka-theme'),
        ),
        'grid' => array(
            'label' => __('Grid Layouts', 'ricelipka-theme'),
            'description' => __('Organized grid formats for multiple items', 'ricelipka-theme'),
        ),
        'case-study' => array(
            'label' => __('Case Studies', 'ricelipka-theme'),
            'description' => __('In-depth project analysis and documentation', 'ricelipka-theme'),
        ),
        'completed' => array(
            'label' => __('Completed Projects', 'ricelipka-theme'),
            'description' => __('Layouts for finished architectural projects', 'ricelipka-theme'),
        ),
        'progress' => array(
            'label' => __('In Progress', 'ricelipka-theme'),
            'description' => __('Construction progress and milestone updates', 'ricelipka-theme'),
        ),
        'planning' => array(
            'label' => __('Planning Phase', 'ricelipka-theme'),
            'description' => __('Upcoming and planned project announcements', 'ricelipka-theme'),
        ),
        'upcoming' => array(
            'label' => __('Upcoming Events', 'ricelipka-theme'),
            'description' => __('Future events with registration and details', 'ricelipka-theme'),
        ),
        'public' => array(
            'label' => __('Public Events', 'ricelipka-theme'),
            'description' => __('Community events and public gatherings', 'ricelipka-theme'),
        ),
        'workshop' => array(
            'label' => __('Workshops & Training', 'ricelipka-theme'),
            'description' => __('Professional development and educational events', 'ricelipka-theme'),
        ),
        'recurring' => array(
            'label' => __('Recurring Events', 'ricelipka-theme'),
            'description' => __('Regular meetings and event series', 'ricelipka-theme'),
        ),
        'recognition' => array(
            'label' => __('Recognition Display', 'ricelipka-theme'),
            'description' => __('Prominent award and achievement displays', 'ricelipka-theme'),
        ),
        'timeline' => array(
            'label' => __('Achievement Timeline', 'ricelipka-theme'),
            'description' => __('Chronological award and milestone displays', 'ricelipka-theme'),
        ),
        'project-awards' => array(
            'label' => __('Project Awards', 'ricelipka-theme'),
            'description' => __('Awards specific to architectural projects', 'ricelipka-theme'),
        ),
        'firm-awards' => array(
            'label' => __('Firm Recognition', 'ricelipka-theme'),
            'description' => __('Firm-wide achievements and leadership recognition', 'ricelipka-theme'),
        ),
        'individual' => array(
            'label' => __('Individual Recognition', 'ricelipka-theme'),
            'description' => __('Personal achievements and team member recognition', 'ricelipka-theme'),
        ),
    );

    foreach ($categories as $slug => $category) {
        register_block_pattern_category($slug, $category);
    }
}

/**
 * Register content patterns with enhanced metadata
 */
function ricelipka_register_content_patterns() {
    $patterns = ricelipka_get_all_patterns();
    
    foreach ($patterns as $pattern_name => $pattern_data) {
        register_block_pattern($pattern_name, $pattern_data);
    }
}

/**
 * Get all patterns with enhanced metadata
 */
function ricelipka_get_all_patterns() {
    return array(
        // News Patterns
        'ricelipka/news-featured-story' => array(
            'title'       => __('Featured Story Layout', 'ricelipka-theme'),
            'description' => __('Prominent layout for major announcements and featured news stories with large imagery and detailed content.', 'ricelipka-theme'),
            'content'     => ricelipka_get_featured_story_pattern(),
            'categories'  => array('ricelipka-blocks', 'featured'),
            'keywords'    => array('featured', 'story', 'major', 'announcement', 'prominent'),
            'blockTypes'  => array('acf/news-article'),
            'viewportWidth' => 1200,
            'inserter'    => true,
        ),
        'ricelipka/news-brief-update' => array(
            'title'       => __('Brief Update Layout', 'ricelipka-theme'),
            'description' => __('Concise layout for quick updates, progress reports, and short announcements.', 'ricelipka-theme'),
            'content'     => ricelipka_get_brief_update_pattern(),
            'categories'  => array('ricelipka-blocks', 'updates'),
            'keywords'    => array('brief', 'update', 'quick', 'short', 'progress'),
            'blockTypes'  => array('acf/news-article'),
            'viewportWidth' => 800,
            'inserter'    => true,
        ),
        'ricelipka/news-project-update' => array(
            'title'       => __('Project Update News', 'ricelipka-theme'),
            'description' => __('News article template for project construction updates and milestones.', 'ricelipka-theme'),
            'content'     => ricelipka_get_project_update_pattern(),
            'categories'  => array('ricelipka-blocks', 'updates'),
            'keywords'    => array('project', 'update', 'construction', 'milestone'),
            'blockTypes'  => array('acf/news-article'),
            'viewportWidth' => 1000,
            'inserter'    => true,
        ),
        'ricelipka/news-award-announcement' => array(
            'title'       => __('Award Announcement', 'ricelipka-theme'),
            'description' => __('News article template for announcing awards and recognition.', 'ricelipka-theme'),
            'content'     => ricelipka_get_award_announcement_pattern(),
            'categories'  => array('ricelipka-blocks', 'awards'),
            'keywords'    => array('award', 'recognition', 'achievement', 'announcement'),
            'blockTypes'  => array('acf/news-article'),
            'viewportWidth' => 1000,
            'inserter'    => true,
        ),

        // Project Patterns
        'ricelipka/project-showcase' => array(
            'title'       => __('Project Showcase Layout', 'ricelipka-theme'),
            'description' => __('Comprehensive showcase layout for highlighting major architectural projects with detailed information and visual elements.', 'ricelipka-theme'),
            'content'     => ricelipka_get_project_showcase_pattern(),
            'categories'  => array('ricelipka-blocks', 'showcase'),
            'keywords'    => array('project', 'showcase', 'portfolio', 'detailed', 'comprehensive'),
            'blockTypes'  => array('acf/project-portfolio'),
            'viewportWidth' => 1200,
            'inserter'    => true,
        ),
        'ricelipka/project-grid-view' => array(
            'title'       => __('Project Grid View', 'ricelipka-theme'),
            'description' => __('Compact grid layout for displaying multiple projects or project phases in an organized format.', 'ricelipka-theme'),
            'content'     => ricelipka_get_project_grid_pattern(),
            'categories'  => array('ricelipka-blocks', 'grid'),
            'keywords'    => array('grid', 'phases', 'organized', 'multiple', 'compact'),
            'blockTypes'  => array('acf/project-portfolio'),
            'viewportWidth' => 1000,
            'inserter'    => true,
        ),
        'ricelipka/project-case-study' => array(
            'title'       => __('Detailed Case Study', 'ricelipka-theme'),
            'description' => __('In-depth case study format for documenting project challenges, solutions, and outcomes.', 'ricelipka-theme'),
            'content'     => ricelipka_get_case_study_pattern(),
            'categories'  => array('ricelipka-blocks', 'case-study'),
            'keywords'    => array('case study', 'detailed', 'analysis', 'process', 'outcomes'),
            'blockTypes'  => array('acf/project-portfolio'),
            'viewportWidth' => 1200,
            'inserter'    => true,
        ),

        // Event Patterns
        'ricelipka/event-upcoming' => array(
            'title'       => __('Upcoming Event Layout', 'ricelipka-theme'),
            'description' => __('Comprehensive layout for upcoming events with prominent call-to-action and detailed information.', 'ricelipka-theme'),
            'content'     => ricelipka_get_upcoming_event_pattern(),
            'categories'  => array('ricelipka-blocks', 'upcoming'),
            'keywords'    => array('upcoming', 'event', 'registration', 'call-to-action'),
            'blockTypes'  => array('acf/event-details'),
            'viewportWidth' => 1200,
            'inserter'    => true,
        ),
        'ricelipka/event-workshop' => array(
            'title'       => __('Professional Workshop', 'ricelipka-theme'),
            'description' => __('Template for professional development workshops and training sessions.', 'ricelipka-theme'),
            'content'     => ricelipka_get_workshop_pattern(),
            'categories'  => array('ricelipka-blocks', 'workshop'),
            'keywords'    => array('workshop', 'training', 'professional', 'education'),
            'blockTypes'  => array('acf/event-details'),
            'viewportWidth' => 1000,
            'inserter'    => true,
        ),

        // Award Patterns
        'ricelipka/award-recognition-display' => array(
            'title'       => __('Recognition Display Layout', 'ricelipka-theme'),
            'description' => __('Prominent display layout for major awards and recognition with visual emphasis and detailed context.', 'ricelipka-theme'),
            'content'     => ricelipka_get_recognition_display_pattern(),
            'categories'  => array('ricelipka-blocks', 'recognition'),
            'keywords'    => array('recognition', 'display', 'major', 'prominent', 'award'),
            'blockTypes'  => array('acf/award-information'),
            'viewportWidth' => 1200,
            'inserter'    => true,
        ),
        'ricelipka/award-timeline' => array(
            'title'       => __('Achievement Timeline', 'ricelipka-theme'),
            'description' => __('Timeline layout for displaying multiple awards and achievements in chronological order.', 'ricelipka-theme'),
            'content'     => ricelipka_get_timeline_pattern(),
            'categories'  => array('ricelipka-blocks', 'timeline'),
            'keywords'    => array('timeline', 'chronological', 'multiple', 'history', 'achievements'),
            'blockTypes'  => array('acf/award-information'),
            'viewportWidth' => 1000,
            'inserter'    => true,
        ),
    );
}

/**
 * Pattern content generators
 */
function ricelipka_get_featured_story_pattern() {
    return '<!-- wp:acf/news-article {"data":{"news_title":"[Featured Story Title]","publication_date":"' . date('Y-m-d') . '","excerpt":"This is a major announcement or featured story that deserves prominent placement. Write a compelling summary that will draw readers in.","subcategory":"project_updates"},"mode":"preview","align":"wide"} /-->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">This featured story represents a significant milestone for Rice+Lipka Architects. Use this opening paragraph to establish the importance and context of your announcement.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Story Highlights</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Major achievement or milestone reached</li><li>Significant project completion or award</li><li>Important partnership or collaboration</li><li>Community impact or recognition</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:heading {"level":4} -->
<h4>Quick Facts</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li><strong>Date:</strong> [Event/Achievement Date]</li><li><strong>Location:</strong> [Relevant Location]</li><li><strong>Impact:</strong> [Community/Industry Impact]</li><li><strong>Recognition:</strong> [Awards/Media Coverage]</li></ul>
<!-- /wp:list -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"[Compelling quote from team member or stakeholder about the significance of this story]"</p><cite>[Name, Title]</cite></blockquote>
<!-- /wp:quote --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->';
}

function ricelipka_get_brief_update_pattern() {
    return '<!-- wp:acf/news-article {"data":{"news_title":"[Brief Update Title]","publication_date":"' . date('Y-m-d') . '","excerpt":"A concise summary of this update or announcement. Keep it brief and to the point.","subcategory":"project_updates"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>This brief update format is perfect for quick announcements, progress reports, or short news items that don\'t require extensive detail.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Key Points</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Main point or achievement</li><li>Current status or progress</li><li>Next steps or timeline</li></ul>
<!-- /wp:list -->';
}

function ricelipka_get_project_update_pattern() {
    return '<!-- wp:acf/news-article {"data":{"news_title":"Project Update: [Project Name] Reaches New Milestone","publication_date":"' . date('Y-m-d') . '","excerpt":"We are excited to share the latest progress on our [Project Name] project, which has reached a significant construction milestone.","subcategory":"project_updates"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>We are pleased to announce that construction on the [Project Name] has reached [milestone description]. This marks an important step forward in delivering this [project type] facility to the community.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Project Progress</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Current completion status: [percentage]% complete</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>Foundation work: Complete</li><li>Structural framing: [status]</li><li>Exterior envelope: [status]</li><li>Interior systems: [status]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>What\'s Next</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Over the coming weeks, our team will focus on [next phase description]. We anticipate [timeline] for the next major milestone.</p>
<!-- /wp:paragraph -->';
}

function ricelipka_get_award_announcement_pattern() {
    return '<!-- wp:acf/news-article {"data":{"news_title":"Rice+Lipka Architects Receives [Award Name]","publication_date":"' . date('Y-m-d') . '","excerpt":"We are honored to announce that Rice+Lipka Architects has been recognized with the [Award Name] for our work on the [Project Name].","subcategory":"award_notifications"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>We are thrilled to announce that Rice+Lipka Architects has been awarded the <strong>[Award Name]</strong> by the <strong>[Awarding Organization]</strong> for our exceptional work on the [Project Name] project.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>About the Award</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The [Award Name] recognizes [award criteria and significance]. This year, [number] projects were submitted for consideration, making this recognition particularly meaningful.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"[Quote from team member or client about the recognition]"</p><cite>[Name, Title]</cite></blockquote>
<!-- /wp:quote -->';
}

function ricelipka_get_project_showcase_pattern() {
    return '<!-- wp:acf/project-portfolio {"data":{"project_name":"[Project Name]","completion_status":"completed","project_type":"civic","client":"[Client Organization]","location":"[City, State]"},"mode":"preview","align":"wide"} /-->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"><!-- wp:heading {"level":3} -->
<h3>Project Overview</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">This [project type] project represents our commitment to [design philosophy or approach]. The [building type] serves [community or purpose] and demonstrates innovative approaches to [key design elements].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:heading {"level":4} -->
<h4>Key Features</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Sustainable design elements</li><li>Accessible entrances and pathways</li><li>Natural lighting optimization</li><li>Community gathering spaces</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->';
}

function ricelipka_get_project_grid_pattern() {
    return '<!-- wp:acf/project-portfolio {"data":{"project_name":"[Project Name]","completion_status":"completed","project_type":"educational","client":"[School District]","location":"[City, State]"},"mode":"preview"} /-->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Phase 1: Planning & Design</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Initial conceptual design and community engagement phase.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Phase 2: Development</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Detailed design development and permitting process.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->';
}

function ricelipka_get_case_study_pattern() {
    return '<!-- wp:acf/project-portfolio {"data":{"project_name":"[Project Name] Case Study","completion_status":"completed","project_type":"cultural","client":"[Arts Organization]","location":"[City, State]"},"mode":"preview","align":"wide"} /-->

<!-- wp:heading {"level":3} -->
<h3>Project Challenge</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Describe the primary challenge or unique requirements that this project needed to address.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Our Approach</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Explain the design methodology and approach taken to address the challenges.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Results & Impact</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Document the outcomes and impact of the project.</p>
<!-- /wp:paragraph -->';
}

function ricelipka_get_upcoming_event_pattern() {
    return '<!-- wp:acf/event-details {"data":{"event_title":"[Event Name]","event_date":"' . date('Y-m-d', strtotime('+2 weeks')) . '","event_time":"18:00","location":"[Venue Name, Address]","registration_link":"https://example.com/register","recurring_event":false},"mode":"preview","align":"wide"} /-->

<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem"}},"color":{"background":"#f8f9fa"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-background" style="background-color:#f8f9fa;padding-top:2rem;padding-bottom:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">🎯 Don\'t Miss This Event!</h3>
<!-- /wp:heading -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#register">Register Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->';
}

function ricelipka_get_workshop_pattern() {
    return '<!-- wp:acf/event-details {"data":{"event_title":"[Workshop Title]","event_date":"' . date('Y-m-d', strtotime('+3 weeks')) . '","event_time":"09:00","location":"[Training Facility, Address]","registration_link":"https://example.com/register","recurring_event":false},"mode":"preview"} /-->

<!-- wp:heading {"level":3} -->
<h3>Learning Objectives</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>[Specific skill or knowledge outcome]</li><li>[Practical application ability]</li><li>[Professional competency gained]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Workshop Schedule</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li><strong>9:00 AM - 10:30 AM:</strong> [Session 1 topic]</li><li><strong>10:45 AM - 12:15 PM:</strong> [Session 2 topic]</li></ul>
<!-- /wp:list -->';
}

function ricelipka_get_recognition_display_pattern() {
    return '<!-- wp:acf/award-information {"data":{"award_name":"[Award Name]","awarding_organization":"[Organization Name]","date_received":"' . date('Y-m-d', strtotime('-2 months')) . '"},"mode":"preview","align":"wide"} /-->

<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"3rem","bottom":"3rem"}},"color":{"background":"#f8f9fa"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-background" style="background-color:#f8f9fa;padding-top:3rem;padding-bottom:3rem"><!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">🏆 Award Recognition</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">We are honored to announce that our [Project Name] has been recognized with the <strong>[Award Name]</strong>.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
}

function ricelipka_get_timeline_pattern() {
    return '<!-- wp:acf/award-information {"data":{"award_name":"[Recent Award Name]","awarding_organization":"[Organization]","date_received":"' . date('Y-m-d', strtotime('-1 month')) . '"},"mode":"preview"} /-->

<!-- wp:heading {"level":3} -->
<h3>Our Recognition Journey</h3>
<!-- /wp:heading -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem"}},"color":{"background":"#f8f9fa"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#f8f9fa;padding-top:2rem;padding-bottom:2rem"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:heading {"level":4} -->
<h4>' . date('Y') . '</h4>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"75%"} -->
<div class="wp-block-column" style="flex-basis:75%"><!-- wp:heading {"level":5} -->
<h5>[Recent Award Name]</h5>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>[Awarding Organization]</strong> - Recognized for [achievement or project].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->';
}