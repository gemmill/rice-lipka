<?php
/**
 * ACF Help Documentation System
 * 
 * Provides comprehensive help documentation for ACF blocks
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RiceLipka_ACF_Help_Documentation {
    
    /**
     * Initialize documentation system
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_help_menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_help_docs_assets'));
        add_action('wp_ajax_get_block_help', array(__CLASS__, 'ajax_get_block_help'));
    }
    
    /**
     * Add help menu to WordPress admin
     */
    public static function add_help_menu() {
        add_submenu_page(
            'edit.php',
            __('ACF Blocks Help', 'ricelipka-theme'),
            __('Blocks Help', 'ricelipka-theme'),
            'edit_posts',
            'acf-blocks-help',
            array(__CLASS__, 'render_help_page')
        );
    }
    
    /**
     * Render help documentation page
     */
    public static function render_help_page() {
        ?>
        <div class="wrap acf-help-documentation">
            <h1><?php _e('ACF Blocks Help & Documentation', 'ricelipka-theme'); ?></h1>
            
            <div class="acf-help-nav">
                <nav class="nav-tab-wrapper">
                    <a href="#overview" class="nav-tab nav-tab-active"><?php _e('Overview', 'ricelipka-theme'); ?></a>
                    <a href="#news-article" class="nav-tab"><?php _e('News Articles', 'ricelipka-theme'); ?></a>
                    <a href="#project-portfolio" class="nav-tab"><?php _e('Project Portfolio', 'ricelipka-theme'); ?></a>
                    <a href="#award-information" class="nav-tab"><?php _e('Award Information', 'ricelipka-theme'); ?></a>
                    <a href="#best-practices" class="nav-tab"><?php _e('Best Practices', 'ricelipka-theme'); ?></a>
                </nav>
            </div>
            
            <div class="acf-help-content">
                
                <!-- Overview Section -->
                <div id="overview" class="help-section active">
                    <h2><?php _e('Getting Started with ACF Blocks', 'ricelipka-theme'); ?></h2>
                    
                    <div class="help-intro">
                        <p><?php _e('Rice+Lipka Architects uses custom ACF blocks to create structured, professional content for different types of posts. Each block type is designed for specific content categories and includes helpful guidance to ensure consistent, high-quality content.', 'ricelipka-theme'); ?></p>
                    </div>
                    
                    <div class="help-grid">
                        <div class="help-card">
                            <div class="help-card-icon">📰</div>
                            <h3><?php _e('News Articles', 'ricelipka-theme'); ?></h3>
                            <p><?php _e('Create engaging news content with structured fields for headlines, images, and categorization.', 'ricelipka-theme'); ?></p>
                            <a href="#news-article" class="button"><?php _e('Learn More', 'ricelipka-theme'); ?></a>
                        </div>
                        
                        <div class="help-card">
                            <div class="help-card-icon">🏗️</div>
                            <h3><?php _e('Project Portfolio', 'ricelipka-theme'); ?></h3>
                            <p><?php _e('Showcase architectural projects with galleries, metadata, and progress tracking.', 'ricelipka-theme'); ?></p>
                            <a href="#project-portfolio" class="button"><?php _e('Learn More', 'ricelipka-theme'); ?></a>
                        </div>
                        
                        <div class="help-card">
                            <div class="help-card-icon">🏆</div>
                            <h3><?php _e('Award Information', 'ricelipka-theme'); ?></h3>
                            <p><?php _e('Document awards and recognition with project associations and timelines.', 'ricelipka-theme'); ?></p>
                            <a href="#award-information" class="button"><?php _e('Learn More', 'ricelipka-theme'); ?></a>
                        </div>
                    </div>
                    
                    <div class="help-quick-start">
                        <h3><?php _e('Quick Start Guide', 'ricelipka-theme'); ?></h3>
                        <ol>
                            <li><?php _e('Create a new post and select the appropriate category (News, Projects, or Awards)', 'ricelipka-theme'); ?></li>
                            <li><?php _e('Add the corresponding ACF block from the block inserter', 'ricelipka-theme'); ?></li>
                            <li><?php _e('Fill in the required fields - tooltips provide guidance for each field', 'ricelipka-theme'); ?></li>
                            <li><?php _e('Use the main content area for detailed descriptions and additional formatting', 'ricelipka-theme'); ?></li>
                            <li><?php _e('Preview your content to see how it will appear to visitors', 'ricelipka-theme'); ?></li>
                        </ol>
                    </div>
                </div>
                
                <!-- News Article Section -->
                <div id="news-article" class="help-section">
                    <?php echo self::get_news_article_help(); ?>
                </div>
                
                <!-- Project Portfolio Section -->
                <div id="project-portfolio" class="help-section">
                    <?php echo self::get_project_portfolio_help(); ?>
                </div>
                
                <!-- Award Information Section -->
                <div id="award-information" class="help-section">
                    <?php echo self::get_award_information_help(); ?>
                </div>
                
                <!-- Best Practices Section -->
                <div id="best-practices" class="help-section">
                    <?php echo self::get_best_practices_help(); ?>
                </div>
                
            </div>
        </div>
        
        <style>
        .acf-help-documentation {
            max-width: 1200px;
        }
        
        .acf-help-nav {
            margin: 20px 0;
        }
        
        .help-section {
            display: none;
            padding: 20px 0;
        }
        
        .help-section.active {
            display: block;
        }
        
        .help-intro {
            background: #f0f6fc;
            padding: 20px;
            border-left: 4px solid #0073aa;
            margin: 20px 0;
        }
        
        .help-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .help-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .help-card-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .help-card h3 {
            margin: 0 0 10px 0;
            color: #0073aa;
        }
        
        .help-card p {
            color: #666;
            margin-bottom: 15px;
        }
        
        .help-quick-start {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .help-quick-start h3 {
            margin-top: 0;
            color: #0073aa;
        }
        
        .help-quick-start ol {
            line-height: 1.6;
        }
        
        .help-quick-start li {
            margin-bottom: 8px;
        }
        
        .field-help-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .field-help-table th,
        .field-help-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .field-help-table th {
            background: #f9f9f9;
            font-weight: 600;
        }
        
        .field-help-table .field-name {
            font-weight: 600;
            color: #0073aa;
        }
        
        .help-tip {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .help-tip::before {
            content: "💡 ";
            font-weight: bold;
        }
        
        .help-warning {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .help-warning::before {
            content: "⚠️ ";
            font-weight: bold;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                
                var target = $(this).attr('href');
                
                // Update active tab
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                
                // Show target section
                $('.help-section').removeClass('active');
                $(target).addClass('active');
            });
            
            // Handle internal links
            $('.help-card .button').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                $('.nav-tab[href="' + target + '"]').click();
            });
        });
        </script>
        <?php
    }
    
    /**
     * Get News Article help content
     */
    private static function get_news_article_help() {
        ob_start();
        ?>
        <h2><?php _e('News Article Block', 'ricelipka-theme'); ?></h2>
        
        <p><?php _e('The News Article block is designed for creating engaging news content with structured fields that ensure consistency and professional presentation.', 'ricelipka-theme'); ?></p>
        
        <h3><?php _e('Field Reference', 'ricelipka-theme'); ?></h3>
        <table class="field-help-table">
            <thead>
                <tr>
                    <th><?php _e('Field', 'ricelipka-theme'); ?></th>
                    <th><?php _e('Purpose', 'ricelipka-theme'); ?></th>
                    <th><?php _e('Guidelines', 'ricelipka-theme'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="field-name"><?php _e('News Title', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Main headline for the article', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Keep under 60 characters for SEO. Use action words and specific details.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Publication Date', 'ricelipka-theme'); ?></td>
                    <td><?php _e('When the news should be published', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Leave empty to use post date. Set future dates for scheduled content.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Excerpt', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Brief summary for listings and social media', 'ricelipka-theme'); ?></td>
                    <td><?php _e('2-3 sentences, under 50 words. Focus on who, what, when, where.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Featured Image', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Primary image for the article', 'ricelipka-theme'); ?></td>
                    <td><?php _e('1200x630px recommended. Use professional photos, avoid stock images.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Subcategory', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Type of news for better organization', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Project Updates or Award Notifications.', 'ricelipka-theme'); ?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="help-tip">
            <?php _e('Use the main WordPress content editor below the block for your full article text. This allows you to use headings, lists, images, and other formatting.', 'ricelipka-theme'); ?>
        </div>
        
        <h3><?php _e('Best Practices', 'ricelipka-theme'); ?></h3>
        <ul>
            <li><?php _e('Write headlines that would make someone want to read more', 'ricelipka-theme'); ?></li>
            <li><?php _e('Include specific project names or award titles for better SEO', 'ricelipka-theme'); ?></li>
            <li><?php _e('Use high-quality images of actual projects or team members', 'ricelipka-theme'); ?></li>
            <li><?php _e('Keep excerpts focused on the key news value', 'ricelipka-theme'); ?></li>
            <li><?php _e('Choose subcategories that help visitors find related content', 'ricelipka-theme'); ?></li>
        </ul>
        
        <h3><?php _e('Common Patterns', 'ricelipka-theme'); ?></h3>
        <p><?php _e('Use the block patterns in the inserter for common news article layouts:', 'ricelipka-theme'); ?></p>
        <ul>
            <li><strong><?php _e('Standard News Article', 'ricelipka-theme'); ?>:</strong> <?php _e('General news and announcements', 'ricelipka-theme'); ?></li>
            <li><strong><?php _e('Project Update', 'ricelipka-theme'); ?>:</strong> <?php _e('Construction progress and milestones', 'ricelipka-theme'); ?></li>
            <li><strong><?php _e('Award Announcement', 'ricelipka-theme'); ?>:</strong> <?php _e('Recognition and achievements', 'ricelipka-theme'); ?></li>
        </ul>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get Project Portfolio help content
     */
    private static function get_project_portfolio_help() {
        ob_start();
        ?>
        <h2><?php _e('Project Portfolio Block', 'ricelipka-theme'); ?></h2>
        
        <p><?php _e('The Project Portfolio block showcases architectural projects with detailed information, image galleries, and progress tracking capabilities.', 'ricelipka-theme'); ?></p>
        
        <h3><?php _e('Field Reference', 'ricelipka-theme'); ?></h3>
        <table class="field-help-table">
            <thead>
                <tr>
                    <th><?php _e('Field', 'ricelipka-theme'); ?></th>
                    <th><?php _e('Purpose', 'ricelipka-theme'); ?></th>
                    <th><?php _e('Guidelines', 'ricelipka-theme'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="field-name"><?php _e('Project Name', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Official project or building name', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Use the name clients and public know. Avoid internal project codes.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Completion Status', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Current project phase', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Completed, In Progress, or Planned. Update as project advances.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Completion %', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Progress indicator for ongoing projects', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Base on construction milestones: 25% foundation, 50% structure, etc.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Project Type', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Category of architectural work', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Cultural, Academic, Offices, Retail & Commercial, Institutional, Planning, Exhibitions, Research & Installation.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Client', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Organization that commissioned the project', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Use official organization name. Example: "City of Portland".', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Location', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Project location', 'ricelipka-theme'); ?></td>
                    <td><?php _e('City, state for public projects. Full address if appropriate.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Image Gallery', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Multiple project photos', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Include exterior, interior, details, and progress shots.', 'ricelipka-theme'); ?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="help-tip">
            <?php _e('Use descriptive alt text for gallery images - it helps with accessibility and enables automatic image categorization (exterior, interior, detail, construction).', 'ricelipka-theme'); ?>
        </div>
        
        <h3><?php _e('Project Year', 'ricelipka-theme'); ?></h3>
        <p><?php _e('Enter the year the project was completed or is expected to be completed. This helps with chronological organization and filtering.', 'ricelipka-theme'); ?></p>
        
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get Award Information help content
     */
    private static function get_award_information_help() {
        ob_start();
        ?>
        <h2><?php _e('Award Information Block', 'ricelipka-theme'); ?></h2>
        
        <p><?php _e('The Award Information block documents awards and recognition with project associations, timeline visualization, and achievement showcase features.', 'ricelipka-theme'); ?></p>
        
        <h3><?php _e('Field Reference', 'ricelipka-theme'); ?></h3>
        <table class="field-help-table">
            <thead>
                <tr>
                    <th><?php _e('Field', 'ricelipka-theme'); ?></th>
                    <th><?php _e('Purpose', 'ricelipka-theme'); ?></th>
                    <th><?php _e('Guidelines', 'ricelipka-theme'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="field-name"><?php _e('Award Name', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Official name of the award', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Use exact name as it appears on certificate or announcement.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Awarding Organization', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Organization that presented the award', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Use full official name rather than abbreviations.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Associated Project', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Project that received this recognition', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Creates automatic cross-references. Only link directly recognized projects.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Date Received', 'ricelipka-theme'); ?></td>
                    <td><?php _e('When award was received or announced', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Use ceremony date or official announcement date.', 'ricelipka-theme'); ?></td>
                </tr>
                <tr>
                    <td class="field-name"><?php _e('Recognition Image', 'ricelipka-theme'); ?></td>
                    <td><?php _e('Certificate, trophy, or ceremony photo', 'ricelipka-theme'); ?></td>
                    <td><?php _e('High-quality scans for certificates. Include ceremony photos when available.', 'ricelipka-theme'); ?></td>
                </tr>
            </tbody>
        </table>
        
        <h3><?php _e('Automatic Features', 'ricelipka-theme'); ?></h3>
        <p><?php _e('The Award Information block automatically provides:', 'ricelipka-theme'); ?></p>
        <ul>
            <li><strong><?php _e('Timeline Visualization', 'ricelipka-theme'); ?>:</strong> <?php _e('Shows award in context with project completion', 'ricelipka-theme'); ?></li>
            <li><strong><?php _e('Cross-References', 'ricelipka-theme'); ?>:</strong> <?php _e('Links to associated projects and related awards', 'ricelipka-theme'); ?></li>
            <li><strong><?php _e('Certificate Lightbox', 'ricelipka-theme'); ?>:</strong> <?php _e('Click to view full-size certificate images', 'ricelipka-theme'); ?></li>
            <li><strong><?php _e('Related Recognition', 'ricelipka-theme'); ?>:</strong> <?php _e('Shows other awards from the same organization', 'ricelipka-theme'); ?></li>
        </ul>
        
        <div class="help-tip">
            <?php _e('When scanning certificates, use high resolution (300 DPI) and save as JPEG or PNG. This ensures text remains readable when viewed full-size.', 'ricelipka-theme'); ?>
        </div>
        
        <h3><?php _e('Project Association Guidelines', 'ricelipka-theme'); ?></h3>
        <ul>
            <li><?php _e('Only link projects that directly received this specific recognition', 'ricelipka-theme'); ?></li>
            <li><?php _e('Don\'t link projects just because they\'re by the same client', 'ricelipka-theme'); ?></li>
            <li><?php _e('Firm-wide awards don\'t need project associations', 'ricelipka-theme'); ?></li>
            <li><?php _e('Individual recognition should not be linked to projects', 'ricelipka-theme'); ?></li>
        </ul>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get Best Practices help content
     */
    private static function get_best_practices_help() {
        ob_start();
        ?>
        <h2><?php _e('Best Practices & Tips', 'ricelipka-theme'); ?></h2>
        
        <h3><?php _e('Content Strategy', 'ricelipka-theme'); ?></h3>
        <div class="help-tip">
            <?php _e('Consistency is key to professional presentation. Use the same style and tone across all content types.', 'ricelipka-theme'); ?>
        </div>
        
        <h4><?php _e('Writing Guidelines', 'ricelipka-theme'); ?></h4>
        <ul>
            <li><?php _e('Use active voice and specific details', 'ricelipka-theme'); ?></li>
            <li><?php _e('Include project names and locations for better SEO', 'ricelipka-theme'); ?></li>
            <li><?php _e('Write for your audience: clients, peers, and community members', 'ricelipka-theme'); ?></li>
            <li><?php _e('Proofread all content before publishing', 'ricelipka-theme'); ?></li>
        </ul>
        
        <h4><?php _e('Image Guidelines', 'ricelipka-theme'); ?></h4>
        <ul>
            <li><?php _e('Use high-quality, professional photography', 'ricelipka-theme'); ?></li>
            <li><?php _e('Optimize images for web (under 1MB file size)', 'ricelipka-theme'); ?></li>
            <li><?php _e('Include descriptive alt text for accessibility', 'ricelipka-theme'); ?></li>
            <li><?php _e('Maintain consistent style and quality across all images', 'ricelipka-theme'); ?></li>
        </ul>
        
        <h3><?php _e('SEO Optimization', 'ricelipka-theme'); ?></h3>
        <h4><?php _e('Title Optimization', 'ricelipka-theme'); ?></h4>
        <ul>
            <li><?php _e('Include location and project type in titles', 'ricelipka-theme'); ?></li>
            <li><?php _e('Use specific award names rather than generic terms', 'ricelipka-theme'); ?></li>
            <li><?php _e('Keep titles under 60 characters for search results', 'ricelipka-theme'); ?></li>
        </ul>
        
        <h4><?php _e('Content Structure', 'ricelipka-theme'); ?></h4>
        <ul>
            <li><?php _e('Use headings (H2, H3) to organize long content', 'ricelipka-theme'); ?></li>
            <li><?php _e('Include relevant keywords naturally in content', 'ricelipka-theme'); ?></li>
            <li><?php _e('Link to related projects and awards when appropriate', 'ricelipka-theme'); ?></li>
        </ul>
        
        <h3><?php _e('Workflow Tips', 'ricelipka-theme'); ?></h3>
        <h4><?php _e('Content Planning', 'ricelipka-theme'); ?></h4>
        <ul>
            <li><?php _e('Gather all information and images before starting', 'ricelipka-theme'); ?></li>
            <li><?php _e('Use the block patterns as starting templates', 'ricelipka-theme'); ?></li>
            <li><?php _e('Preview content before publishing to check formatting', 'ricelipka-theme'); ?></li>
        </ul>
        
        <h4><?php _e('Quality Control', 'ricelipka-theme'); ?></h4>
        <ul>
            <li><?php _e('Check all links work correctly', 'ricelipka-theme'); ?></li>
            <li><?php _e('Verify dates and project information are accurate', 'ricelipka-theme'); ?></li>
            <li><?php _e('Review content on mobile devices', 'ricelipka-theme'); ?></li>
        </ul>
        
        <h3><?php _e('Common Issues & Solutions', 'ricelipka-theme'); ?></h3>
        
        <h4><?php _e('Images Not Displaying', 'ricelipka-theme'); ?></h4>
        <ul>
            <li><?php _e('Check file size (should be under 10MB)', 'ricelipka-theme'); ?></li>
            <li><?php _e('Ensure file format is JPG, PNG, or WebP', 'ricelipka-theme'); ?></li>
            <li><?php _e('Try uploading images one at a time for galleries', 'ricelipka-theme'); ?></li>
        </ul>
        
        <h4><?php _e('Block Not Saving', 'ricelipka-theme'); ?></h4>
        <ul>
            <li><?php _e('Fill in all required fields (marked with *)', 'ricelipka-theme'); ?></li>
            <li><?php _e('Check for validation errors in red text', 'ricelipka-theme'); ?></li>
            <li><?php _e('Ensure URLs include http:// or https://', 'ricelipka-theme'); ?></li>
        </ul>
        
        <h4><?php _e('Preview Not Updating', 'ricelipka-theme'); ?></h4>
        <ul>
            <li><?php _e('Save the post to refresh the preview', 'ricelipka-theme'); ?></li>
            <li><?php _e('Clear browser cache if changes don\'t appear', 'ricelipka-theme'); ?></li>
            <li><?php _e('Check that all required fields are completed', 'ricelipka-theme'); ?></li>
        </ul>
        
        <div class="help-tip">
            <?php _e('Need more help? The tooltips next to each field provide specific guidance, and the tutorial system can walk you through creating your first content of each type.', 'ricelipka-theme'); ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Enqueue help documentation assets
     */
    public static function enqueue_help_docs_assets($hook) {
        if ($hook !== 'posts_page_acf-blocks-help') {
            return;
        }
        
        wp_enqueue_style(
            'ricelipka-help-docs',
            get_template_directory_uri() . '/assets/css/help-documentation.css',
            array(),
            wp_get_theme()->get('Version')
        );
    }
    
    /**
     * AJAX handler for getting block help
     */
    public static function ajax_get_block_help() {
        check_ajax_referer('ricelipka_help_nonce', 'nonce');
        
        $block_type = sanitize_text_field($_POST['block_type']);
        
        $help_methods = array(
            'news-article' => 'get_news_article_help',
            'project-portfolio' => 'get_project_portfolio_help',
            'award-information' => 'get_award_information_help'
        );
        
        if (isset($help_methods[$block_type])) {
            $help_content = call_user_func(array(__CLASS__, $help_methods[$block_type]));
            wp_send_json_success($help_content);
        } else {
            wp_send_json_error('Block type not found');
        }
    }
}

// Initialize documentation system
RiceLipka_ACF_Help_Documentation::init();