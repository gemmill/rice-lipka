<?php
/**
 * Award Information Block Patterns and Templates
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register award information patterns and templates
 */
function ricelipka_register_award_patterns() {
    
    // Recognition Display Layout Pattern
    register_block_pattern(
        'ricelipka/award-recognition-display',
        array(
            'title'       => __('Recognition Display Layout', 'ricelipka-theme'),
            'description' => __('Prominent display layout for major awards and recognition with visual emphasis and detailed context.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/award-information {"data":{"award_name":"[Award Name]","awarding_organization":"[Organization Name]","date_received":"' . date('Y-m-d', strtotime('-2 months')) . '"},"mode":"preview","align":"wide"} /-->

<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"3rem","bottom":"3rem","left":"2rem","right":"2rem"}},"color":{"background":"#f8f9fa","text":"#2c3e50"},"border":{"width":"1px","color":"#e9ecef","style":"solid"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-text-color has-background has-border-color" style="border-color:#e9ecef;border-style:solid;border-width:1px;color:#2c3e50;background-color:#f8f9fa;padding-top:3rem;padding-right:2rem;padding-bottom:3rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"2.5rem","fontWeight":"700"}}} -->
<h2 class="wp-block-heading has-text-align-center" style="font-size:2.5rem;font-weight:700">🏆 Award Recognition</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">We are honored to announce that our [Project Name] has been recognized with the <strong>[Award Name]</strong> from the <strong>[Awarding Organization]</strong>. This prestigious award recognizes [award criteria and significance].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"><!-- wp:heading {"level":3} -->
<h3>About the Recognition</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The [Award Name] is presented annually to projects that demonstrate [award criteria]. Our [Project Name] was selected from [number] submissions for its [specific qualities that earned recognition].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Project Excellence</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This recognition highlights our commitment to [design principles or values] and validates our approach to [specific aspect of the project]. The award committee specifically noted [specific commendations].</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"[Quote from award committee or jury about why this project was selected]"</p><cite>[Award Committee Chair or Jury Member]</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:heading {"level":3} -->
<h3>Impact & Significance</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This award reflects not only the quality of our design work but also the positive impact our projects have on communities. It reinforces our mission to [firm mission or values].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:heading {"level":4} -->
<h4>Award Details</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li><strong>Award:</strong> [Full Award Name]</li><li><strong>Organization:</strong> [Awarding Body]</li><li><strong>Category:</strong> [Award Category]</li><li><strong>Year:</strong> [Award Year]</li><li><strong>Project:</strong> [Associated Project]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":4} -->
<h4>Project Highlights</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>[Key design feature]</li><li>[Sustainability achievement]</li><li>[Community impact]</li><li>[Innovation or technique]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":4} -->
<h4>Award Criteria</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>[Criterion 1]</li><li>[Criterion 2]</li><li>[Criterion 3]</li><li>[Criterion 4]</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:heading {"level":3} -->
<h3>Team Recognition</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This award reflects the collaborative effort of our entire project team, including [key team members or roles]. We are grateful for the opportunity to create meaningful architecture that serves our community.</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'recognition'),
            'keywords'    => array('recognition', 'display', 'major', 'prominent', 'award'),
            'blockTypes'  => array('acf/award-information'),
        )
    );

    // Achievement Timeline Pattern
    register_block_pattern(
        'ricelipka/award-timeline',
        array(
            'title'       => __('Achievement Timeline', 'ricelipka-theme'),
            'description' => __('Timeline layout for displaying multiple awards and achievements in chronological order.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/award-information {"data":{"award_name":"[Recent Award Name]","awarding_organization":"[Organization]","date_received":"' . date('Y-m-d', strtotime('-1 month')) . '"},"mode":"preview"} /-->

<!-- wp:heading {"level":3} -->
<h3>Our Recognition Journey</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Rice+Lipka Architects has been honored with multiple awards and recognition over the years. This timeline showcases our commitment to excellence in architectural design and community service.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem","left":"2rem","right":"2rem"}},"color":{"background":"#f8f9fa"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#f8f9fa;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:heading {"level":4,"style":{"color":{"text":"#0073aa"}}} -->
<h4 class="wp-block-heading has-text-color" style="color:#0073aa">[Current Year]</h4>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"75%"} -->
<div class="wp-block-column" style="flex-basis:75%"><!-- wp:heading {"level":5} -->
<h5>[Recent Award Name]</h5>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>[Awarding Organization]</strong> - Recognized for [achievement or project]. This award highlights our [specific accomplishment].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:heading {"level":4,"style":{"color":{"text":"#0073aa"}}} -->
<h4 class="wp-block-heading has-text-color" style="color:#0073aa">[Previous Year]</h4>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"75%"} -->
<div class="wp-block-column" style="flex-basis:75%"><!-- wp:heading {"level":5} -->
<h5>[Previous Award Name]</h5>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>[Awarding Organization]</strong> - Honored for [achievement or project]. This recognition demonstrated our expertise in [area of expertise].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:heading {"level":4,"style":{"color":{"text":"#0073aa"}}} -->
<h4 class="wp-block-heading has-text-color" style="color:#0073aa">[Earlier Year]</h4>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"75%"} -->
<div class="wp-block-column" style="flex-basis:75%"><!-- wp:heading {"level":5} -->
<h5>[Earlier Award Name]</h5>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>[Awarding Organization]</strong> - Celebrated for [achievement or project]. This milestone marked our [significance or impact].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
            'categories'  => array('ricelipka-blocks', 'timeline'),
            'keywords'    => array('timeline', 'chronological', 'multiple', 'history', 'achievements'),
            'blockTypes'  => array('acf/award-information'),
        )
    );
    
    // Project-Specific Award Pattern
    register_block_pattern(
        'ricelipka/award-project-specific',
        array(
            'title'       => __('Project-Specific Award', 'ricelipka-theme'),
            'description' => __('Template for awards received for specific architectural projects.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/award-information {"data":{"award_name":"[Award Name]","awarding_organization":"[Organization Name]","date_received":"' . date('Y-m-d', strtotime('-2 months')) . '"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>We are honored to announce that our [Project Name] has been recognized with the <strong>[Award Name]</strong> from the <strong>[Awarding Organization]</strong>. This prestigious award recognizes [award criteria and significance].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>About the Recognition</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The [Award Name] is presented annually to projects that demonstrate [award criteria]. Our [Project Name] was selected from [number] submissions for its [specific qualities that earned recognition].</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Project Highlights</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>[Key design feature]</li><li>[Sustainability achievement]</li><li>[Community impact]</li><li>[Innovation or technique]</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4>Award Criteria</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>[Criterion 1]</li><li>[Criterion 2]</li><li>[Criterion 3]</li><li>[Criterion 4]</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"[Quote from award committee or jury about why this project was selected]"</p><cite>[Award Committee Chair or Jury Member]</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:heading {"level":3} -->
<h3>Team Recognition</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This award reflects the collaborative effort of our entire project team, including [key team members or roles]. We are grateful for the opportunity to create meaningful architecture that serves our community.</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'project-awards'),
            'keywords'    => array('award', 'project', 'recognition', 'architecture'),
            'blockTypes'  => array('acf/award-information'),
        )
    );
    
    // Firm Achievement Award Pattern
    register_block_pattern(
        'ricelipka/award-firm-achievement',
        array(
            'title'       => __('Firm Achievement Award', 'ricelipka-theme'),
            'description' => __('Template for awards recognizing overall firm achievements or leadership.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/award-information {"data":{"award_name":"[Firm Achievement Award]","awarding_organization":"[Professional Organization]","date_received":"' . date('Y-m-d', strtotime('-1 month')) . '"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>Rice+Lipka Architects is proud to receive the <strong>[Award Name]</strong> from the <strong>[Awarding Organization]</strong>. This recognition honors our firm\'s commitment to [area of achievement] and our contributions to [field or community].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Recognition Significance</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The [Award Name] recognizes firms that have demonstrated [award criteria over time period]. This honor reflects our ongoing dedication to [firm values or mission] and our impact on [community, profession, or field].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Our Achievements</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Over the past [time period], Rice+Lipka Architects has:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>[Major achievement or milestone]</li><li>[Community contribution or impact]</li><li>[Professional leadership or innovation]</li><li>[Sustainability or social responsibility initiative]</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Looking Forward</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This recognition motivates us to continue [future goals or commitments]. We remain committed to [firm mission or values] and look forward to [future initiatives or projects].</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"[Quote from firm principal or leader about the significance of this recognition and future commitments]"</p><cite>[Name, Title, Rice+Lipka Architects]</cite></blockquote>
<!-- /wp:quote -->',
            'categories'  => array('ricelipka-blocks', 'firm-awards'),
            'keywords'    => array('firm', 'achievement', 'leadership', 'recognition'),
            'blockTypes'  => array('acf/award-information'),
        )
    );
    
    // Individual Recognition Pattern
    register_block_pattern(
        'ricelipka/award-individual',
        array(
            'title'       => __('Individual Recognition', 'ricelipka-theme'),
            'description' => __('Template for awards recognizing individual team members or principals.', 'ricelipka-theme'),
            'content'     => '<!-- wp:acf/award-information {"data":{"award_name":"[Individual Award Name]","awarding_organization":"[Professional Organization]","date_received":"' . date('Y-m-d', strtotime('-6 weeks')) . '"},"mode":"preview"} /-->

<!-- wp:paragraph -->
<p>We are delighted to announce that <strong>[Name]</strong>, [Title] at Rice+Lipka Architects, has been honored with the <strong>[Award Name]</strong> by the <strong>[Awarding Organization]</strong>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>About the Recognition</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The [Award Name] recognizes [individual achievement criteria]. [Name] was selected for [specific contributions or achievements] and [impact on profession or community].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Professional Contributions</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[Name]\'s notable contributions include:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>[Professional achievement or project leadership]</li><li>[Community service or volunteer work]</li><li>[Innovation or research contribution]</li><li>[Mentorship or educational involvement]</li></ul>
<!-- /wp:list -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>"[Quote from the award recipient about the honor and their work]"</p><cite>[Name, Title]</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:heading {"level":3} -->
<h3>Team Celebration</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This individual recognition reflects the collaborative spirit and excellence that defines our entire team at Rice+Lipka Architects. We celebrate [Name]\'s achievement and the positive impact of their work on our projects and community.</p>
<!-- /wp:paragraph -->',
            'categories'  => array('ricelipka-blocks', 'individual'),
            'keywords'    => array('individual', 'personal', 'recognition', 'team member'),
            'blockTypes'  => array('acf/award-information'),
        )
    );
}
add_action('init', 'ricelipka_register_award_patterns');

/**
 * Register award information block templates
 */
function ricelipka_register_award_templates() {
    // Add award-specific template when creating new award posts
    add_filter('default_content', function($content, $post) {
        if (isset($_GET['category']) && $_GET['category'] === 'awards') {
            return '<!-- wp:acf/award-information {"data":{"award_name":"","awarding_organization":"","date_received":"' . date('Y-m-d') . '"}} /-->' . "\n\n" . 
                   '<!-- wp:paragraph {"placeholder":"Describe the significance of this award..."} -->' . "\n" .
                   '<p>Describe the significance of this award...</p>' . "\n" .
                   '<!-- /wp:paragraph -->';
        }
        return $content;
    }, 10, 2);
}
add_action('init', 'ricelipka_register_award_templates', 20);

/**
 * Add drag-and-drop reordering support for award blocks
 */
function ricelipka_award_block_editor_settings($settings, $context) {
    if (isset($context->post) && $context->post->post_type === 'post') {
        // Get post categories to determine if this is an award post
        $categories = get_the_category($context->post->ID);
        $is_award_post = false;
        
        foreach ($categories as $category) {
            if ($category->slug === 'awards') {
                $is_award_post = true;
                break;
            }
        }
        
        if ($is_award_post) {
            // Enable drag-and-drop reordering and add pattern categories
            $settings['supportsLayout'] = true;
            $settings['__experimentalBlockPatterns'] = true;
            $settings['__experimentalBlockPatternCategories'] = array_merge(
                $settings['__experimentalBlockPatternCategories'] ?? array(),
                array(
                    array(
                        'name' => 'ricelipka-awards',
                        'label' => __('Award Layouts', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'recognition',
                        'label' => __('Recognition Display', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'timeline',
                        'label' => __('Achievement Timeline', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'project-awards',
                        'label' => __('Project Awards', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'firm-awards',
                        'label' => __('Firm Recognition', 'ricelipka-theme'),
                    ),
                    array(
                        'name' => 'individual',
                        'label' => __('Individual Recognition', 'ricelipka-theme'),
                    )
                )
            );
        }
    }
    
    return $settings;
}
add_filter('block_editor_settings_all', 'ricelipka_award_block_editor_settings', 10, 2);