<?php
/**
 * News Article Block Patterns and Templates
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register news article patterns and templates
 */
function ricelipka_register_news_patterns() {
    
    // Featured Story Layout Pattern
    register_block_pattern(
        'ricelipka/news-featured-story',
        array(
            'title'       => __('Featured Story Layout', 'ricelipka-theme'),
            'description' => __('Prominent layout for major announcements and featured news stories with large imagery and detailed content.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/news-article {"data":{"news_title":"[Featured Story Title]","publication_date":"' . date('Y-m-d') . '","excerpt":"This is a major announcement or featured story that deserves prominent placement. Write a compelling summary that will draw readers in.","subcategory":"project_updates"},"mode":"preview","align":"wide"} /-->

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
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Continue with detailed information about the story. This layout is designed for substantial content that provides comprehensive coverage of important firm news.</p>
<!-- /wp:paragraph --></div>
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
<!-- /wp:columns -->',
            'categories'  => array('ricelipka-blocks', 'featured'),
            'keywords'    => array('featured', 'story', 'major', 'announcement', 'prominent'),
            'blockTypes'  => array('acf/news-article'),
        )
    );

    // Brief Update Pattern
    register_block_pattern(
        'ricelipka/news-brief-update',
        array(
            'title'       => __('Brief Update Layout', 'ricelipka-theme'),
            'description' => __('Concise layout for quick updates, progress reports, and short announcements.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/news-article {"data":{"news_title":"[Brief Update Title]","publication_date":"' . date('Y-m-d') . '","excerpt":"A concise summary of this update or announcement. Keep it brief and to the point.","subcategory":"project_updates"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>This brief update format is perfect for quick announcements, progress reports, or short news items that don\'t require extensive detail.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Key Points</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Main point or achievement</li><li>Current status or progress</li><li>Next steps or timeline</li></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Keep the content focused and actionable. This format works well for project milestones, team updates, or quick announcements.</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'updates'),
            'keywords'    => array('brief', 'update', 'quick', 'short', 'progress'),
            'blockTypes'  => array('acf/news-article'),
        )
    );

    // Standard News Article Pattern
    register_block_pattern(
        'ricelipka/news-standard',
        array(
            'title'       => __('Standard News Article', 'ricelipka-theme'),
            'description' => __('A standard news article layout with title, image, and content.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/news-article {"data":{"news_title":"Your News Title Here","publication_date":"' . date('Y-m-d') . '","excerpt":"Write a brief summary of your news article here. This will appear in listings and social media shares.","subcategory":"project_updates"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>Write your main article content here. You can use all the standard WordPress blocks to format your content with headings, images, lists, and more.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Key Points</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Add important details as bullet points</li><li>Highlight key achievements or milestones</li><li>Include relevant project information</li></ul>
<!-- /wp:list -->',
            'categories'  => array('ricelipka-blocks'),
            'keywords'    => array('news', 'article', 'announcement'),
            'blockTypes'  => array('acf/news-article'),
        )
    );
    
    // Project Update Pattern
    register_block_pattern(
        'ricelipka/news-project-update',
        array(
            'title'       => __('Project Update News', 'ricelipka-theme'),
            'description' => __('News article template for project construction updates and milestones.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/news-article {"data":{"news_title":"Project Update: [Project Name] Reaches New Milestone","publication_date":"' . date('Y-m-d') . '","excerpt":"We are excited to share the latest progress on our [Project Name] project, which has reached a significant construction milestone.","subcategory":"project_updates"},"mode":"preview"} /-->

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
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'updates'),
            'keywords'    => array('project', 'update', 'construction', 'milestone'),
            'blockTypes'  => array('acf/news-article'),
        )
    );
    
    // Award Announcement Pattern
    register_block_pattern(
        'ricelipka/news-award-announcement',
        array(
            'title'       => __('Award Announcement', 'ricelipka-theme'),
            'description' => __('News article template for announcing awards and recognition.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/news-article {"data":{"news_title":"Rice+Lipka Architects Receives [Award Name]","publication_date":"' . date('Y-m-d') . '","excerpt":"We are honored to announce that Rice+Lipka Architects has been recognized with the [Award Name] for our work on the [Project Name].","subcategory":"award_notifications"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>We are thrilled to announce that Rice+Lipka Architects has been awarded the <strong>[Award Name]</strong> by the <strong>[Awarding Organization]</strong> for our exceptional work on the [Project Name] project.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>About the Award</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The [Award Name] recognizes [award criteria and significance]. This year, [number] projects were submitted for consideration, making this recognition particularly meaningful.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Project Recognition</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our [Project Name] was selected for its [key features that earned recognition]. The project demonstrates our commitment to [design principles or values].</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"[Quote from team member or client about the recognition]"</p><cite>[Name, Title]</cite></blockquote>
<!-- /wp:quote -->',
            'categories'  => array('ricelipka-blocks', 'awards'),
            'keywords'    => array('award', 'recognition', 'achievement', 'announcement'),
            'blockTypes'  => array('acf/news-article'),
        )
    );
}
add_action('init', 'ricelipka_register_news_patterns');

/**
 * Register news article block templates
 */
function ricelipka_register_news_templates() {
    // Register block template for news category posts
    $post_type_object = get_post_type_object('post');
    if ($post_type_object) {
        $post_type_object->template = array(
            array('acf/news-article', array(
                'data' => array(
                    'news_title' => '',
                    'publication_date' => date('Y-m-d'),
                    'excerpt' => '',
                    'subcategory' => 'project_updates'
                )
            )),
            array('core/paragraph', array(
                'placeholder' => 'Start writing your news article content here...'
            ))
        );
        $post_type_object->template_lock = false; // Allow reordering and adding blocks
    }
}
add_action('init', 'ricelipka_register_news_templates', 20);

/**
 * Add drag-and-drop reordering support for news blocks
 */
function ricelipka_news_block_editor_settings($settings, $context) {
    if (isset($context->post) && $context->post->post_type === 'post') {
        // Get post categories to determine if this is a news post
        $categories = get_the_category($context->post->ID);
        $is_news_post = false;
        
        foreach ($categories as $category) {
            if ($category->slug === 'news') {
                $is_news_post = true;
                break;
            }
        }
        
        if ($is_news_post) {
            // Enable drag-and-drop reordering
            $settings['supportsLayout'] = true;
            $settings['__experimentalBlockPatterns'] = true;
            $settings['__experimentalBlockPatternCategories'] = array(
                array(
                    'name' => 'ricelipka-news',
                    'label' => __('News Layouts', 'ricelipka-theme'),
                ),
                array(
                    'name' => 'featured',
                    'label' => __('Featured Stories', 'ricelipka-theme'),
                ),
                array(
                    'name' => 'updates',
                    'label' => __('Updates & Announcements', 'ricelipka-theme'),
                ),
                array(
                    'name' => 'awards',
                    'label' => __('Awards & Recognition', 'ricelipka-theme'),
                )
            );
        }
    }
    
    return $settings;
}
add_filter('block_editor_settings_all', 'ricelipka_news_block_editor_settings', 10, 2);