<?php
/**
 * Project Portfolio Block Patterns and Templates
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register project portfolio patterns and templates
 */
function ricelipka_register_project_patterns() {
    
    // Project Showcase Layout Pattern
    register_block_pattern(
        'ricelipka/project-showcase',
        array(
            'title'       => __('Project Showcase Layout', 'ricelipka-theme'),
            'description' => __('Comprehensive showcase layout for highlighting major architectural projects with detailed information and visual elements.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/project-portfolio {"data":{"project_name":"[Project Name]","completion_status":"completed","project_type":"civic","client":"[Client Organization]","location":"[City, State]"},"mode":"preview","align":"wide"} /-->

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
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Design Philosophy</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our design team focused on [key design principles], incorporating [sustainable features, accessibility considerations, or community input]. The result is a [description of final outcome] that serves as a model for [type of architecture].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Community Impact</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This project has had a significant impact on the local community by [specific benefits]. The design accommodates [user needs] while contributing to [broader community goals].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:heading {"level":4} -->
<h4>Project Details</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li><strong>Size:</strong> [Square footage/area]</li><li><strong>Budget:</strong> [Project budget range]</li><li><strong>Timeline:</strong> [Project duration]</li><li><strong>Team Size:</strong> [Number of team members]</li><li><strong>Sustainability:</strong> [LEED rating or green features]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":4} -->
<h4>Key Features</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Sustainable design elements</li><li>Accessible entrances and pathways</li><li>Natural lighting optimization</li><li>Community gathering spaces</li><li>Innovative structural solutions</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":4} -->
<h4>Awards & Recognition</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This project has received recognition for [award categories or achievements], including [specific awards or mentions].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
            'categories'  => array('ricelipka-blocks', 'showcase'),
            'keywords'    => array('project', 'showcase', 'portfolio', 'detailed', 'comprehensive'),
            'blockTypes'  => array('acf/project-portfolio'),
        )
    );

    // Project Grid View Pattern
    register_block_pattern(
        'ricelipka/project-grid-view',
        array(
            'title'       => __('Project Grid View', 'ricelipka-theme'),
            'description' => __('Compact grid layout for displaying multiple projects or project phases in an organized format.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/project-portfolio {"data":{"project_name":"[Project Name]","completion_status":"completed","project_type":"educational","client":"[School District]","location":"[City, State]"},"mode":"preview"} /-->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Phase 1: Planning & Design</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Initial conceptual design and community engagement phase. Completed [date] with [key outcomes].</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>Community workshops</li><li>Site analysis</li><li>Conceptual design</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Phase 2: Development</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Detailed design development and permitting process. Completed [date] with [key outcomes].</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>Technical drawings</li><li>Permit applications</li><li>Contractor selection</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Phase 3: Construction</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Construction administration and project completion. Finished [date] with [final results].</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>Construction oversight</li><li>Quality control</li><li>Final inspections</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
            'categories'  => array('ricelipka-blocks', 'grid'),
            'keywords'    => array('grid', 'phases', 'organized', 'multiple', 'compact'),
            'blockTypes'  => array('acf/project-portfolio'),
        )
    );
    
    // Detailed Case Study Pattern
    register_block_pattern(
        'ricelipka/project-case-study',
        array(
            'title'       => __('Detailed Case Study', 'ricelipka-theme'),
            'description' => __('In-depth case study format for documenting project challenges, solutions, and outcomes.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/project-portfolio {"data":{"project_name":"[Project Name] Case Study","completion_status":"completed","project_type":"cultural","client":"[Arts Organization]","location":"[City, State]"},"mode":"preview","align":"wide"} /-->

<!-- wp:heading {"level":3} -->
<h3>Project Challenge</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Describe the primary challenge or unique requirements that this project needed to address. What made this project particularly interesting or complex?</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Our Approach</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Explain the design methodology and approach taken to address the challenges. Include any innovative techniques or collaborative processes used.</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Design Process</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Research and analysis phase</li><li>Stakeholder engagement</li><li>Iterative design development</li><li>Testing and refinement</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Key Innovations</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>[Specific innovation or technique]</li><li>[Sustainable solution implemented]</li><li>[Technology or material innovation]</li><li>[Process or workflow improvement]</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:heading {"level":3} -->
<h3>Results & Impact</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Document the outcomes and impact of the project. Include both quantitative results and qualitative feedback from users and stakeholders.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"[Quote from client or user about the project impact and success]"</p><cite>[Name, Title, Organization]</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:heading {"level":3} -->
<h3>Lessons Learned</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Reflect on key insights gained from this project that can inform future work. What would you do differently? What worked particularly well?</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'case-study'),
            'keywords'    => array('case study', 'detailed', 'analysis', 'process', 'outcomes'),
            'blockTypes'  => array('acf/project-portfolio'),
        )
    );

    // Completed Project Pattern
    register_block_pattern(
        'ricelipka/project-completed',
        array(
            'title'       => __('Completed Project Showcase', 'ricelipka-theme'),
            'description' => __('Template for showcasing a completed architectural project with full details.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/project-portfolio {"data":{"project_name":"[Project Name]","completion_status":"completed","project_type":"civic","client":"[Client Organization]","location":"[City, State]"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>This [project type] project represents our commitment to [design philosophy or approach]. Completed in [year], the [building type] serves [community or purpose] and demonstrates innovative approaches to [key design elements].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Design Approach</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our design team focused on [key design principles], incorporating [sustainable features, accessibility considerations, or community input]. The result is a [description of final outcome].</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Key Features</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Sustainable design elements</li><li>Accessible entrances and pathways</li><li>Natural lighting optimization</li><li>Community gathering spaces</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Awards & Recognition</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This project has received recognition for [award categories or achievements].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
            'categories'  => array('ricelipka-blocks', 'completed'),
            'keywords'    => array('project', 'completed', 'showcase', 'portfolio'),
            'blockTypes'  => array('acf/project-portfolio'),
        )
    );
    
    // In-Progress Project Pattern
    register_block_pattern(
        'ricelipka/project-in-progress',
        array(
            'title'       => __('Project In Progress', 'ricelipka-theme'),
            'description' => __('Template for projects currently under construction with progress tracking.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/project-portfolio {"data":{"project_name":"[Project Name]","completion_status":"in_progress","completion_percentage":65,"project_type":"educational","client":"[School District]","location":"[City, State]"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>Construction is well underway on this exciting [project type] project. Expected to be completed in [timeline], this [building type] will serve [community or users] with [key features or capacity].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Construction Progress</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We are currently [current phase of construction]. Recent milestones include [recent achievements], and we are on track for [next major milestone] by [date].</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Completed Phases</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Site preparation and excavation</li><li>Foundation and structural work</li><li>Exterior envelope installation</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Upcoming Milestones</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Interior systems installation</li><li>Finish work and fixtures</li><li>Final inspections and occupancy</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:paragraph -->
<p><strong>Estimated Completion:</strong> [Month Year]</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'progress'),
            'keywords'    => array('project', 'construction', 'progress', 'in-progress'),
            'blockTypes'  => array('acf/project-portfolio'),
        )
    );
    
    // Planned Project Pattern
    register_block_pattern(
        'ricelipka/project-planned',
        array(
            'title'       => __('Planned Project', 'ricelipka-theme'),
            'description' => __('Template for approved projects that have not yet begun construction.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/project-portfolio {"data":{"project_name":"[Project Name]","completion_status":"planned","project_type":"cultural","client":"[Arts Organization]","location":"[City, State]"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>We are excited to share details about our upcoming [project type] project. This [building type] will [purpose and impact] when construction begins in [timeline].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Project Vision</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The design concept focuses on [design vision and goals]. Key elements include [major design features] that will [benefits to users or community].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Project Timeline</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li><strong>Design Development:</strong> [Current phase status]</li><li><strong>Permitting:</strong> [Status or timeline]</li><li><strong>Construction Start:</strong> [Anticipated date]</li><li><strong>Estimated Completion:</strong> [Target completion]</li></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>We look forward to breaking ground on this important project and will share construction updates as work progresses.</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'planning'),
            'keywords'    => array('project', 'planned', 'future', 'upcoming'),
            'blockTypes'  => array('acf/project-portfolio'),
        )
    );
}
add_action('init', 'ricelipka_register_project_patterns');

/**
 * Register project portfolio block templates
 */
function ricelipka_register_project_templates() {
    // Add project-specific template when creating new project posts
    add_filter('default_content', function($content, $post) {
        if (isset($_GET['category']) && $_GET['category'] === 'projects') {
            return '<!-- wp:acf/project-portfolio {"data":{"project_name":"","completion_status":"planned","project_type":"civic","client":"","location":""}} /-->' . "\n\n" . 
                   '<!-- wp:paragraph {"placeholder":"Describe your project here..."} -->' . "\n" .
                   '<p>Describe your project here...</p>' . "\n" .
                   '<!-- /wp:paragraph -->';
        }
        return $content;
    }, 10, 2);
}
add_action('init', 'ricelipka_register_project_templates', 20);

/**
 * Add drag-and-drop reordering support for project blocks
 */
function ricelipka_project_block_editor_settings($settings, $context) {
    if (isset($context->post) && $context->post->post_type === 'post') {
        // Get post categories to determine if this is a project post
        $categories = get_the_category($context->post->ID);
        $is_project_post = false;
        
        foreach ($categories as $category) {
            if ($category->slug === 'projects') {
                $is_project_post = true;
                break;
            }
        }
        
        if ($is_project_post) {
            // Enable drag-and-drop reordering and add pattern categories
            $settings['supportsLayout'] = true;
            $settings['__experimentalBlockPatterns'] = true;
            $settings['__experimentalBlockPatternCategories'] = array_merge(
                $settings['__experimentalBlockPatternCategories'] ?? array(),
                array(
                    array(
                        'name' => 'ricelipka-projects',
                        'label' => __('Project Layouts', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'showcase',
                        'label' => __('Project Showcase', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'grid',
                        'label' => __('Grid Layouts', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'case-study',
                        'label' => __('Case Studies', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'completed',
                        'label' => __('Completed Projects', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'progress',
                        'label' => __('In Progress', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'planning',
                        'label' => __('Planning Phase', 'ricelipka-theme'),
                    )
                )
            );
        }
    }
    
    return $settings;
}
add_filter('block_editor_settings_all', 'ricelipka_project_block_editor_settings', 10, 2);