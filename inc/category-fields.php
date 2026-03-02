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

    // News Category Field Group - No custom fields, uses WordPress defaults
    // acf_add_local_field_group(array(
    //     'key' => 'group_news_fields',
    //     'title' => 'News Fields',
    //     'fields' => array(),
    //     'location' => array(
    //         array(
    //             array(
    //                 'param' => 'post_type',
    //                 'operator' => '==',
    //                 'value' => 'post',
    //             ),
    //             array(
    //                 'param' => 'post_category',
    //                 'operator' => '==',
    //                 'value' => 'category:news',
    //             ),
    //         ),
    //     ),
    // ));

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
                    'cultural' => 'Cultural',
                    'academic' => 'Academic',
                    'offices' => 'Offices',
                    'retail_commercial' => 'Retail & Commercial',
                    'institutional' => 'Institutional',
                    'planning' => 'Planning',
                    'exhibitions' => 'Exhibitions',
                    'research_installation' => 'Research & Installation',
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
                'key' => 'field_project_year',
                'label' => 'Year',
                'name' => 'project_year',
                'type' => 'number',
                'instructions' => 'Enter the project year',
                'min' => 1900,
                'max' => 2100,
                'step' => 1,
                'required' => 1,
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

    // Events Category Field Group - Removed
    // Events category has been removed from the system

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
                'instructions' => 'Link to the associated project, or enter project name below if not found',
                'post_type' => array('post'),
                'taxonomy' => array('category:projects'),
                'return_format' => 'object',
                'allow_null' => 1,
            ),
            array(
                'key' => 'field_project_name_text',
                'label' => 'Project Name (if not linked above)',
                'name' => 'project_name_text',
                'type' => 'text',
                'instructions' => 'Enter project name if it\'s not available in the project list above',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_associated_project',
                            'operator' => '==empty',
                        ),
                    ),
                ),
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

    // People Category Field Group
    acf_add_local_field_group(array(
        'key' => 'group_people_fields',
        'title' => 'People Fields',
        'fields' => array(
            array(
                'key' => 'field_person_role',
                'label' => 'Role',
                'name' => 'person_role',
                'type' => 'select',
                'instructions' => 'Select the person\'s role or position',
                'choices' => array(
                    'principal' => 'Principal',
                    'associate' => 'Associate',
                    'architect' => 'Architect',
                    'designer' => 'Designer',
                    'project_manager' => 'Project Manager',
                    'intern' => 'Intern',
                    'consultant' => 'Consultant',
                    'collaborator' => 'Collaborator',
                    'client' => 'Client',
                    'contractor' => 'Contractor',
                ),
                'required' => 1,
                'allow_null' => 0,
            ),
            array(
                'key' => 'field_person_associations',
                'label' => 'Project Associations',
                'name' => 'person_associations',
                'type' => 'post_object',
                'instructions' => 'Select projects this person is associated with',
                'post_type' => array('post'),
                'taxonomy' => array('category:projects'),
                'return_format' => 'object',
                'multiple' => 1,
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
                    'value' => 'category:people',
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
            // News uses WordPress default fields (title, content, excerpt, featured image)
            $fields = array();
            break;

        case 'projects':
            $fields = array(
                'completion_status' => get_field('completion_status', $post_id),
                'completion_percentage' => get_field('completion_percentage', $post_id),
                'project_type' => get_field('project_type', $post_id),
                'client' => get_field('client', $post_id),
                'location' => get_field('location', $post_id),
                'image_gallery' => get_field('image_gallery', $post_id),
                'project_year' => get_field('project_year', $post_id),
            );
            break;

        case 'awards':
            $fields = array(
                'awarding_organization' => get_field('awarding_organization', $post_id),
                'associated_project' => get_field('associated_project', $post_id),
                'project_name_text' => get_field('project_name_text', $post_id),
                'date_received' => get_field('date_received', $post_id),
            );
            break;

        case 'people':
            $fields = array(
                'person_role' => get_field('person_role', $post_id),
                'person_associations' => get_field('person_associations', $post_id),
            );
            break;
    }

    return $fields;
}

/**
 * Get the display name for a project type
 */
function ricelipka_get_project_type_display($project_type) {
    $type_labels = array(
        'cultural' => 'Cultural',
        'academic' => 'Academic',
        'offices' => 'Offices',
        'retail_commercial' => 'Retail & Commercial',
        'institutional' => 'Institutional',
        'planning' => 'Planning',
        'exhibitions' => 'Exhibitions',
        'research_installation' => 'Research & Installation',
    );
    
    return isset($type_labels[$project_type]) ? $type_labels[$project_type] : ucfirst(str_replace('_', ' ', $project_type));
}