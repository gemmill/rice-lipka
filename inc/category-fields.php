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
 * Create ACF field groups for each custom post type
 */
function ricelipka_create_acf_field_groups() {
    // Check if ACF Pro is active
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // News Post Type - No custom fields, uses WordPress defaults
    // (No ACF field group needed)

    // Projects Post Type Field Group
    acf_add_local_field_group(array(
        'key' => 'group_projects_fields',
        'title' => 'Project Fields',
        'fields' => array(
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
                                'required' => 1,
            ),
            array(
                'key' => 'field_location',
                'label' => 'Location',
                'name' => 'location',
                'type' => 'text',
                                'required' => 1,
            ),
                        array(
                'key' => 'field_project_year',
                'label' => 'Year',
                'name' => 'project_year',
                'type' => 'number',
                'instructions' => 'Enter the project year',
                'min' => 2000,
                'max' => 2100,
                'step' => 1,
                'required' => 1,
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

        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'projects',
                ),
            ),
        ),
    ));

    // Awards Post Type Field Group
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
                'post_type' => array('projects'),
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
                    'value' => 'awards',
                ),
            ),
        ),
    ));

    // People Post Type Field Group
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
                'post_type' => array('projects'),
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
                    'value' => 'people',
                ),
            ),
        ),
    ));

    // About Page Field Group
    acf_add_local_field_group(array(
        'key' => 'group_about_page_fields',
        'title' => 'About Page Fields',
        'fields' => array(
            array(
                'key' => 'field_about_child_pages',
                'label' => 'Child Pages Order',
                'name' => 'about_child_pages',
                'type' => 'relationship',
                'instructions' => 'Select and drag to reorder the child pages that should appear on the About page',
                'post_type' => array('page'),
                'taxonomy' => array(),
                'filters' => array(
                    'search',
                    'post_type',
                ),
                'elements' => array(
                    'featured_image',
                ),
                'min' => '',
                'max' => '',
                'return_format' => 'object',
                'library' => 'all',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-about.php',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
}
add_action('acf/init', 'ricelipka_create_acf_field_groups');

/**
 * Filter relationship field to show only child pages of About page
 */
function ricelipka_filter_about_child_pages($args, $field, $post_id) {
    // Only apply this filter to the about_child_pages field
    if ($field['name'] !== 'about_child_pages') {
        return $args;
    }
    
    // Get the About page
    $about_page = get_page_by_path('about');
    
    if ($about_page) {
        // Modify the query to only show child pages of About
        $args['post_parent'] = $about_page->ID;
        $args['post_status'] = 'publish';
        $args['orderby'] = 'menu_order title';
        $args['order'] = 'ASC';
    }
    
    return $args;
}
add_filter('acf/fields/relationship/query/name=about_child_pages', 'ricelipka_filter_about_child_pages', 10, 3);

/**
 * Helper function to get post type specific fields
 */
function ricelipka_get_post_type_fields($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $post_type = get_post_type($post_id);
    $fields = array();

    switch ($post_type) {
        case 'news':
            // News uses WordPress default fields (title, content, excerpt, featured image)
            $fields = array();
            break;

        case 'projects':
            $fields = array(
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
            
        case 'page':
            // Check if this is the About page
            $page_slug = get_post_field('post_name', $post_id);
            if ($page_slug === 'about') {
                $fields = array(
                    'about_child_pages' => get_field('about_child_pages', $post_id),
                );
            }
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

/**
 * Get ordered child pages for About page
 */
function ricelipka_get_about_child_pages($about_page_id = null) {
    if (!$about_page_id) {
        $about_page = get_page_by_path('about');
        if (!$about_page) {
            return array();
        }
        $about_page_id = $about_page->ID;
    }
    
    // Get the custom ordered pages from ACF field
    $ordered_pages = get_field('about_child_pages', $about_page_id);
    
    if ($ordered_pages && is_array($ordered_pages)) {
        return $ordered_pages;
    }
    
    // Fallback: get all child pages in default order
    $child_pages = get_children(array(
        'post_parent' => $about_page_id,
        'post_type' => 'page',
        'post_status' => 'publish',
        'orderby' => 'menu_order title',
        'order' => 'ASC'
    ));
    
    return $child_pages;
}