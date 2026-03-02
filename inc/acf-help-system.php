<?php
/**
 * ACF Help System for Rice+Lipka WordPress Theme
 * 
 * Provides contextual help, tooltips, and guidance for ACF fields in classic editor
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RiceLipka_ACF_Help_System {
    
    /**
     * Initialize the help system
     */
    public static function init() {
        add_action('acf/input/admin_head', array(__CLASS__, 'add_help_styles'));
        add_action('acf/input/admin_footer', array(__CLASS__, 'add_help_scripts'));
        add_filter('acf/prepare_field', array(__CLASS__, 'add_field_help'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_help_assets'));
        add_action('admin_notices', array(__CLASS__, 'show_category_help'));
    }
    
    /**
     * Show help notice based on post category
     */
    public static function show_category_help() {
        global $post, $pagenow;
        
        if (!in_array($pagenow, array('post.php', 'post-new.php')) || !$post) {
            return;
        }
        
        $categories = get_the_category($post->ID);
        if (empty($categories)) {
            return;
        }
        
        $primary_category = null;
        $primary_cats = array('news', 'projects', 'events', 'awards');
        
        foreach ($categories as $category) {
            if (in_array($category->slug, $primary_cats)) {
                $primary_category = $category->slug;
                break;
            }
        }
        
        if (!$primary_category) {
            return;
        }
        
        $help_messages = array(
            'news' => __('You\'re editing a News post. Use the content editor for the main article and fill in the ACF fields below for structured data like publication date and excerpt.', 'ricelipka-theme'),
            'projects' => __('You\'re editing a Project post. Use the content editor to describe the project and fill in the ACF fields for project details, status, and gallery images.', 'ricelipka-theme'),
            'events' => __('You\'re editing an Event post. Use the content editor for event details and fill in the ACF fields for date, time, location, and registration information.', 'ricelipka-theme'),
            'awards' => __('You\'re editing an Award post. Use the content editor to describe the award and fill in the ACF fields for organization, date received, and associated project.', 'ricelipka-theme'),
        );
        
        if (isset($help_messages[$primary_category])) {
            echo '<div class="notice notice-info is-dismissible">';
            echo '<p><strong>' . ucfirst($primary_category) . ' Post:</strong> ' . $help_messages[$primary_category] . '</p>';
            echo '</div>';
        }
    }
    
    /**
     * Get help content for specific field types
     */
    public static function get_help_content() {
        return array(
            'news' => array(
                'title' => __('News Post Fields', 'ricelipka-theme'),
                'description' => __('Fill in these fields to provide structured information for your news article.', 'ricelipka-theme'),
                'fields' => array(
                    'publication_date' => array(
                        'tooltip' => __('Select the date when this news should be published. Leave empty to use the post publication date.', 'ricelipka-theme'),
                        'help' => __('For scheduled content, choose a future date. For backdated news, select the original announcement date.', 'ricelipka-theme')
                    ),
                    'excerpt' => array(
                        'tooltip' => __('Write a brief summary (2-3 sentences) that will appear in news listings and social media shares.', 'ricelipka-theme'),
                        'help' => __('Focus on the key points: who, what, when, where. This text appears in search results and social previews.', 'ricelipka-theme')
                    ),
                    'featured_image' => array(
                        'tooltip' => __('Upload a high-quality image that represents the news story. Recommended size: 1200x630px.', 'ricelipka-theme'),
                        'help' => __('Use professional photos of projects, events, or team members. Avoid stock photos when possible.', 'ricelipka-theme')
                    ),
                    'subcategory' => array(
                        'tooltip' => __('Choose the type of news to help visitors find related content.', 'ricelipka-theme'),
                        'help' => __('Project Updates: Construction progress, completions. Event Announcements: Upcoming events. Awards: Recognition received.', 'ricelipka-theme')
                    )
                )
            ),
            'project_portfolio' => array(
                'title' => __('Project Portfolio Block Help', 'ricelipka-theme'),
                'description' => __('Showcase architectural projects with detailed information and image galleries.', 'ricelipka-theme'),
                'fields' => array(
                    'project_name' => array(
                        'tooltip' => __('Enter the official project name or building name. This will be the main heading.', 'ricelipka-theme'),
                        'help' => __('Use the name clients and the public know. Example: "Downtown Civic Center" or "Riverside Elementary School"', 'ricelipka-theme')
                    ),
                    'completion_status' => array(
                        'tooltip' => __('Select the current status of this project.', 'ricelipka-theme'),
                        'help' => __('Completed: Project is finished and occupied. In Progress: Currently under construction. Planned: Approved but not yet started.', 'ricelipka-theme')
                    ),
                    'completion_percentage' => array(
                        'tooltip' => __('For in-progress projects, enter the percentage complete (0-100).', 'ricelipka-theme'),
                        'help' => __('Base this on construction milestones: 25% (foundation), 50% (structure), 75% (exterior), 100% (complete).', 'ricelipka-theme')
                    ),
                    'project_type' => array(
                        'tooltip' => __('Select the category that best describes this project.', 'ricelipka-theme'),
                        'help' => __('Cultural: Museums, theaters, arts centers. Academic: Schools, universities. Offices: Corporate buildings. Retail & Commercial: Stores, restaurants. Institutional: Government, healthcare. Planning: Urban design, master plans. Exhibitions: Gallery installations. Research & Installation: Experimental projects.', 'ricelipka-theme')
                    ),
                    'client' => array(
                        'tooltip' => __('Enter the client organization or entity that commissioned this project.', 'ricelipka-theme'),
                        'help' => __('Use the official organization name. Example: "City of Portland" or "Portland Public Schools"', 'ricelipka-theme')
                    ),
                    'location' => array(
                        'tooltip' => __('Enter the project location (city, state or full address if appropriate).', 'ricelipka-theme'),
                        'help' => __('For public projects, include full address. For private projects, city and state may be sufficient.', 'ricelipka-theme')
                    ),
                    'image_gallery' => array(
                        'tooltip' => __('Upload multiple high-quality images showing different aspects of the project.', 'ricelipka-theme'),
                        'help' => __('Include exterior views, interior spaces, detail shots, and construction progress. Use descriptive alt text for each image.', 'ricelipka-theme')
                    ),
                    'project_year' => array(
                        'tooltip' => __('Enter the year the project was completed or is expected to be completed.', 'ricelipka-theme'),
                        'help' => __('This helps with chronological organization and filtering of projects by year.', 'ricelipka-theme')
                    )
                )
            ),
            'event_details' => array(
                'title' => __('Event Details Block Help', 'ricelipka-theme'),
                'description' => __('Create comprehensive event listings with dates, locations, and registration information.', 'ricelipka-theme'),
                'fields' => array(
                    'event_title' => array(
                        'tooltip' => __('Enter the official event name or title.', 'ricelipka-theme'),
                        'help' => __('Use the name as it appears on invitations or promotional materials. Be specific and descriptive.', 'ricelipka-theme')
                    ),
                    'event_date' => array(
                        'tooltip' => __('Select the date when the event will take place.', 'ricelipka-theme'),
                        'help' => __('For multi-day events, use the start date. The system will automatically show countdown timers for future events.', 'ricelipka-theme')
                    ),
                    'event_time' => array(
                        'tooltip' => __('Enter the event start time. Use 24-hour format (18:00) or 12-hour format (6:00 PM).', 'ricelipka-theme'),
                        'help' => __('Include the start time even for all-day events. This helps with calendar integration and time zone handling.', 'ricelipka-theme')
                    ),
                    'location' => array(
                        'tooltip' => __('Enter the event venue name and address.', 'ricelipka-theme'),
                        'help' => __('Include venue name, street address, and city. Example: "Portland Art Museum, 1219 SW Park Ave, Portland, OR"', 'ricelipka-theme')
                    ),
                    'external_links' => array(
                        'tooltip' => __('Add links to related websites, venue information, or additional resources.', 'ricelipka-theme'),
                        'help' => __('Include links to venue websites, parking information, or related event pages. Each link should have descriptive text.', 'ricelipka-theme')
                    ),
                    'registration_link' => array(
                        'tooltip' => __('If registration is required, enter the URL where visitors can sign up.', 'ricelipka-theme'),
                        'help' => __('This creates a prominent "Register Now" button. Use the direct registration URL, not a general website.', 'ricelipka-theme')
                    ),
                    'recurring_event' => array(
                        'tooltip' => __('Check this box if this event happens regularly (weekly, monthly, annually).', 'ricelipka-theme'),
                        'help' => __('This adds a visual indicator and helps visitors understand this is part of a series.', 'ricelipka-theme')
                    )
                )
            ),
            'award_information' => array(
                'title' => __('Award Information Block Help', 'ricelipka-theme'),
                'description' => __('Document awards and recognition with detailed information and project associations.', 'ricelipka-theme'),
                'fields' => array(
                    'award_name' => array(
                        'tooltip' => __('Enter the official name of the award or recognition received.', 'ricelipka-theme'),
                        'help' => __('Use the exact name as it appears on the certificate or announcement. Example: "Excellence in Civic Architecture Award"', 'ricelipka-theme')
                    ),
                    'awarding_organization' => array(
                        'tooltip' => __('Enter the name of the organization that presented this award.', 'ricelipka-theme'),
                        'help' => __('Use the full official name. Example: "American Institute of Architects" rather than "AIA"', 'ricelipka-theme')
                    ),
                    'associated_project' => array(
                        'tooltip' => __('If this award was given for a specific project, select it from the list.', 'ricelipka-theme'),
                        'help' => __('This creates automatic cross-references between awards and projects. Only select projects that directly received this recognition.', 'ricelipka-theme')
                    ),
                    'date_received' => array(
                        'tooltip' => __('Select the date when the award was received or announced.', 'ricelipka-theme'),
                        'help' => __('Use the ceremony date or official announcement date. This helps create timeline visualizations.', 'ricelipka-theme')
                    ),
                    'recognition_image' => array(
                        'tooltip' => __('Upload an image of the award certificate, trophy, or ceremony photo.', 'ricelipka-theme'),
                        'help' => __('High-quality scans of certificates work well. Include photos from award ceremonies when available.', 'ricelipka-theme')
                    )
                )
            )
        );
    }
    
    /**
     * Get tutorial content for first-time users
     */
    public static function get_tutorial_content() {
        return array(
            'news_article' => array(
                'title' => __('Creating Your First News Article', 'ricelipka-theme'),
                'steps' => array(
                    array(
                        'title' => __('Start with a Strong Headline', 'ricelipka-theme'),
                        'content' => __('Your news title should be clear and engaging. Think about what would make someone want to read more.', 'ricelipka-theme'),
                        'tip' => __('Tip: Include specific details like project names or award titles for better SEO.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Add a Compelling Image', 'ricelipka-theme'),
                        'content' => __('Upload a high-quality featured image that represents your story. This appears in social media shares and news listings.', 'ricelipka-theme'),
                        'tip' => __('Tip: Use images of actual projects or team members rather than generic stock photos.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Write Your Content', 'ricelipka-theme'),
                        'content' => __('Use the main content area below for your full article. The excerpt field is for a brief summary that appears in listings.', 'ricelipka-theme'),
                        'tip' => __('Tip: Write the excerpt after finishing your article - it\'s easier to summarize completed content.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Choose the Right Category', 'ricelipka-theme'),
                        'content' => __('Select a subcategory to help visitors find related content. This also affects how your article appears on category pages.', 'ricelipka-theme'),
                        'tip' => __('Tip: Project updates work well for construction progress, while event announcements are perfect for upcoming activities.', 'ricelipka-theme')
                    )
                )
            ),
            'project_portfolio' => array(
                'title' => __('Creating Your First Project Portfolio', 'ricelipka-theme'),
                'steps' => array(
                    array(
                        'title' => __('Project Basics', 'ricelipka-theme'),
                        'content' => __('Start with the project name, client, and location. These create the foundation for your project showcase.', 'ricelipka-theme'),
                        'tip' => __('Tip: Use names that clients and the public recognize - avoid internal project codes.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Set Project Status', 'ricelipka-theme'),
                        'content' => __('Choose whether the project is completed, in progress, or planned. For ongoing projects, add a completion percentage.', 'ricelipka-theme'),
                        'tip' => __('Tip: Update completion percentages regularly to keep stakeholders informed of progress.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Build Your Gallery', 'ricelipka-theme'),
                        'content' => __('Upload multiple images showing different aspects: exterior views, interior spaces, details, and construction progress.', 'ricelipka-theme'),
                        'tip' => __('Tip: Use descriptive alt text for each image - it helps with accessibility and SEO.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Add Project Year', 'ricelipka-theme'),
                        'content' => __('Enter the year the project was completed or is expected to be completed. This helps with chronological organization.', 'ricelipka-theme'),
                        'tip' => __('Tip: Use the actual completion year for finished projects, or expected completion year for ongoing projects.', 'ricelipka-theme')
                    )
                )
            ),
            'event_details' => array(
                'title' => __('Creating Your First Event Listing', 'ricelipka-theme'),
                'steps' => array(
                    array(
                        'title' => __('Event Essentials', 'ricelipka-theme'),
                        'content' => __('Start with the event title, date, and time. These are the most important details for visitors.', 'ricelipka-theme'),
                        'tip' => __('Tip: Use the official event name as it appears on invitations or promotional materials.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Location Details', 'ricelipka-theme'),
                        'content' => __('Include the venue name and full address. This enables map integration and helps with directions.', 'ricelipka-theme'),
                        'tip' => __('Tip: Include parking information in the event description if it\'s limited or requires special instructions.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Registration Setup', 'ricelipka-theme'),
                        'content' => __('If registration is required, add the direct registration URL. This creates a prominent "Register Now" button.', 'ricelipka-theme'),
                        'tip' => __('Tip: Test the registration link to make sure it works correctly before publishing.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Additional Resources', 'ricelipka-theme'),
                        'content' => __('Add external links for venue information, parking details, or related event pages.', 'ricelipka-theme'),
                        'tip' => __('Tip: Each link should have descriptive text that explains what visitors will find when they click.', 'ricelipka-theme')
                    )
                )
            ),
            'award_information' => array(
                'title' => __('Creating Your First Award Entry', 'ricelipka-theme'),
                'steps' => array(
                    array(
                        'title' => __('Award Details', 'ricelipka-theme'),
                        'content' => __('Enter the official award name and the organization that presented it. Use exact names as they appear on certificates.', 'ricelipka-theme'),
                        'tip' => __('Tip: Use full organization names rather than abbreviations for better recognition.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Project Association', 'ricelipka-theme'),
                        'content' => __('If the award was for a specific project, select it from the dropdown. This creates automatic cross-references.', 'ricelipka-theme'),
                        'tip' => __('Tip: Only link projects that directly received this recognition, not just projects by the same client.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Documentation', 'ricelipka-theme'),
                        'content' => __('Upload an image of the certificate, trophy, or ceremony photo. High-quality scans work best for certificates.', 'ricelipka-theme'),
                        'tip' => __('Tip: Include ceremony photos when available - they add personal context to the achievement.', 'ricelipka-theme')
                    ),
                    array(
                        'title' => __('Timeline Context', 'ricelipka-theme'),
                        'content' => __('Set the date when the award was received. This helps create timeline visualizations and shows your firm\'s growth.', 'ricelipka-theme'),
                        'tip' => __('Tip: Use the ceremony date or official announcement date for consistency.', 'ricelipka-theme')
                    )
                )
            )
        );
    }
    
    /**
     * Add field-specific help content
     */
    public static function add_field_help($field) {
        $help_content = self::get_help_content();
        
        // Determine which block type we're in based on field names
        $block_type = self::detect_block_type($field);
        
        if ($block_type && isset($help_content[$block_type]['fields'][$field['name']])) {
            $field_help = $help_content[$block_type]['fields'][$field['name']];
            
            // Add tooltip to field instructions
            if (isset($field_help['tooltip'])) {
                $field['instructions'] = $field_help['tooltip'];
            }
            
            // Add help icon and expanded help content
            if (isset($field_help['help'])) {
                $field['wrapper']['data-help'] = $field_help['help'];
                $field['wrapper']['class'] = isset($field['wrapper']['class']) ? 
                    $field['wrapper']['class'] . ' has-help-content' : 'has-help-content';
            }
        }
        
        return $field;
    }
    
    /**
     * Detect which block type we're working with based on field names
     */
    private static function detect_block_type($field) {
        $field_mappings = array(
            'news_title' => 'news_article',
            'publication_date' => 'news_article',
            'project_name' => 'project_portfolio',
            'completion_status' => 'project_portfolio',
            'event_title' => 'event_details',
            'event_date' => 'event_details',
            'award_name' => 'award_information',
            'awarding_organization' => 'award_information'
        );
        
        return isset($field_mappings[$field['name']]) ? $field_mappings[$field['name']] : null;
    }
    
    /**
     * Enqueue help system assets
     */
    public static function enqueue_help_assets($hook) {
        if ($hook !== 'post.php' && $hook !== 'post-new.php') {
            return;
        }
        
        wp_enqueue_style(
            'ricelipka-acf-help',
            get_template_directory_uri() . '/assets/css/acf-help.css',
            array(),
            wp_get_theme()->get('Version')
        );
        
        wp_enqueue_script(
            'ricelipka-acf-help',
            get_template_directory_uri() . '/assets/js/acf-help.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
        
        wp_localize_script('ricelipka-acf-help', 'acfHelp', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('acf_help_nonce'),
            'tutorials' => self::get_tutorial_content(),
            'help_content' => self::get_help_content(),
            'strings' => array(
                'show_help' => __('Show Help', 'ricelipka-theme'),
                'hide_help' => __('Hide Help', 'ricelipka-theme'),
                'start_tutorial' => __('Start Tutorial', 'ricelipka-theme'),
                'next_step' => __('Next Step', 'ricelipka-theme'),
                'previous_step' => __('Previous Step', 'ricelipka-theme'),
                'finish_tutorial' => __('Finish Tutorial', 'ricelipka-theme'),
                'skip_tutorial' => __('Skip Tutorial', 'ricelipka-theme')
            )
        ));
    }
    
    /**
     * Add help system styles to admin head
     */
    public static function add_help_styles() {
        ?>
        <style>
        .acf-help-tooltip {
            position: relative;
            display: inline-block;
            margin-left: 5px;
        }
        
        .acf-help-icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            background: #0073aa;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 16px;
            font-size: 12px;
            cursor: help;
            font-weight: bold;
        }
        
        .acf-help-content {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.4;
            white-space: nowrap;
            max-width: 300px;
            white-space: normal;
            z-index: 999999;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            display: none;
        }
        
        .acf-help-content:after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #333;
        }
        
        .acf-help-tooltip:hover .acf-help-content {
            display: block;
        }
        
        .acf-tutorial-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 999998;
            display: none;
        }
        
        .acf-tutorial-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 8px;
            padding: 0;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow: hidden;
            z-index: 999999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .acf-tutorial-header {
            background: #0073aa;
            color: white;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .acf-tutorial-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .acf-tutorial-progress {
            margin-top: 10px;
            background: rgba(255,255,255,0.2);
            height: 4px;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .acf-tutorial-progress-bar {
            background: white;
            height: 100%;
            transition: width 0.3s ease;
        }
        
        .acf-tutorial-body {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .acf-tutorial-step {
            display: none;
        }
        
        .acf-tutorial-step.active {
            display: block;
        }
        
        .acf-tutorial-step h3 {
            margin-top: 0;
            color: #0073aa;
        }
        
        .acf-tutorial-tip {
            background: #f0f6fc;
            border-left: 4px solid #0073aa;
            padding: 12px;
            margin-top: 15px;
            font-style: italic;
        }
        
        .acf-tutorial-footer {
            background: #f9f9f9;
            padding: 15px 20px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .acf-tutorial-nav {
            display: flex;
            gap: 10px;
        }
        
        .acf-tutorial-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        
        .acf-tutorial-btn-primary {
            background: #0073aa;
            color: white;
        }
        
        .acf-tutorial-btn-primary:hover {
            background: #005a87;
        }
        
        .acf-tutorial-btn-secondary {
            background: #f3f4f5;
            color: #50575e;
        }
        
        .acf-tutorial-btn-secondary:hover {
            background: #e9eaeb;
        }
        
        .acf-onboarding-banner {
            background: linear-gradient(135deg, #0073aa 0%, #005a87 100%);
            color: white;
            padding: 15px 20px;
            margin: 0 0 20px 0;
            border-radius: 6px;
            position: relative;
        }
        
        .acf-onboarding-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .acf-onboarding-text h4 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        
        .acf-onboarding-text p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .acf-onboarding-actions {
            display: flex;
            gap: 10px;
        }
        
        .acf-onboarding-btn {
            padding: 8px 16px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }
        
        .acf-onboarding-btn:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.5);
        }
        
        .acf-onboarding-close {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        
        .acf-onboarding-close:hover {
            opacity: 1;
        }
        </style>
        <?php
    }
    
    /**
     * Add help system JavaScript to admin footer
     */
    public static function add_help_scripts() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Add help icons to fields with help content
            $('.has-help-content .acf-label label').each(function() {
                var $label = $(this);
                var $field = $label.closest('.acf-field');
                var helpText = $field.data('help');
                
                if (helpText) {
                    var $helpIcon = $('<span class="acf-help-tooltip">' +
                        '<span class="acf-help-icon">?</span>' +
                        '<div class="acf-help-content">' + helpText + '</div>' +
                        '</span>');
                    
                    $label.append($helpIcon);
                }
            });
            
            // Show onboarding banner for first-time users
            if (!localStorage.getItem('acf_help_onboarding_dismissed')) {
                showOnboardingBanner();
            }
            
            function showOnboardingBanner() {
                var blockType = detectCurrentBlockType();
                if (!blockType) return;
                
                var $banner = $('<div class="acf-onboarding-banner">' +
                    '<button class="acf-onboarding-close">&times;</button>' +
                    '<div class="acf-onboarding-content">' +
                        '<div class="acf-onboarding-text">' +
                            '<h4>Welcome to ACF Blocks!</h4>' +
                            '<p>New to creating ' + blockType.replace('_', ' ') + ' content? We can help you get started.</p>' +
                        '</div>' +
                        '<div class="acf-onboarding-actions">' +
                            '<button class="acf-onboarding-btn" data-action="tutorial">Start Tutorial</button>' +
                            '<button class="acf-onboarding-btn" data-action="dismiss">Maybe Later</button>' +
                        '</div>' +
                    '</div>' +
                '</div>');
                
                $('.acf-fields').first().prepend($banner);
            }
            
            function detectCurrentBlockType() {
                if ($('[data-name="news_title"]').length) return 'news_article';
                if ($('[data-name="project_name"]').length) return 'project_portfolio';
                if ($('[data-name="event_title"]').length) return 'event_details';
                if ($('[data-name="award_name"]').length) return 'award_information';
                return null;
            }
            
            // Handle onboarding banner actions
            $(document).on('click', '.acf-onboarding-btn', function() {
                var action = $(this).data('action');
                
                if (action === 'tutorial') {
                    startTutorial();
                } else if (action === 'dismiss') {
                    dismissOnboarding();
                }
            });
            
            $(document).on('click', '.acf-onboarding-close', function() {
                dismissOnboarding();
            });
            
            function dismissOnboarding() {
                $('.acf-onboarding-banner').fadeOut();
                localStorage.setItem('acf_help_onboarding_dismissed', 'true');
            }
            
            function startTutorial() {
                var blockType = detectCurrentBlockType();
                if (!blockType || !window.acfHelp || !window.acfHelp.tutorials[blockType]) {
                    return;
                }
                
                dismissOnboarding();
                showTutorialModal(blockType);
            }
            
            function showTutorialModal(blockType) {
                var tutorial = window.acfHelp.tutorials[blockType];
                var currentStep = 0;
                
                var $overlay = $('<div class="acf-tutorial-overlay"></div>');
                var $modal = $('<div class="acf-tutorial-modal">' +
                    '<div class="acf-tutorial-header">' +
                        '<h2 class="acf-tutorial-title">' + tutorial.title + '</h2>' +
                        '<div class="acf-tutorial-progress">' +
                            '<div class="acf-tutorial-progress-bar"></div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="acf-tutorial-body">' +
                        generateTutorialSteps(tutorial.steps) +
                    '</div>' +
                    '<div class="acf-tutorial-footer">' +
                        '<div class="acf-tutorial-step-info">' +
                            '<span class="acf-tutorial-step-current">1</span> of ' +
                            '<span class="acf-tutorial-step-total">' + tutorial.steps.length + '</span>' +
                        '</div>' +
                        '<div class="acf-tutorial-nav">' +
                            '<button class="acf-tutorial-btn acf-tutorial-btn-secondary acf-tutorial-prev" disabled>Previous</button>' +
                            '<button class="acf-tutorial-btn acf-tutorial-btn-primary acf-tutorial-next">Next Step</button>' +
                            '<button class="acf-tutorial-btn acf-tutorial-btn-secondary acf-tutorial-skip">Skip Tutorial</button>' +
                        '</div>' +
                    '</div>' +
                '</div>');
                
                $('body').append($overlay).append($modal);
                $overlay.fadeIn();
                
                updateTutorialStep(currentStep, tutorial.steps.length);
                
                // Tutorial navigation
                $modal.on('click', '.acf-tutorial-next', function() {
                    if (currentStep < tutorial.steps.length - 1) {
                        currentStep++;
                        updateTutorialStep(currentStep, tutorial.steps.length);
                    } else {
                        closeTutorial();
                    }
                });
                
                $modal.on('click', '.acf-tutorial-prev', function() {
                    if (currentStep > 0) {
                        currentStep--;
                        updateTutorialStep(currentStep, tutorial.steps.length);
                    }
                });
                
                $modal.on('click', '.acf-tutorial-skip', closeTutorial);
                $overlay.on('click', closeTutorial);
                
                function updateTutorialStep(step, total) {
                    $('.acf-tutorial-step').removeClass('active');
                    $('.acf-tutorial-step').eq(step).addClass('active');
                    
                    $('.acf-tutorial-step-current').text(step + 1);
                    $('.acf-tutorial-progress-bar').css('width', ((step + 1) / total * 100) + '%');
                    
                    $('.acf-tutorial-prev').prop('disabled', step === 0);
                    
                    if (step === total - 1) {
                        $('.acf-tutorial-next').text(window.acfHelp.strings.finish_tutorial);
                    } else {
                        $('.acf-tutorial-next').text(window.acfHelp.strings.next_step);
                    }
                }
                
                function closeTutorial() {
                    $overlay.fadeOut(function() {
                        $overlay.remove();
                        $modal.remove();
                    });
                }
            }
            
            function generateTutorialSteps(steps) {
                var html = '';
                steps.forEach(function(step, index) {
                    html += '<div class="acf-tutorial-step' + (index === 0 ? ' active' : '') + '">' +
                        '<h3>' + step.title + '</h3>' +
                        '<p>' + step.content + '</p>';
                    
                    if (step.tip) {
                        html += '<div class="acf-tutorial-tip">' + step.tip + '</div>';
                    }
                    
                    html += '</div>';
                });
                return html;
            }
        });
        </script>
        <?php
    }
    
    /**
     * Handle AJAX request to dismiss tutorial
     */
    public static function dismiss_tutorial() {
        check_ajax_referer('acf_help_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        update_user_meta($user_id, 'acf_help_tutorial_dismissed', true);
        
        wp_send_json_success();
    }
}

// Initialize the help system
RiceLipka_ACF_Help_System::init();