<?php
/**
 * Classic Editor and ACF Fields functionality
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Disable the block editor (Gutenberg) and use classic editor
 */
function ricelipka_disable_block_editor() {
    // Disable block editor for posts
    add_filter('use_block_editor_for_post', '__return_false');
    
    // Disable block editor for post types
    add_filter('use_block_editor_for_post_type', '__return_false');
    
    // Remove block editor assets
    remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');
    
    // Remove block editor styles from frontend
    add_action('wp_print_styles', 'ricelipka_remove_block_styles', 100);
}
add_action('init', 'ricelipka_disable_block_editor');

/**
 * Remove block editor styles from frontend
 */
function ricelipka_remove_block_styles() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style');
}

/**
 * Remove block editor from admin
 */
function ricelipka_remove_block_editor_admin() {
    // Remove block editor scripts and styles from admin
    remove_action('admin_enqueue_scripts', 'wp_enqueue_editor_block_directory_assets');
    
    // Disable block widgets
    remove_theme_support('widgets-block-editor');
}
add_action('admin_init', 'ricelipka_remove_block_editor_admin');

/**
 * Add custom image sizes for ACF fields
 */
function ricelipka_add_image_sizes() {
    add_image_size('news-featured', 800, 450, true);
    add_image_size('project-thumbnail', 400, 300, true);
    add_image_size('project-large', 1200, 800, true);
    add_image_size('project-gallery', 600, 400, true);
    add_image_size('event-banner', 1200, 300, true);
    add_image_size('award-certificate', 400, 300, true);
}
add_action('after_setup_theme', 'ricelipka_add_image_sizes');

/**
 * Add custom image sizes to media library
 */
function ricelipka_add_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'news-featured' => __('News Featured (800x450)', 'ricelipka-theme'),
        'project-thumbnail' => __('Project Thumbnail (400x300)', 'ricelipka-theme'),
        'project-large' => __('Project Large (1200x800)', 'ricelipka-theme'),
        'project-gallery' => __('Project Gallery (600x400)', 'ricelipka-theme'),
        'event-banner' => __('Event Banner (1200x300)', 'ricelipka-theme'),
        'award-certificate' => __('Award Certificate (400x300)', 'ricelipka-theme'),
    ));
}
add_filter('image_size_names_choose', 'ricelipka_add_custom_image_sizes');

/**
 * Enhance classic editor with better formatting
 */
function ricelipka_enhance_classic_editor() {
    // Add more buttons to TinyMCE
    add_filter('mce_buttons', 'ricelipka_mce_buttons');
    add_filter('mce_buttons_2', 'ricelipka_mce_buttons_2');
    
    // Add custom styles to editor
    add_editor_style('assets/css/editor-style.css');
}
add_action('init', 'ricelipka_enhance_classic_editor');

/**
 * Add buttons to TinyMCE toolbar
 */
function ricelipka_mce_buttons($buttons) {
    array_push($buttons, 'separator', 'styleselect');
    return $buttons;
}

/**
 * Add more buttons to second TinyMCE toolbar
 */
function ricelipka_mce_buttons_2($buttons) {
    array_push($buttons, 'fontselect', 'fontsizeselect');
    return $buttons;
}

/**
 * Add custom styles to TinyMCE
 */
function ricelipka_mce_before_init($init_array) {
    $style_formats = array(
        array(
            'title' => 'Project Highlight',
            'block' => 'div',
            'classes' => 'project-highlight',
            'wrapper' => true,
        ),
        array(
            'title' => 'News Excerpt',
            'block' => 'p',
            'classes' => 'news-excerpt',
        ),
        array(
            'title' => 'Event Date',
            'inline' => 'span',
            'classes' => 'event-date',
        ),
        array(
            'title' => 'Award Title',
            'block' => 'h3',
            'classes' => 'award-title',
        ),
    );
    
    $init_array['style_formats'] = json_encode($style_formats);
    return $init_array;
}
add_filter('tiny_mce_before_init', 'ricelipka_mce_before_init');

/**
 * Add CSS for classic editor styles
 */
function ricelipka_classic_editor_styles() {
    ?>
    <style>
    /* Classic Editor Enhancements */
    .wp-editor-wrap {
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .wp-editor-container {
        background: white;
    }
    
    /* Custom content styles */
    .project-highlight {
        background: #f0f6fc;
        border-left: 4px solid #0073aa;
        padding: 1rem;
        margin: 1rem 0;
    }
    
    .news-excerpt {
        font-style: italic;
        color: #666;
        font-size: 1.1em;
        line-height: 1.4;
    }
    
    .event-date {
        background: #0073aa;
        color: white;
        padding: 2px 8px;
        border-radius: 3px;
        font-weight: bold;
    }
    
    .award-title {
        color: #d63638;
        border-bottom: 2px solid #d63638;
        padding-bottom: 0.5rem;
    }
    </style>
    <?php
}
add_action('admin_head', 'ricelipka_classic_editor_styles');

/**
 * Enable media upload in classic editor
 */
function ricelipka_enable_media_upload() {
    if (function_exists('wp_enqueue_media')) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'ricelipka_enable_media_upload');

/**
 * Add media buttons to classic editor
 */
function ricelipka_add_media_buttons() {
    echo '<a href="#" class="button ricelipka-add-media" data-editor="content">Add Media</a>';
}
add_action('media_buttons', 'ricelipka_add_media_buttons');

/**
 * Add JavaScript for enhanced media handling in classic editor
 */
function ricelipka_classic_editor_scripts() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Enhanced media uploader for classic editor
        var mediaUploader;
        
        $('.ricelipka-add-media').click(function(e) {
            e.preventDefault();
            
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Choose Media',
                button: {
                    text: 'Insert into post'
                },
                multiple: true
            });
            
            // When media is selected, insert into editor
            mediaUploader.on('select', function() {
                var selection = mediaUploader.state().get('selection');
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    var content = '';
                    
                    if (attachment.type === 'image') {
                        content = '<img src="' + attachment.url + '" alt="' + attachment.alt + '" />';
                    } else {
                        content = '<a href="' + attachment.url + '">' + attachment.title + '</a>';
                    }
                    
                    // Insert into TinyMCE editor
                    if (typeof tinyMCE !== 'undefined' && tinyMCE.activeEditor) {
                        tinyMCE.activeEditor.execCommand('mceInsertContent', false, content);
                    }
                });
            });
            
            // Open the uploader dialog
            mediaUploader.open();
        });
        
        // Add gallery functionality
        $('.ricelipka-add-gallery').click(function(e) {
            e.preventDefault();
            
            var galleryUploader = wp.media({
                title: 'Create Gallery',
                button: {
                    text: 'Create Gallery'
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });
            
            galleryUploader.on('select', function() {
                var selection = galleryUploader.state().get('selection');
                var gallery = '<div class="ricelipka-gallery">';
                
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    gallery += '<div class="gallery-item">';
                    gallery += '<img src="' + attachment.sizes.medium.url + '" alt="' + attachment.alt + '" />';
                    gallery += '</div>';
                });
                
                gallery += '</div>';
                
                if (typeof tinyMCE !== 'undefined' && tinyMCE.activeEditor) {
                    tinyMCE.activeEditor.execCommand('mceInsertContent', false, gallery);
                }
            });
            
            galleryUploader.open();
        });
    });
    </script>
    <?php
}
add_action('admin_footer-post.php', 'ricelipka_classic_editor_scripts');
add_action('admin_footer-post-new.php', 'ricelipka_classic_editor_scripts');

/**
 * Add gallery button to media buttons
 */
function ricelipka_add_gallery_button() {
    echo '<a href="#" class="button ricelipka-add-gallery">Add Gallery</a>';
}
add_action('media_buttons', 'ricelipka_add_gallery_button');

/**
 * Add frontend styles for classic editor content
 */
function ricelipka_frontend_classic_styles() {
    ?>
    <style>
    /* Frontend styles for classic editor content */
    .project-highlight {
        background: #f0f6fc;
        border-left: 4px solid #0073aa;
        padding: 1rem;
        margin: 1rem 0;
        border-radius: 0 4px 4px 0;
    }
    
    .news-excerpt {
        font-style: italic;
        color: #666;
        font-size: 1.1em;
        line-height: 1.4;
        margin: 1rem 0;
    }
    
    .event-date {
        background: #0073aa;
        color: white;
        padding: 4px 12px;
        border-radius: 3px;
        font-weight: bold;
        display: inline-block;
        margin: 0.5rem 0;
    }
    
    .award-title {
        color: #d63638;
        border-bottom: 2px solid #d63638;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .ricelipka-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 2rem 0;
    }
    
    .ricelipka-gallery .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 4px;
    }
    
    .ricelipka-gallery .gallery-item img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.3s ease;
    }
    
    .ricelipka-gallery .gallery-item:hover img {
        transform: scale(1.05);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .ricelipka-gallery {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.5rem;
        }
        
        .project-highlight {
            padding: 0.75rem;
            margin: 0.75rem 0;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'ricelipka_frontend_classic_styles');