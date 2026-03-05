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

    // Add Site Settings Options Page
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => 'Site Settings',
            'menu_title' => 'Site Settings',
            'menu_slug' => 'site-settings',
            'capability' => 'edit_posts',
            'icon_url' => 'dashicons-admin-generic',
            'position' => 30,
        ));
    }

    // Site Settings Field Group
    acf_add_local_field_group(array(
        'key' => 'group_site_settings',
        'title' => 'Site Settings',
        'fields' => array(
            array(
                'key' => 'field_site_colors',
                'label' => 'Site Colors',
                'name' => 'site_colors',
                'type' => 'repeater',
                'instructions' => 'Add multiple colors for the site. Each color must meet WCAG contrast guidelines for white backgrounds (minimum 4.5:1 ratio).',
                'required' => 0,
                'collapsed' => '',
                'min' => 0,
                'max' => 10,
                'layout' => 'table',
                'button_label' => 'Add Color',
                'sub_fields' => array(
                    array(
                        'key' => 'field_site_color',
                        'label' => 'Color',
                        'name' => 'color',
                        'type' => 'color_picker',
                        'instructions' => '',
                        'required' => 1,
                        'default_value' => '#000000',
                        'enable_opacity' => 0,
                        'return_format' => 'string',
                    ),
                    array(
                        'key' => 'field_site_color_name',
                        'label' => 'Color Name',
                        'name' => 'color_name',
                        'type' => 'text',
                        'instructions' => 'Optional: Give this color a descriptive name (e.g., "Primary", "Accent", "Text")',
                        'required' => 0,
                        'placeholder' => 'e.g., Primary, Accent, Text',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'site-settings',
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
        'description' => 'Site-wide color settings with WCAG compliance validation.',
    ));

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
                    'residential' => 'Residential',
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

    // Page Child Pages Field Group (for any page with children)
    acf_add_local_field_group(array(
        'key' => 'group_page_child_pages_fields',
        'title' => 'Child Pages',
        'fields' => array(
            array(
                'key' => 'field_page_child_pages',
                'label' => 'Child Pages Order',
                'name' => 'page_child_pages',
                'type' => 'relationship',
                'instructions' => 'Select and drag to reorder the child pages that should appear on this page',
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
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
                array(
                    'param' => 'page_parent',
                    'operator' => '==',
                    'value' => '0',
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
 * Filter relationship field to show only child pages of current page
 */
function ricelipka_filter_page_child_pages($args, $field, $post_id) {
    // Only apply this filter to the page_child_pages field
    if ($field['name'] !== 'page_child_pages') {
        return $args;
    }
    
    // Get the current page ID
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }
    
    if ($post_id) {
        // Modify the query to only show child pages of current page
        $args['post_parent'] = $post_id;
        $args['post_status'] = 'publish';
        $args['orderby'] = 'menu_order title';
        $args['order'] = 'ASC';
    }
    
    return $args;
}
add_filter('acf/fields/relationship/query/name=page_child_pages', 'ricelipka_filter_page_child_pages', 10, 3);

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
            // Get page-specific fields
            $fields = array(
                'page_child_pages' => get_field('page_child_pages', $post_id),
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
        'residential' => 'Residential',
    );
    
    return isset($type_labels[$project_type]) ? $type_labels[$project_type] : ucfirst(str_replace('_', ' ', $project_type));
}

/**
 * Get ordered child pages for any page
 */
function ricelipka_get_page_child_pages($page_id = null) {
    if (!$page_id) {
        $page_id = get_the_ID();
    }
    
    if (!$page_id) {
        return array();
    }
    
    // Get the custom ordered pages from ACF field
    $ordered_pages = get_field('page_child_pages', $page_id);
    
    if ($ordered_pages && is_array($ordered_pages)) {
        return $ordered_pages;
    }
    
    // Fallback: get all child pages in default order
    $child_pages = get_children(array(
        'post_parent' => $page_id,
        'post_type' => 'page',
        'post_status' => 'publish',
        'orderby' => 'menu_order title',
        'order' => 'ASC'
    ));
    
    return $child_pages;
}

/**
 * Calculate color contrast ratio for WCAG compliance
 */
function ricelipka_calculate_contrast_ratio($color1, $color2) {
    // Convert hex colors to RGB
    $rgb1 = ricelipka_hex_to_rgb($color1);
    $rgb2 = ricelipka_hex_to_rgb($color2);
    
    if (!$rgb1 || !$rgb2) {
        return 1; // Return low contrast if conversion fails
    }
    
    // Calculate relative luminance
    $l1 = ricelipka_get_relative_luminance($rgb1);
    $l2 = ricelipka_get_relative_luminance($rgb2);
    
    // Calculate contrast ratio
    $lighter = max($l1, $l2);
    $darker = min($l1, $l2);
    
    return ($lighter + 0.05) / ($darker + 0.05);
}

/**
 * Convert hex color to RGB array
 */
function ricelipka_hex_to_rgb($hex) {
    // Remove # if present
    $hex = ltrim($hex, '#');
    
    // Handle invalid hex
    if (!preg_match('/^[a-fA-F0-9]{3}$|^[a-fA-F0-9]{6}$/', $hex)) {
        return false;
    }
    
    // Convert 3-digit hex to 6-digit
    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    
    return array(
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2))
    );
}

/**
 * Calculate relative luminance for a color
 */
function ricelipka_get_relative_luminance($rgb) {
    $r = $rgb['r'] / 255;
    $g = $rgb['g'] / 255;
    $b = $rgb['b'] / 255;
    
    // Apply gamma correction
    $r = ($r <= 0.03928) ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
    $g = ($g <= 0.03928) ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
    $b = ($b <= 0.03928) ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);
    
    return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

/**
 * Validate color contrast for WCAG compliance
 */
function ricelipka_validate_color_contrast($valid, $value, $field, $input) {
    // Skip validation if already invalid or no value
    if (!$valid || empty($value)) {
        return $valid;
    }
    
    // White background for contrast testing
    $white_background = '#ffffff';
    
    // Calculate contrast ratio
    $contrast_ratio = ricelipka_calculate_contrast_ratio($value, $white_background);
    
    // WCAG AA requires minimum 4.5:1 for normal text, 3:1 for large text
    $min_contrast = 4.5;
    
    if ($contrast_ratio < $min_contrast) {
        $valid = sprintf(
            'Color %s has insufficient contrast ratio (%.2f:1). WCAG requires minimum %.1f:1 for accessibility compliance. Please choose a darker color.',
            $value,
            $contrast_ratio,
            $min_contrast
        );
    }
    
    return $valid;
}
add_filter('acf/validate_value/name=color', 'ricelipka_validate_color_contrast', 10, 4);

/**
 * Additional validation for the entire repeater field
 */
function ricelipka_validate_site_colors_repeater($valid, $value, $field, $input) {
    // Skip if already invalid
    if (!$valid) {
        return $valid;
    }
    
    // Check if we have any colors
    if (empty($value) || !is_array($value)) {
        return $valid; // Allow empty (not required)
    }
    
    $white_background = '#ffffff';
    $min_contrast = 4.5;
    $errors = array();
    
    foreach ($value as $index => $color_data) {
        if (isset($color_data['color']) && !empty($color_data['color'])) {
            $color = $color_data['color'];
            $contrast_ratio = ricelipka_calculate_contrast_ratio($color, $white_background);
            
            if ($contrast_ratio < $min_contrast) {
                $color_name = isset($color_data['color_name']) && !empty($color_data['color_name']) 
                    ? $color_data['color_name'] 
                    : 'Color ' . ($index + 1);
                    
                $errors[] = sprintf(
                    '%s (%s): Contrast ratio %.2f:1 is too low. Minimum required: %.1f:1',
                    $color_name,
                    $color,
                    $contrast_ratio,
                    $min_contrast
                );
            }
        }
    }
    
    if (!empty($errors)) {
        $valid = 'WCAG Contrast Validation Failed:<br>' . implode('<br>', $errors);
    }
    
    return $valid;
}
add_filter('acf/validate_value/name=site_colors', 'ricelipka_validate_site_colors_repeater', 10, 4);

/**
 * Get site colors from ACF options
 */
function ricelipka_get_site_colors() {
    if (function_exists('get_field')) {
        $colors_data = get_field('site_colors', 'option');
        
        if ($colors_data && is_array($colors_data)) {
            $colors = array();
            foreach ($colors_data as $color_item) {
                if (isset($color_item['color'])) {
                    $colors[] = array(
                        'color' => $color_item['color'],
                        'name' => isset($color_item['color_name']) ? $color_item['color_name'] : ''
                    );
                }
            }
            return $colors;
        }
    }
    
    // Default fallback
    return array(
        array(
            'color' => '#000000',
            'name' => 'Default Black'
        )
    );
}

/**
 * Get site colors as simple array (just the hex values)
 */
function ricelipka_get_site_colors_simple() {
    $colors_data = ricelipka_get_site_colors();
    $colors = array();
    
    foreach ($colors_data as $color_item) {
        $colors[] = $color_item['color'];
    }
    
    return $colors;
}

/**
 * Add JavaScript for real-time color contrast validation
 */
function ricelipka_add_color_validation_script() {
    // Only load on the site settings page
    $screen = get_current_screen();
    if ($screen && strpos($screen->id, 'site-settings') !== false) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Function to calculate contrast ratio
            function calculateContrastRatio(color1, color2) {
                function hexToRgb(hex) {
                    hex = hex.replace('#', '');
                    if (hex.length === 3) {
                        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
                    }
                    return {
                        r: parseInt(hex.substr(0, 2), 16),
                        g: parseInt(hex.substr(2, 2), 16),
                        b: parseInt(hex.substr(4, 2), 16)
                    };
                }
                
                function getRelativeLuminance(rgb) {
                    var r = rgb.r / 255;
                    var g = rgb.g / 255;
                    var b = rgb.b / 255;
                    
                    r = (r <= 0.03928) ? r / 12.92 : Math.pow((r + 0.055) / 1.055, 2.4);
                    g = (g <= 0.03928) ? g / 12.92 : Math.pow((g + 0.055) / 1.055, 2.4);
                    b = (b <= 0.03928) ? b / 12.92 : Math.pow((b + 0.055) / 1.055, 2.4);
                    
                    return 0.2126 * r + 0.7152 * g + 0.0722 * b;
                }
                
                var rgb1 = hexToRgb(color1);
                var rgb2 = hexToRgb(color2);
                var l1 = getRelativeLuminance(rgb1);
                var l2 = getRelativeLuminance(rgb2);
                var lighter = Math.max(l1, l2);
                var darker = Math.min(l1, l2);
                
                return (lighter + 0.05) / (darker + 0.05);
            }
            
            // Add contrast validation to color inputs
            function validateColorContrast($input) {
                var color = $input.val();
                var $row = $input.closest('.acf-row');
                var $feedback = $row.find('.contrast-feedback');
                
                if ($feedback.length === 0) {
                    $feedback = $('<div class="contrast-feedback" style="margin-top: 5px; font-size: 12px;"></div>');
                    $input.closest('.acf-input').append($feedback);
                }
                
                if (color && color.match(/^#[0-9A-F]{6}$/i)) {
                    var ratio = calculateContrastRatio(color, '#ffffff');
                    var minRatio = 4.5;
                    
                    if (ratio >= minRatio) {
                        $feedback.html('<span style="color: green;">✓ WCAG compliant (ratio: ' + ratio.toFixed(2) + ':1)</span>');
                        $input.css('border-color', '');
                    } else {
                        $feedback.html('<span style="color: red;">✗ Low contrast (ratio: ' + ratio.toFixed(2) + ':1, need: ' + minRatio + ':1)</span>');
                        $input.css('border-color', '#dc3232');
                    }
                } else {
                    $feedback.html('');
                    $input.css('border-color', '');
                }
            }
            
            // Bind to existing and new color inputs
            $(document).on('change input', 'input[data-name="color"]', function() {
                validateColorContrast($(this));
            });
            
            // Initial validation for existing colors
            $('input[data-name="color"]').each(function() {
                validateColorContrast($(this));
            });
        });
        </script>
        <style>
        .contrast-feedback {
            font-weight: bold;
        }
        </style>
        <?php
    }
}
add_action('admin_footer', 'ricelipka_add_color_validation_script');

/**
 * Test function to verify contrast calculation (for debugging)
 * Remove this in production
 */
function ricelipka_test_contrast_calculation() {
    if (defined('WP_DEBUG') && WP_DEBUG && isset($_GET['test_contrast'])) {
        $test_colors = array(
            '#000000' => 'Black (should pass)',
            '#ffffff' => 'White (should fail)',
            '#767676' => 'Gray (should pass barely)',
            '#777777' => 'Lighter Gray (should fail)',
            '#ff0000' => 'Red (should fail)',
            '#cc0000' => 'Dark Red (should pass)',
        );
        
        echo '<div style="background: white; padding: 20px; margin: 20px;">';
        echo '<h3>Color Contrast Test Results</h3>';
        
        foreach ($test_colors as $color => $description) {
            $ratio = ricelipka_calculate_contrast_ratio($color, '#ffffff');
            $status = $ratio >= 4.5 ? 'PASS' : 'FAIL';
            echo '<p style="color: ' . $color . '; font-weight: bold;">';
            echo $description . ': ' . $color . ' - Ratio: ' . number_format($ratio, 2) . ':1 - ' . $status;
            echo '</p>';
        }
        
        echo '</div>';
    }
}
add_action('admin_notices', 'ricelipka_test_contrast_calculation');