<?php
/**
 * Category-based field management
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create ACF field groups for each category
 */
function ricelipka_create_acf_field_groups() {
    // Check if ACF Pro is active
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // News Category Field Group
    acf_add_local_field_group(array(
        'key' => 'group_news_fields',
        'title' => 'News Fields',
        'fields' => array(
            array(
                'key' => 'field_publication_date',
                'label' => 'Publication Date',
                'name' => 'publication_date',
                'type' => 'date_picker',
                'instructions' => 'Select the publication date',
                'display_format' => 'F j, Y',
                'return_format' => 'Y-m-d',
                'first_day' => 1,
            ),
            array(
                'key' => 'field_news_excerpt',
                'label' => 'Excerpt',
                'name' => 'excerpt',
                'type' => 'textarea',
                'instructions' => 'Brief summary of the news article',
                'rows' => 3,
            ),
            array(
                'key' => 'field_featured_image',
                'label' => 'Featured Image',
                'name' => 'featured_image',
                'type' => 'image',
                'instructions' => 'Upload a featured image for the news article',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
                array(
                    'param' => 'post_category',
                    'operator' => '==',
                    'value' => 'category:news',
                ),
            ),
        ),
    ));

    // Projects Category Field Group
    acf_add_local_field_group(array(
        'key' => 'group_projects_fields',
        'title' => 'Project Fields',
        'fields' => array(
            array(
                'key' => 'field_completion_status',
                'label' => 'Completion Status',
                'name' => 'completion_status',
                'type' => 'select',
                'instructions' => 'Select the project completion status',
                'choices' => array(
                    'completed' => 'Completed',
                    'in_progress' => 'In Progress',
                    'planned' => 'Planned',
                ),
                'required' => 1,
            ),
            array(
                'key' => 'field_completion_percentage',
                'label' => 'Completion Percentage',
                'name' => 'completion_percentage',
                'type' => 'number',
                'instructions' => 'Enter completion percentage (0-100)',
                'min' => 0,
                'max' => 100,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_completion_status',
                            'operator' => '==',
                            'value' => 'in_progress',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_project_type',
                'label' => 'Project Type',
                'name' => 'project_type',
                'type' => 'select',
                'instructions' => 'Select the project type',
                'choices' => array(
                    'civic_architecture' => 'Civic Architecture',
                    'cultural_projects' => 'Cultural Projects',
                    'educational_buildings' => 'Educational Buildings',
                    'public_works' => 'Public Works',
                    'residential' => 'Residential',
                    'commercial' => 'Commercial',
                    'mixed_use' => 'Mixed Use',
                ),
                'required' => 1,
            ),
            array(
                'key' => 'field_client',
                'label' => 'Client',
                'name' => 'client',
                'type' => 'text',
                'instructions' => 'Enter the client organization',
            ),
            array(
                'key' => 'field_location',
                'label' => 'Location',
                'name' => 'location',
                'type' => 'text',
                'instructions' => 'Enter the project location',
            ),
            array(
                'key' => 'field_image_gallery',
                'label' => 'Image Gallery',
                'name' => 'image_gallery',
                'type' => 'gallery',
                'instructions' => 'Upload project images',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_project_metadata',
                'label' => 'Project Metadata',
                'name' => 'project_metadata',
                'type' => 'group',
                'instructions' => 'Additional project details',
                'sub_fields' => array(
                    array(
                        'key' => 'field_square_footage',
                        'label' => 'Square Footage',
                        'name' => 'square_footage',
                        'type' => 'number',
                    ),
                    array(
                        'key' => 'field_budget',
                        'label' => 'Budget',
                        'name' => 'budget',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_start_date',
                        'label' => 'Start Date',
                        'name' => 'start_date',
                        'type' => 'date_picker',
                    ),
                    array(
                        'key' => 'field_end_date',
                        'label' => 'End Date',
                        'name' => 'end_date',
                        'type' => 'date_picker',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
                array(
                    'param' => 'post_category',
                    'operator' => '==',
                    'value' => 'category:projects',
                ),
            ),
        ),
    ));

    // Events Category Field Group
    acf_add_local_field_group(array(
        'key' => 'group_events_fields',
        'title' => 'Event Fields',
        'fields' => array(
            array(
                'key' => 'field_event_date',
                'label' => 'Event Date',
                'name' => 'event_date',
                'type' => 'date_picker',
                'instructions' => 'Select the event date',
                'display_format' => 'F j, Y',
                'return_format' => 'Y-m-d',
                'required' => 1,
            ),
            array(
                'key' => 'field_event_time',
                'label' => 'Event Time',
                'name' => 'event_time',
                'type' => 'time_picker',
                'instructions' => 'Select the event time',
                'display_format' => 'g:i a',
                'return_format' => 'H:i:s',
            ),
            array(
                'key' => 'field_event_location',
                'label' => 'Location',
                'name' => 'location',
                'type' => 'text',
                'instructions' => 'Enter the event location',
                'required' => 1,
            ),
            array(
                'key' => 'field_external_links',
                'label' => 'External Links',
                'name' => 'external_links',
                'type' => 'repeater',
                'instructions' => 'Add related links',
                'sub_fields' => array(
                    array(
                        'key' => 'field_link_title',
                        'label' => 'Link Title',
                        'name' => 'title',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_link_url',
                        'label' => 'URL',
                        'name' => 'url',
                        'type' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_registration_link',
                'label' => 'Registration Link',
                'name' => 'registration_link',
                'type' => 'url',
                'instructions' => 'Enter the registration URL',
            ),
            array(
                'key' => 'field_recurring_event',
                'label' => 'Recurring Event',
                'name' => 'recurring_event',
                'type' => 'true_false',
                'instructions' => 'Check if this is a recurring event',
            ),
            array(
                'key' => 'field_event_subcategory',
                'label' => 'Event Type',
                'name' => 'event_subcategory',
                'type' => 'select',
                'instructions' => 'Select the event type',
                'choices' => array(
                    'conferences' => 'Conferences',
                    'workshops' => 'Workshops',
                    'exhibitions' => 'Exhibitions',
                    'lectures' => 'Lectures',
                    'community_events' => 'Community Events',
                    'awards_ceremonies' => 'Awards Ceremonies',
                    'networking' => 'Networking Events',
                ),
                'allow_null' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
                array(
                    'param' => 'post_category',
                    'operator' => '==',
                    'value' => 'category:events',
                ),
            ),
        ),
    ));

    // Awards Category Field Group
    acf_add_local_field_group(array(
        'key' => 'group_awards_fields',
        'title' => 'Award Fields',
        'fields' => array(
            array(
                'key' => 'field_awarding_organization',
                'label' => 'Awarding Organization',
                'name' => 'awarding_organization',
                'type' => 'text',
                'instructions' => 'Enter the organization name',
                'required' => 1,
            ),
            array(
                'key' => 'field_associated_project',
                'label' => 'Associated Project',
                'name' => 'associated_project',
                'type' => 'post_object',
                'instructions' => 'Link to the associated project',
                'post_type' => array('post'),
                'taxonomy' => array('category:projects'),
                'return_format' => 'object',
            ),
            array(
                'key' => 'field_date_received',
                'label' => 'Date Received',
                'name' => 'date_received',
                'type' => 'date_picker',
                'instructions' => 'Select the award date',
                'display_format' => 'F j, Y',
                'return_format' => 'Y-m-d',
                'required' => 1,
            ),
            array(
                'key' => 'field_recognition_image',
                'label' => 'Recognition Image',
                'name' => 'recognition_image',
                'type' => 'image',
                'instructions' => 'Upload award certificate or image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_award_subcategory',
                'label' => 'Award Type',
                'name' => 'award_subcategory',
                'type' => 'select',
                'instructions' => 'Select the award type',
                'choices' => array(
                    'design_excellence' => 'Design Excellence',
                    'sustainability' => 'Sustainability Awards',
                    'innovation' => 'Innovation Awards',
                    'community_impact' => 'Community Impact',
                    'professional_recognition' => 'Professional Recognition',
                    'project_awards' => 'Project Awards',
                ),
                'allow_null' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
                array(
                    'param' => 'post_category',
                    'operator' => '==',
                    'value' => 'category:awards',
                ),
            ),
        ),
    ));
}
add_action('acf/init', 'ricelipka_create_acf_field_groups');

/**
 * Helper function to get category-specific fields
 */
function ricelipka_get_category_fields($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $primary_category = ricelipka_get_post_primary_category($post_id);
    $fields = array();

    switch ($primary_category) {
        case 'news':
            $fields = array(
                'news_title' => get_field('news_title', $post_id),
                'publication_date' => get_field('publication_date', $post_id),
                'excerpt' => get_field('excerpt', $post_id),
                'featured_image' => get_field('featured_image', $post_id),
                'subcategory' => get_field('subcategory', $post_id),
            );
            break;

        case 'projects':
            $fields = array(
                'project_name' => get_field('project_name', $post_id),
                'completion_status' => get_field('completion_status', $post_id),
                'completion_percentage' => get_field('completion_percentage', $post_id),
                'project_type' => get_field('project_type', $post_id),
                'client' => get_field('client', $post_id),
                'location' => get_field('location', $post_id),
                'image_gallery' => get_field('image_gallery', $post_id),
                'project_metadata' => get_field('project_metadata', $post_id),
            );
            break;

        case 'events':
            $fields = array(
                'event_title' => get_field('event_title', $post_id),
                'event_date' => get_field('event_date', $post_id),
                'event_time' => get_field('event_time', $post_id),
                'location' => get_field('location', $post_id),
                'external_links' => get_field('external_links', $post_id),
                'registration_link' => get_field('registration_link', $post_id),
                'recurring_event' => get_field('recurring_event', $post_id),
                'event_subcategory' => get_field('event_subcategory', $post_id),
            );
            break;

        case 'awards':
            $fields = array(
                'award_name' => get_field('award_name', $post_id),
                'awarding_organization' => get_field('awarding_organization', $post_id),
                'associated_project' => get_field('associated_project', $post_id),
                'date_received' => get_field('date_received', $post_id),
                'recognition_image' => get_field('recognition_image', $post_id),
                'award_subcategory' => get_field('award_subcategory', $post_id),
            );
            break;
    }

    return $fields;
}