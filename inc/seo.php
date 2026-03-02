<?php
/**
 * SEO optimization functions
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add structured data for different content types
 */
function ricelipka_add_structured_data() {
    if (is_single()) {
        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);
        
        switch ($post_type) {
            case 'projects':
                ricelipka_project_structured_data($post_id);
                break;
            case 'news':
                ricelipka_article_structured_data($post_id);
                break;
            case 'awards':
                ricelipka_award_structured_data($post_id);
                break;
        }
    } elseif (is_home() || is_category()) {
        ricelipka_organization_structured_data();
    }
}
add_action('wp_head', 'ricelipka_add_structured_data');

/**
 * Project structured data
 */
function ricelipka_project_structured_data($post_id) {
    $project_name = get_field('project_name', $post_id) ?: get_the_title($post_id);
    $location = get_field('location', $post_id);
    $client = get_field('client', $post_id);
    $project_type = get_field('project_type', $post_id);
    $completion_status = get_field('completion_status', $post_id);
    $image_gallery = get_field('image_gallery', $post_id);
    
    $structured_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => $project_name,
        'description' => get_the_excerpt($post_id),
        'url' => get_permalink($post_id),
        'datePublished' => get_the_date('c', $post_id),
        'dateModified' => get_the_modified_date('c', $post_id),
        'author' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
        ),
    );
    
    if ($location) {
        $structured_data['locationCreated'] = array(
            '@type' => 'Place',
            'name' => $location,
        );
    }
    
    if ($client) {
        $structured_data['sponsor'] = array(
            '@type' => 'Organization',
            'name' => $client,
        );
    }
    
    if ($project_type) {
        $structured_data['genre'] = ucfirst(str_replace('_', ' ', $project_type));
    }
    
    if ($image_gallery && is_array($image_gallery)) {
        $images = array();
        foreach ($image_gallery as $image) {
            $images[] = $image['url'];
        }
        $structured_data['image'] = $images;
    }
    
    echo '<script type="application/ld+json">' . json_encode($structured_data, JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Article structured data
 */
function ricelipka_article_structured_data($post_id) {
    $structured_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title($post_id),
        'description' => get_the_excerpt($post_id),
        'url' => get_permalink($post_id),
        'datePublished' => get_the_date('c', $post_id),
        'dateModified' => get_the_modified_date('c', $post_id),
        'author' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
        ),
    );
    
    if (has_post_thumbnail($post_id)) {
        $structured_data['image'] = get_the_post_thumbnail_url($post_id, 'large');
    }
    
    echo '<script type="application/ld+json">' . json_encode($structured_data, JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Award structured data
 */
function ricelipka_award_structured_data($post_id) {
    $award_name = get_field('award_name', $post_id) ?: get_the_title($post_id);
    $awarding_organization = get_field('awarding_organization', $post_id);
    $date_received = get_field('date_received', $post_id);
    $associated_project = get_field('associated_project', $post_id);
    
    $structured_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'Award',
        'name' => $award_name,
        'description' => get_the_excerpt($post_id),
        'url' => get_permalink($post_id),
    );
    
    if ($awarding_organization) {
        $structured_data['sourceOrganization'] = array(
            '@type' => 'Organization',
            'name' => $awarding_organization,
        );
    }
    
    if ($date_received) {
        $structured_data['dateCreated'] = $date_received;
    }
    
    if ($associated_project) {
        $structured_data['about'] = array(
            '@type' => 'CreativeWork',
            'name' => get_the_title($associated_project->ID),
            'url' => get_permalink($associated_project->ID),
        );
    }
    
    echo '<script type="application/ld+json">' . json_encode($structured_data, JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Organization structured data
 */
function ricelipka_organization_structured_data() {
    $structured_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => home_url(),
        'sameAs' => array(
            // Add social media URLs here
        ),
    );
    
    // Add logo if available
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
        $structured_data['logo'] = $logo_url;
    }
    
    echo '<script type="application/ld+json">' . json_encode($structured_data, JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Add breadcrumb structured data
 */
function ricelipka_breadcrumb_structured_data() {
    if (is_single() || is_category()) {
        $breadcrumbs = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array(),
        );
        
        // Home
        $breadcrumbs['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => home_url(),
        );
        
        $position = 2;
        
        if (is_category()) {
            $category = get_queried_object();
            $breadcrumbs['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $category->name,
                'item' => get_category_link($category->term_id),
            );
        } elseif (is_single()) {
            $post_type = get_post_type();
            if (in_array($post_type, array('news', 'projects', 'awards', 'people'))) {
                $post_type_object = get_post_type_object($post_type);
                if ($post_type_object) {
                    $breadcrumbs['itemListElement'][] = array(
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => $post_type_object->labels->name,
                        'item' => get_post_type_archive_link($post_type),
                    );
                    $position++;
                }
            }
            
            $breadcrumbs['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => get_the_title(),
                'item' => get_permalink(),
            );
        }
        
        echo '<script type="application/ld+json">' . json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES) . '</script>';
    }
}
add_action('wp_head', 'ricelipka_breadcrumb_structured_data');

/**
 * Optimize meta descriptions
 */
function ricelipka_meta_description() {
    if (is_single()) {
        $excerpt = get_the_excerpt();
        if ($excerpt) {
            echo '<meta name="description" content="' . esc_attr(wp_trim_words($excerpt, 25)) . '">';
        }
    } elseif (is_category()) {
        $category = get_queried_object();
        if ($category->description) {
            echo '<meta name="description" content="' . esc_attr(wp_trim_words($category->description, 25)) . '">';
        }
    } elseif (is_home()) {
        $description = get_bloginfo('description');
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">';
        }
    }
}
add_action('wp_head', 'ricelipka_meta_description');

/**
 * Add Open Graph meta tags
 */
function ricelipka_open_graph_tags() {
    if (is_single()) {
        echo '<meta property="og:type" content="article">';
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">';
        echo '<meta property="og:description" content="' . esc_attr(wp_trim_words(get_the_excerpt(), 25)) . '">';
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">';
        
        if (has_post_thumbnail()) {
            echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url(null, 'large')) . '">';
        }
    } elseif (is_home() || is_category()) {
        echo '<meta property="og:type" content="website">';
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">';
        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">';
        echo '<meta property="og:url" content="' . esc_url(home_url()) . '">';
    }
    
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">';
}
add_action('wp_head', 'ricelipka_open_graph_tags');

/**
 * Generate XML sitemap
 */
function ricelipka_generate_sitemap() {
    // This is a basic implementation - consider using a plugin for full functionality
    if (isset($_GET['sitemap']) && $_GET['sitemap'] === 'xml') {
        header('Content-Type: application/xml; charset=utf-8');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Home page
        echo '<url>';
        echo '<loc>' . home_url() . '</loc>';
        echo '<changefreq>daily</changefreq>';
        echo '<priority>1.0</priority>';
        echo '</url>';
        
        // Category pages
        $categories = get_categories(array('hide_empty' => false));
        foreach ($categories as $category) {
            echo '<url>';
            echo '<loc>' . get_category_link($category->term_id) . '</loc>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>';
        }
        
        // Posts
        $posts = get_posts(array('numberposts' => -1, 'post_status' => 'publish'));
        foreach ($posts as $post) {
            echo '<url>';
            echo '<loc>' . get_permalink($post->ID) . '</loc>';
            echo '<lastmod>' . get_the_modified_date('c', $post->ID) . '</lastmod>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.6</priority>';
            echo '</url>';
        }
        
        echo '</urlset>';
        exit;
    }
}
add_action('init', 'ricelipka_generate_sitemap');