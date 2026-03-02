<?php
/**
 * Chronological ordering for time-sensitive content
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modify main query for chronological ordering
 */
function ricelipka_modify_category_query($query) {
    // Only modify main query on frontend category pages
    if (!is_admin() && $query->is_main_query() && is_category()) {
        $category = get_queried_object();
        
        if ($category && in_array($category->slug, ['news', 'events'])) {
            // Set posts per page for better pagination
            $query->set('posts_per_page', 12);
            
            if ($category->slug === 'news') {
                // News: Order by publication_date (ACF field) or post_date, newest first
                $query->set('meta_key', 'publication_date');
                $query->set('orderby', array(
                    'meta_value' => 'DESC',
                    'date' => 'DESC'
                ));
                $query->set('meta_type', 'DATE');
                
                // Include posts without publication_date
                $query->set('meta_query', array(
                    'relation' => 'OR',
                    array(
                        'key' => 'publication_date',
                        'compare' => 'EXISTS'
                    ),
                    array(
                        'key' => 'publication_date',
                        'compare' => 'NOT EXISTS'
                    )
                ));
                
            } elseif ($category->slug === 'events') {
                // Events: Order by event_date, upcoming first, then past events
                $today = date('Y-m-d');
                
                // Check if we're filtering for upcoming or past events
                $event_filter = get_query_var('event_filter', 'all');
                
                if ($event_filter === 'upcoming') {
                    $query->set('meta_query', array(
                        array(
                            'key' => 'event_date',
                            'value' => $today,
                            'compare' => '>=',
                            'type' => 'DATE'
                        )
                    ));
                    $query->set('meta_key', 'event_date');
                    $query->set('orderby', 'meta_value');
                    $query->set('order', 'ASC');
                    $query->set('meta_type', 'DATE');
                    
                } elseif ($event_filter === 'past') {
                    $query->set('meta_query', array(
                        array(
                            'key' => 'event_date',
                            'value' => $today,
                            'compare' => '<',
                            'type' => 'DATE'
                        )
                    ));
                    $query->set('meta_key', 'event_date');
                    $query->set('orderby', 'meta_value');
                    $query->set('order', 'DESC');
                    $query->set('meta_type', 'DATE');
                    
                } else {
                    // All events: upcoming first (ASC), then past events (DESC)
                    $query->set('meta_key', 'event_date');
                    $query->set('orderby', array(
                        'meta_value' => 'ASC',
                        'date' => 'DESC'
                    ));
                    $query->set('meta_type', 'DATE');
                    
                    // Custom ordering to show upcoming first, then past
                    add_filter('posts_orderby', 'ricelipka_events_custom_orderby', 10, 2);
                }
            }
        }
    }
}
add_action('pre_get_posts', 'ricelipka_modify_category_query');

/**
 * Custom orderby for events to show upcoming first, then past events
 */
function ricelipka_events_custom_orderby($orderby, $query) {
    global $wpdb;
    
    if (!is_admin() && $query->is_main_query() && is_category()) {
        $category = get_queried_object();
        
        if ($category && $category->slug === 'events') {
            $today = date('Y-m-d');
            
            // Custom SQL to order upcoming events ASC, past events DESC
            $orderby = "
                CASE 
                    WHEN {$wpdb->postmeta}.meta_value >= '{$today}' THEN 0 
                    ELSE 1 
                END ASC,
                CASE 
                    WHEN {$wpdb->postmeta}.meta_value >= '{$today}' THEN {$wpdb->postmeta}.meta_value 
                    ELSE NULL 
                END ASC,
                CASE 
                    WHEN {$wpdb->postmeta}.meta_value < '{$today}' THEN {$wpdb->postmeta}.meta_value 
                    ELSE NULL 
                END DESC,
                {$wpdb->posts}.post_date DESC
            ";
        }
    }
    
    // Remove filter to prevent infinite loop
    remove_filter('posts_orderby', 'ricelipka_events_custom_orderby', 10);
    
    return $orderby;
}

/**
 * Add custom query vars for filtering
 */
function ricelipka_add_query_vars($vars) {
    $vars[] = 'event_filter';
    $vars[] = 'date_range';
    $vars[] = 'subcategory_filter';
    return $vars;
}
add_filter('query_vars', 'ricelipka_add_query_vars');

/**
 * Handle AJAX filtering for chronological content
 */
function ricelipka_ajax_filter_content() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'ricelipka_nonce')) {
        wp_die('Security check failed');
    }
    
    $category = sanitize_text_field($_POST['category']);
    $filter_type = sanitize_text_field($_POST['filter_type']);
    $filter_value = sanitize_text_field($_POST['filter_value']);
    $paged = intval($_POST['paged']) ?: 1;
    
    // Build query args
    $args = array(
        'post_type' => 'post',
        'category_name' => $category,
        'posts_per_page' => 12,
        'paged' => $paged,
        'post_status' => 'publish'
    );
    
    // Apply category-specific ordering and filtering
    if ($category === 'news') {
        $args['meta_key'] = 'publication_date';
        $args['orderby'] = array(
            'meta_value' => 'DESC',
            'date' => 'DESC'
        );
        $args['meta_type'] = 'DATE';
        
        // Date range filtering for news
        if ($filter_type === 'date_range' && $filter_value) {
            $date_ranges = ricelipka_get_date_ranges();
            if (isset($date_ranges[$filter_value])) {
                $range = $date_ranges[$filter_value];
                $args['meta_query'] = array(
                    array(
                        'key' => 'publication_date',
                        'value' => array($range['start'], $range['end']),
                        'compare' => 'BETWEEN',
                        'type' => 'DATE'
                    )
                );
            }
        }
        
        // Subcategory filtering for news
        if ($filter_type === 'subcategory' && $filter_value !== 'all') {
            $args['meta_query'] = array(
                array(
                    'key' => 'subcategory',
                    'value' => $filter_value,
                    'compare' => '='
                )
            );
        }
        
    } elseif ($category === 'events') {
        $today = date('Y-m-d');
        
        // Event status filtering
        if ($filter_type === 'event_status') {
            if ($filter_value === 'upcoming') {
                $args['meta_query'] = array(
                    array(
                        'key' => 'event_date',
                        'value' => $today,
                        'compare' => '>=',
                        'type' => 'DATE'
                    )
                );
                $args['meta_key'] = 'event_date';
                $args['orderby'] = 'meta_value';
                $args['order'] = 'ASC';
                
            } elseif ($filter_value === 'past') {
                $args['meta_query'] = array(
                    array(
                        'key' => 'event_date',
                        'value' => $today,
                        'compare' => '<',
                        'type' => 'DATE'
                    )
                );
                $args['meta_key'] = 'event_date';
                $args['orderby'] = 'meta_value';
                $args['order'] = 'DESC';
            }
        }
        
        // Event type filtering
        if ($filter_type === 'event_type' && $filter_value !== 'all') {
            $args['meta_query'] = array(
                array(
                    'key' => 'event_subcategory',
                    'value' => $filter_value,
                    'compare' => '='
                )
            );
        }
        
        $args['meta_type'] = 'DATE';
    }
    
    // Execute query
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Load appropriate template part
            if ($category === 'news') {
                get_template_part('template-parts/content', 'news-card');
            } elseif ($category === 'events') {
                get_template_part('template-parts/content', 'event-card');
            }
        }
        
        // Pagination
        if ($query->max_num_pages > 1) {
            echo '<div class="ajax-pagination" data-max-pages="' . $query->max_num_pages . '">';
            echo '<button class="load-more-btn" data-page="' . ($paged + 1) . '">';
            _e('Load More', 'ricelipka-theme');
            echo '</button>';
            echo '</div>';
        }
        
    } else {
        echo '<div class="no-results">';
        echo '<p>' . __('No content found for the selected filters.', 'ricelipka-theme') . '</p>';
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    $content = ob_get_clean();
    
    wp_send_json_success(array(
        'content' => $content,
        'found_posts' => $query->found_posts,
        'max_pages' => $query->max_num_pages
    ));
}
add_action('wp_ajax_ricelipka_filter_content', 'ricelipka_ajax_filter_content');
add_action('wp_ajax_nopriv_ricelipka_filter_content', 'ricelipka_ajax_filter_content');

/**
 * Get predefined date ranges for filtering
 */
function ricelipka_get_date_ranges() {
    $current_year = date('Y');
    $current_month = date('m');
    
    return array(
        'this_month' => array(
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t'),
            'label' => __('This Month', 'ricelipka-theme')
        ),
        'last_month' => array(
            'start' => date('Y-m-01', strtotime('-1 month')),
            'end' => date('Y-m-t', strtotime('-1 month')),
            'label' => __('Last Month', 'ricelipka-theme')
        ),
        'this_year' => array(
            'start' => $current_year . '-01-01',
            'end' => $current_year . '-12-31',
            'label' => __('This Year', 'ricelipka-theme')
        ),
        'last_year' => array(
            'start' => ($current_year - 1) . '-01-01',
            'end' => ($current_year - 1) . '-12-31',
            'label' => __('Last Year', 'ricelipka-theme')
        ),
        'last_6_months' => array(
            'start' => date('Y-m-01', strtotime('-6 months')),
            'end' => date('Y-m-t'),
            'label' => __('Last 6 Months', 'ricelipka-theme')
        )
    );
}

/**
 * Archive page functionality for chronological content
 */
function ricelipka_create_archive_pages() {
    // Add rewrite rules for archive pages
    add_rewrite_rule(
        '^news/archive/?$',
        'index.php?category_name=news&archive_view=1',
        'top'
    );
    
    add_rewrite_rule(
        '^events/archive/?$',
        'index.php?category_name=events&archive_view=1',
        'top'
    );
    
    add_rewrite_rule(
        '^news/([0-9]{4})/?$',
        'index.php?category_name=news&archive_year=$matches[1]',
        'top'
    );
    
    add_rewrite_rule(
        '^events/([0-9]{4})/?$',
        'index.php?category_name=events&archive_year=$matches[1]',
        'top'
    );
    
    add_rewrite_rule(
        '^news/([0-9]{4})/([0-9]{2})/?$',
        'index.php?category_name=news&archive_year=$matches[1]&archive_month=$matches[2]',
        'top'
    );
    
    add_rewrite_rule(
        '^events/([0-9]{4})/([0-9]{2})/?$',
        'index.php?category_name=events&archive_year=$matches[1]&archive_month=$matches[2]',
        'top'
    );
}
add_action('init', 'ricelipka_create_archive_pages');

/**
 * Add archive query vars
 */
function ricelipka_add_archive_query_vars($vars) {
    $vars[] = 'archive_view';
    $vars[] = 'archive_year';
    $vars[] = 'archive_month';
    return $vars;
}
add_filter('query_vars', 'ricelipka_add_archive_query_vars');

/**
 * Modify query for archive pages
 */
function ricelipka_modify_archive_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        $archive_view = get_query_var('archive_view');
        $archive_year = get_query_var('archive_year');
        $archive_month = get_query_var('archive_month');
        
        if ($archive_view || $archive_year) {
            $category = get_query_var('category_name');
            
            if (in_array($category, ['news', 'events'])) {
                $query->set('posts_per_page', 24); // More posts for archive view
                
                // Date filtering for archives
                if ($archive_year) {
                    $date_query = array(
                        'year' => intval($archive_year)
                    );
                    
                    if ($archive_month) {
                        $date_query['month'] = intval($archive_month);
                    }
                    
                    $query->set('date_query', array($date_query));
                }
                
                // Apply chronological ordering
                if ($category === 'news') {
                    $query->set('meta_key', 'publication_date');
                    $query->set('orderby', array(
                        'meta_value' => 'DESC',
                        'date' => 'DESC'
                    ));
                    $query->set('meta_type', 'DATE');
                    
                } elseif ($category === 'events') {
                    $query->set('meta_key', 'event_date');
                    $query->set('orderby', 'meta_value');
                    $query->set('order', 'DESC');
                    $query->set('meta_type', 'DATE');
                }
            }
        }
    }
}
add_action('pre_get_posts', 'ricelipka_modify_archive_query');

/**
 * Get archive years for a category
 */
function ricelipka_get_archive_years($category) {
    global $wpdb;
    
    $meta_key = ($category === 'news') ? 'publication_date' : 'event_date';
    
    $years = $wpdb->get_col($wpdb->prepare("
        SELECT DISTINCT YEAR(pm.meta_value) as year
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE pm.meta_key = %s
        AND p.post_status = 'publish'
        AND p.post_type = 'post'
        AND t.slug = %s
        AND pm.meta_value != ''
        ORDER BY year DESC
    ", $meta_key, $category));
    
    return array_filter($years);
}

/**
 * Get archive months for a category and year
 */
function ricelipka_get_archive_months($category, $year) {
    global $wpdb;
    
    $meta_key = ($category === 'news') ? 'publication_date' : 'event_date';
    
    $months = $wpdb->get_results($wpdb->prepare("
        SELECT DISTINCT MONTH(pm.meta_value) as month, MONTHNAME(pm.meta_value) as month_name
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE pm.meta_key = %s
        AND YEAR(pm.meta_value) = %d
        AND p.post_status = 'publish'
        AND p.post_type = 'post'
        AND t.slug = %s
        AND pm.meta_value != ''
        ORDER BY month ASC
    ", $meta_key, $year, $category));
    
    return $months;
}