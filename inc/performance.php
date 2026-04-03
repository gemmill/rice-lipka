<?php
/**
 * Performance optimization functions
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enable comprehensive image optimization and lazy loading
 */
function ricelipka_image_optimization() {
    // Add lazy loading to images
    add_filter('wp_get_attachment_image_attributes', 'ricelipka_add_lazy_loading', 10, 3);
    
    // Add comprehensive responsive image sizes
    add_image_size('project-thumbnail', 400, 300, true);
    add_image_size('project-medium', 600, 450, true);
    add_image_size('project-large', 800, 600, true);
    add_image_size('project-xlarge', 1200, 900, true);
    add_image_size('news-featured', 600, 400, true);
    add_image_size('news-featured-large', 800, 533, true);
    add_image_size('award-certificate', 500, 350, true);
    add_image_size('award-certificate-large', 700, 490, true);
    add_image_size('gallery-thumb', 300, 300, true);
    add_image_size('gallery-medium', 500, 500, true);
    add_image_size('gallery-large', 800, 800, true);
    
    // Enable WebP support
    add_filter('wp_generate_attachment_metadata', 'ricelipka_generate_webp_images', 10, 2);
    
    // Add srcset and sizes attributes for better responsive images
    add_filter('wp_calculate_image_srcset', 'ricelipka_custom_srcset', 10, 5);
}
add_action('after_setup_theme', 'ricelipka_image_optimization');

/**
 * Add comprehensive lazy loading attributes to images
 */
function ricelipka_add_lazy_loading($attr, $attachment, $size) {
    // Skip if in admin, feed, or AMP
    if (is_admin() || is_feed() || function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        return $attr;
    }

    // Skip for above-the-fold images (first 2 images)
    static $image_count = 0;
    $image_count++;
    
    if ($image_count <= 2) {
        return $attr;
    }

    // Add loading="lazy" for better performance
    $attr['loading'] = 'lazy';
    
    // Add decoding="async" for non-blocking image decoding
    $attr['decoding'] = 'async';
    
    // Add intersection observer data attributes for advanced lazy loading
    $attr['data-lazy'] = 'true';
    
    return $attr;
}

/**
 * Generate WebP images for better compression
 */
function ricelipka_generate_webp_images($metadata, $attachment_id) {
    if (!function_exists('imagewebp')) {
        return $metadata;
    }
    
    $upload_dir = wp_upload_dir();
    $file_path = get_attached_file($attachment_id);
    
    if (!$file_path || !file_exists($file_path)) {
        return $metadata;
    }
    
    $file_info = pathinfo($file_path);
    $webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';
    
    // Generate WebP for main image
    ricelipka_create_webp_image($file_path, $webp_path);
    
    // Generate WebP for all image sizes
    if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
        foreach ($metadata['sizes'] as $size => $size_data) {
            $size_file_path = $upload_dir['path'] . '/' . $size_data['file'];
            $size_webp_path = $upload_dir['path'] . '/' . pathinfo($size_data['file'], PATHINFO_FILENAME) . '.webp';
            
            if (file_exists($size_file_path)) {
                ricelipka_create_webp_image($size_file_path, $size_webp_path);
            }
        }
    }
    
    return $metadata;
}

/**
 * Create WebP image from source
 */
function ricelipka_create_webp_image($source_path, $webp_path) {
    $image_info = getimagesize($source_path);
    
    if (!$image_info) {
        return false;
    }
    
    $mime_type = $image_info['mime'];
    $image = null;
    
    switch ($mime_type) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source_path);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source_path);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source_path);
            break;
        default:
            return false;
    }
    
    if ($image) {
        // Create WebP with 85% quality for good balance of size and quality
        $result = imagewebp($image, $webp_path, 85);
        imagedestroy($image);
        return $result;
    }
    
    return false;
}

/**
 * Custom srcset for responsive images
 */
function ricelipka_custom_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    // Add WebP sources if available
    foreach ($sources as $width => $source) {
        $webp_url = ricelipka_get_webp_url($source['url']);
        if ($webp_url) {
            $sources[$width]['webp'] = $webp_url;
        }
    }
    
    return $sources;
}

/**
 * Get WebP URL if WebP version exists
 */
function ricelipka_get_webp_url($image_url) {
    $upload_dir = wp_upload_dir();
    $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_url);
    $webp_path = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $image_path);
    
    if (file_exists($webp_path)) {
        return preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $image_url);
    }
    
    return false;
}

/**
 * Comprehensive CSS and JavaScript optimization
 */
function ricelipka_optimize_assets() {
    // Remove unnecessary WordPress assets
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Remove WordPress version from head
    remove_action('wp_head', 'wp_generator');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove wlwmanifest link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove REST API links if not needed
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // Remove DNS prefetch to s.w.org
    remove_action('wp_head', 'wp_resource_hints', 2);
    
    // Defer non-critical JavaScript
    add_filter('script_loader_tag', 'ricelipka_defer_scripts', 10, 3);
    
    // Minify CSS and JS in production
    if (!WP_DEBUG) {
        add_filter('style_loader_tag', 'ricelipka_minify_css', 10, 4);
    }
}
add_action('init', 'ricelipka_optimize_assets');

/**
 * Defer non-critical JavaScript
 */
function ricelipka_defer_scripts($tag, $handle, $src) {
    // Scripts to defer (non-critical)
    $defer_scripts = array(
        'ricelipka-acf-help',
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace('<script ', '<script defer ', $tag);
    }
    
    return $tag;
}

/**
 * Add async attribute to non-critical CSS
 */
function ricelipka_minify_css($html, $handle, $href, $media) {
    // Non-critical CSS files to load asynchronously
    $async_styles = array(
        'ricelipka-acf-help'
    );
    
    if (in_array($handle, $async_styles)) {
        $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
        $html .= '<noscript><link rel="stylesheet" href="' . $href . '" media="' . $media . '"></noscript>';
    }
    
    return $html;
}

/**
 * Comprehensive caching headers and strategies
 */
function ricelipka_add_cache_headers() {
    if (!is_admin()) {
        // Set cache headers for static assets
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|webp)$/i', $_SERVER['REQUEST_URI'])) {
            header('Cache-Control: public, max-age=31536000, immutable'); // 1 year with immutable
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
            
            // Add Vary header for WebP images
            if (preg_match('/\.(webp)$/i', $_SERVER['REQUEST_URI'])) {
                header('Vary: Accept');
            }
        }
        
        // Cache HTML pages for shorter duration
        if (!is_user_logged_in() && !is_admin()) {
            header('Cache-Control: public, max-age=3600'); // 1 hour for HTML
            header('Vary: Accept-Encoding, Cookie');
        }
    }
}
add_action('init', 'ricelipka_add_cache_headers');

/**
 * Enable Gzip compression
 */
function ricelipka_enable_gzip_compression() {
    if (!is_admin() && !headers_sent()) {
        if (function_exists('gzencode') && !ob_get_level()) {
            ob_start('ricelipka_gzip_handler');
        }
    }
}
add_action('init', 'ricelipka_enable_gzip_compression');

/**
 * Gzip compression handler
 */
function ricelipka_gzip_handler($buffer) {
    if (strlen($buffer) < 2048) {
        return $buffer; // Don't compress small files
    }
    
    $encoding = '';
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
        if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
            $encoding = 'gzip';
        } elseif (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== false) {
            $encoding = 'deflate';
        }
    }
    
    if ($encoding) {
        header('Content-Encoding: ' . $encoding);
        if ($encoding == 'gzip') {
            return gzencode($buffer, 6);
        } elseif ($encoding == 'deflate') {
            return gzdeflate($buffer, 6);
        }
    }
    
    return $buffer;
}

/**
 * Optimize database queries
 */
function ricelipka_optimize_queries() {
    // Remove unnecessary queries on frontend
    if (!is_admin()) {
        // Disable post revisions on frontend
        remove_action('pre_post_update', 'wp_save_post_revision');
        
        // Limit post revisions
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', 3);
        }
    }
}
add_action('init', 'ricelipka_optimize_queries');

/**
 * Comprehensive resource preloading and optimization
 */
function ricelipka_preload_resources() {
    // Preload critical CSS
    echo '<link rel="preload" href="' . get_stylesheet_uri() . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    
    // Preload responsive layouts CSS (critical)
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/css/responsive-layouts.css" as="style">';
    
    // Preload critical JavaScript
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/js/main.js" as="script">';
    
    // Preload critical fonts (if using custom fonts)
    // echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/fonts/font.woff2" as="font" type="font/woff2" crossorigin>';
    
    // DNS prefetch for external resources
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
    
    // Preconnect to critical third-party domains
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    
    // Add resource hints for better performance
    ricelipka_add_resource_hints();
}
add_action('wp_head', 'ricelipka_preload_resources', 1);

/**
 * Add intelligent resource hints
 */
function ricelipka_add_resource_hints() {
    // Prefetch next likely pages based on current page
    if (is_home() || is_front_page()) {
        // Prefetch category pages from homepage
        $categories = get_categories(array('hide_empty' => true));
        $count = 0;
        foreach ($categories as $category) {
            if ($count >= 3) break; // Limit prefetch to avoid overloading
            if (in_array($category->slug, array('news', 'projects', 'awards'))) {
                echo '<link rel="prefetch" href="' . get_category_link($category->term_id) . '">';
                $count++;
            }
        }
    }
    
    // Prefetch images that are likely to be viewed
    if (is_category() || is_archive()) {
        // Prefetch first few post images
        global $wp_query;
        if ($wp_query->have_posts()) {
            $post_count = 0;
            while ($wp_query->have_posts() && $post_count < 3) {
                $wp_query->the_post();
                $thumbnail_id = get_post_thumbnail_id();
                if ($thumbnail_id) {
                    $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'medium');
                    if ($thumbnail_url) {
                        echo '<link rel="prefetch" href="' . $thumbnail_url . '">';
                    }
                }
                $post_count++;
            }
            wp_reset_postdata();
        }
    }
}

/**
 * Optimize WordPress heartbeat
 */
function ricelipka_optimize_heartbeat() {
    // Disable heartbeat on frontend
    if (!is_admin()) {
        wp_deregister_script('heartbeat');
    }
}
add_action('init', 'ricelipka_optimize_heartbeat');

/**
 * Compress HTML output
 */
function ricelipka_compress_html($buffer) {
    // Only compress on frontend
    if (is_admin()) {
        return $buffer;
    }
    
    // Remove unnecessary whitespace
    $buffer = preg_replace('/\s+/', ' ', $buffer);
    $buffer = preg_replace('/>\s+</', '><', $buffer);
    
    return trim($buffer);
}

/**
 * Enable HTML compression
 */
function ricelipka_enable_compression() {
    if (!is_admin() && !is_feed()) {
        ob_start('ricelipka_compress_html');
    }
}
add_action('template_redirect', 'ricelipka_enable_compression');

/**
 * Optimize image delivery
 */
function ricelipka_optimize_image_delivery($html, $id, $caption, $title, $align, $url, $size, $alt) {
    // Add WebP support detection
    $webp_support = '<script>
        (function() {
            var webP = new Image();
            webP.onload = webP.onerror = function () {
                if (webP.height == 2) {
                    document.documentElement.classList.add("webp");
                }
            };
            webP.src = "data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA";
        })();
    </script>';
    
    // Only add script once
    static $webp_script_added = false;
    if (!$webp_script_added && !is_admin()) {
        add_action('wp_footer', function() use ($webp_support) {
            echo $webp_support;
        });
        $webp_script_added = true;
    }
    
    return $html;
}
add_filter('image_send_to_editor', 'ricelipka_optimize_image_delivery', 10, 8);

/**
 * Critical CSS inlining for above-the-fold content
 */
function ricelipka_inline_critical_css() {
    // Only on homepage and category pages
    if (is_home() || is_category()) {
        $critical_css = '

        ';
        
        echo '<style id="critical-css">' . $critical_css . '</style>';
    }
}
add_action('wp_head', 'ricelipka_inline_critical_css', 2);


/**
/**
 * Optimize database queries and reduce overhead
 */
function ricelipka_optimize_database() {
    // Remove unnecessary queries on frontend
    if (!is_admin()) {
        // Disable post revisions on frontend
        remove_action('pre_post_update', 'wp_save_post_revision');
        
        // Limit post revisions
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', 3);
        }
        
        // Disable pingbacks and trackbacks
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('wp_headers', 'ricelipka_remove_x_pingback');
        
        // Optimize WordPress queries
        add_action('pre_get_posts', 'ricelipka_optimize_main_queries');
    }
}
add_action('init', 'ricelipka_optimize_database');

/**
 * Remove X-Pingback header
 */
function ricelipka_remove_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}

/**
 * Optimize WordPress queries for better performance
 */
function ricelipka_optimize_main_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Optimize category queries
        if (is_category()) {
            $query->set('posts_per_page', 12); // Reasonable pagination
            $query->set('meta_query', array(
                'relation' => 'OR',
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'NOT EXISTS'
                )
            ));
        }
        
        // Optimize search queries
        if (is_search()) {
            $query->set('posts_per_page', 10);
            // Exclude certain post types from search if needed
            $query->set('post_type', 'post');
        }
    }
}

/**
 * Generate responsive srcset for an image
 */
function ricelipka_generate_responsive_srcset($attachment_id, $sizes = array()) {
    if (!$attachment_id || empty($sizes)) {
        return '';
    }
    
    $srcset_parts = array();
    
    foreach ($sizes as $size) {
        $image_url = wp_get_attachment_image_url($attachment_id, $size);
        if ($image_url) {
            $image_meta = wp_get_attachment_metadata($attachment_id);
            if (isset($image_meta['sizes'][$size]['width'])) {
                $width = $image_meta['sizes'][$size]['width'];
                $srcset_parts[] = $image_url . ' ' . $width . 'w';
            }
        }
    }
    
    return implode(', ', $srcset_parts);
}

/**
 * Generate WebP srcset for an image
 */
function ricelipka_generate_webp_srcset($attachment_id, $sizes = array()) {
    if (!$attachment_id || empty($sizes)) {
        return '';
    }
    
    $srcset_parts = array();
    
    foreach ($sizes as $size) {
        $image_url = wp_get_attachment_image_url($attachment_id, $size);
        if ($image_url) {
            $webp_url = ricelipka_get_webp_url($image_url);
            if ($webp_url) {
                $image_meta = wp_get_attachment_metadata($attachment_id);
                if (isset($image_meta['sizes'][$size]['width'])) {
                    $width = $image_meta['sizes'][$size]['width'];
                    $srcset_parts[] = $webp_url . ' ' . $width . 'w';
                }
            }
        }
    }
    
    return implode(', ', $srcset_parts);
}

/**
 * Add performance optimization for ACF image fields
 */
function ricelipka_optimize_acf_images($value, $post_id, $field) {
    // Only process image fields
    if ($field['type'] !== 'image' && $field['type'] !== 'gallery') {
        return $value;
    }
    
    // Skip if in admin
    if (is_admin()) {
        return $value;
    }
    
    // Process single image
    if ($field['type'] === 'image' && is_array($value)) {
        $value = ricelipka_add_performance_attributes_to_image($value);
    }
    
    // Process gallery images
    if ($field['type'] === 'gallery' && is_array($value)) {
        foreach ($value as $index => $image) {
            if (is_array($image)) {
                $value[$index] = ricelipka_add_performance_attributes_to_image($image, $index);
            }
        }
    }
    
    return $value;
}
add_filter('acf/format_value', 'ricelipka_optimize_acf_images', 10, 3);

/**
 * Add performance attributes to image array
 */
function ricelipka_add_performance_attributes_to_image($image, $index = 0) {
    if (!is_array($image) || !isset($image['ID'])) {
        return $image;
    }
    
    $image_id = $image['ID'];
    
    // Add WebP URL if available
    if (isset($image['url'])) {
        $image['webp_url'] = ricelipka_get_webp_url($image['url']);
    }
    
    // Add responsive srcsets
    $image['srcset'] = ricelipka_generate_responsive_srcset($image_id, array('medium', 'large', 'full'));
    $image['webp_srcset'] = ricelipka_generate_webp_srcset($image_id, array('medium', 'large', 'full'));
    
    // Add loading strategy
    $image['loading'] = $index < 2 ? 'eager' : 'lazy';
    $image['fetchpriority'] = $index < 2 ? 'high' : 'auto';
    
    return $image;
}