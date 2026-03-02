<?php
/**
 * Subcategory Management System
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize subcategory system
 */
function ricelipka_init_subcategory_system() {
    add_action('init', 'ricelipka_create_subcategories');
    add_action('admin_init', 'ricelipka_enforce_single_primary_category');
    add_action('save_post', 'ricelipka_validate_category_assignment', 10, 2);
}
add_action('after_setup_theme', 'ricelipka_init_subcategory_system');

/**
 * Define subcategory structure for each primary category
 */
function ricelipka_get_subcategory_structure() {
    return array(
        'news' => array(
            'project_updates' => 'Project Updates',
            'event_announcements' => 'Event Announcements', 
            'award_notifications' => 'Award Notifications',
            'firm_news' => 'Firm News',
            'press_releases' => 'Press Releases',
            'community_involvement' => 'Community Involvement'
        ),
        'projects' => array(
            'civic_architecture' => 'Civic Architecture',
            'cultural_projects' => 'Cultural Projects',
            'educational_buildings' => 'Educational Buildings',
            'public_works' => 'Public Works',
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'mixed_use' => 'Mixed Use'
        ),
        'events' => array(
            'conferences' => 'Conferences',
            'workshops' => 'Workshops',
            'exhibitions' => 'Exhibitions',
            'lectures' => 'Lectures',
            'community_events' => 'Community Events',
            'awards_ceremonies' => 'Awards Ceremonies',
            'networking' => 'Networking Events'
        ),
        'awards' => array(
            'design_excellence' => 'Design Excellence',
            'sustainability' => 'Sustainability Awards',
            'innovation' => 'Innovation Awards',
            'community_impact' => 'Community Impact',
            'professional_recognition' => 'Professional Recognition',
            'project_awards' => 'Project Awards'
        )
    );
}

/**
 * Create subcategories for each primary category
 */
function ricelipka_create_subcategories() {
    $subcategory_structure = ricelipka_get_subcategory_structure();
    
    foreach ($subcategory_structure as $primary_slug => $subcategories) {
        // Get or create primary category
        $primary_category = get_term_by('slug', $primary_slug, 'category');
        
        if (!$primary_category) {
            $primary_name = ucfirst($primary_slug);
            $primary_result = wp_insert_term($primary_name, 'category', array('slug' => $primary_slug));
            
            if (!is_wp_error($primary_result)) {
                $primary_category = get_term($primary_result['term_id'], 'category');
            }
        }
        
        if ($primary_category) {
            // Create subcategories
            foreach ($subcategories as $sub_slug => $sub_name) {
                $full_slug = $primary_slug . '_' . $sub_slug;
                
                if (!term_exists($full_slug, 'category')) {
                    wp_insert_term($sub_name, 'category', array(
                        'slug' => $full_slug,
                        'parent' => $primary_category->term_id,
                        'description' => sprintf('Subcategory of %s', $primary_category->name)
                    ));
                }
            }
        }
    }
}

/**
 * Get subcategories for a primary category
 */
function ricelipka_get_subcategories($primary_category_slug) {
    $primary_category = get_term_by('slug', $primary_category_slug, 'category');
    
    if (!$primary_category) {
        return array();
    }
    
    $subcategories = get_terms(array(
        'taxonomy' => 'category',
        'parent' => $primary_category->term_id,
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC'
    ));
    
    return is_wp_error($subcategories) ? array() : $subcategories;
}

/**
 * Get primary category from post categories
 */
function ricelipka_get_post_primary_category_with_subcategories($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    $primary_cats = array('news', 'projects', 'events', 'awards');
    $result = array(
        'primary' => null,
        'subcategories' => array()
    );
    
    foreach ($categories as $category) {
        // Check if it's a primary category
        if (in_array($category->slug, $primary_cats)) {
            $result['primary'] = $category->slug;
        }
        // Check if it's a subcategory (has parent)
        elseif ($category->parent > 0) {
            $parent = get_term($category->parent, 'category');
            if ($parent && in_array($parent->slug, $primary_cats)) {
                $result['subcategories'][] = $category;
            }
        }
    }
    
    // Default to news if no primary category found
    if (!$result['primary']) {
        $result['primary'] = 'news';
    }
    
    return $result;
}

/**
 * Enforce single primary category rule
 */
function ricelipka_enforce_single_primary_category() {
    add_action('admin_footer', 'ricelipka_category_admin_script');
}

/**
 * Add JavaScript to enforce single primary category selection
 */
function ricelipka_category_admin_script() {
    global $pagenow;
    
    if ($pagenow === 'post.php' || $pagenow === 'post-new.php') {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var primaryCategories = ['news', 'projects', 'events', 'awards'];
            var categoryCheckboxes = $('#categorychecklist input[type="checkbox"]');
            
            function enforceSinglePrimary() {
                var checkedPrimary = [];
                
                categoryCheckboxes.each(function() {
                    var label = $(this).next('label').text().toLowerCase();
                    var isChecked = $(this).is(':checked');
                    
                    if (isChecked && primaryCategories.includes(label)) {
                        checkedPrimary.push($(this));
                    }
                });
                
                // If more than one primary category is selected, uncheck all but the last one
                if (checkedPrimary.length > 1) {
                    for (var i = 0; i < checkedPrimary.length - 1; i++) {
                        checkedPrimary[i].prop('checked', false);
                    }
                    
                    alert('Only one primary category (News, Projects, Events, or Awards) can be selected at a time.');
                }
            }
            
            categoryCheckboxes.on('change', enforceSinglePrimary);
        });
        </script>
        <?php
    }
}

/**
 * Validate category assignment on post save
 */
function ricelipka_validate_category_assignment($post_id, $post) {
    // Skip for autosaves and revisions
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }
    
    // Only process posts
    if ($post->post_type !== 'post') {
        return;
    }
    
    $categories = wp_get_post_categories($post_id);
    $primary_cats = array('news', 'projects', 'events', 'awards');
    $primary_count = 0;
    $primary_category_id = null;
    
    foreach ($categories as $cat_id) {
        $category = get_term($cat_id, 'category');
        if ($category && in_array($category->slug, $primary_cats)) {
            $primary_count++;
            $primary_category_id = $cat_id;
        }
    }
    
    // If no primary category, assign to News
    if ($primary_count === 0) {
        $news_category = get_term_by('slug', 'news', 'category');
        if ($news_category) {
            $categories[] = $news_category->term_id;
            wp_set_post_categories($post_id, $categories);
        }
    }
    // If multiple primary categories, keep only the first one found
    elseif ($primary_count > 1) {
        $filtered_categories = array();
        $primary_found = false;
        
        foreach ($categories as $cat_id) {
            $category = get_term($cat_id, 'category');
            if ($category && in_array($category->slug, $primary_cats)) {
                if (!$primary_found) {
                    $filtered_categories[] = $cat_id;
                    $primary_found = true;
                }
            } else {
                $filtered_categories[] = $cat_id;
            }
        }
        
        wp_set_post_categories($post_id, $filtered_categories);
    }
}

/**
 * Get category hierarchy for navigation
 */
function ricelipka_get_category_hierarchy() {
    $primary_cats = array('news', 'projects', 'events', 'awards');
    $hierarchy = array();
    
    foreach ($primary_cats as $primary_slug) {
        $primary_category = get_term_by('slug', $primary_slug, 'category');
        
        if ($primary_category) {
            $subcategories = ricelipka_get_subcategories($primary_slug);
            
            $hierarchy[$primary_slug] = array(
                'category' => $primary_category,
                'subcategories' => $subcategories,
                'post_count' => $primary_category->count
            );
        }
    }
    
    return $hierarchy;
}

/**
 * Generate category navigation menu
 */
function ricelipka_generate_category_navigation($current_category = null) {
    $hierarchy = ricelipka_get_category_hierarchy();
    $output = '<nav class="category-navigation">';
    $output .= '<ul class="primary-categories">';
    
    foreach ($hierarchy as $slug => $data) {
        $is_current = ($current_category === $slug);
        $category_url = get_category_link($data['category']->term_id);
        
        $output .= sprintf(
            '<li class="primary-category %s"><a href="%s">%s <span class="count">(%d)</span></a>',
            $is_current ? 'current' : '',
            esc_url($category_url),
            esc_html($data['category']->name),
            $data['post_count']
        );
        
        // Add subcategories if they exist
        if (!empty($data['subcategories'])) {
            $output .= '<ul class="subcategories">';
            
            foreach ($data['subcategories'] as $subcategory) {
                $sub_url = get_category_link($subcategory->term_id);
                $output .= sprintf(
                    '<li class="subcategory"><a href="%s">%s <span class="count">(%d)</span></a></li>',
                    esc_url($sub_url),
                    esc_html($subcategory->name),
                    $subcategory->count
                );
            }
            
            $output .= '</ul>';
        }
        
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</nav>';
    
    return $output;
}

/**
 * Add category filtering functionality
 */
function ricelipka_add_category_filters() {
    add_action('wp_ajax_filter_posts_by_category', 'ricelipka_ajax_filter_posts');
    add_action('wp_ajax_nopriv_filter_posts_by_category', 'ricelipka_ajax_filter_posts');
}
add_action('init', 'ricelipka_add_category_filters');

/**
 * AJAX handler for category filtering
 */
function ricelipka_ajax_filter_posts() {
    check_ajax_referer('ricelipka_nonce', 'nonce');
    
    $category_slug = sanitize_text_field($_POST['category']);
    $subcategory_slug = sanitize_text_field($_POST['subcategory']);
    $paged = intval($_POST['paged']) ?: 1;
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => get_option('posts_per_page'),
        'paged' => $paged,
        'post_status' => 'publish'
    );
    
    // Set category query
    if ($subcategory_slug && $subcategory_slug !== 'all') {
        $args['category_name'] = $subcategory_slug;
    } elseif ($category_slug && $category_slug !== 'all') {
        $args['category_name'] = $category_slug;
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Load appropriate template part based on category
            $primary_category = ricelipka_get_post_primary_category(get_the_ID());
            get_template_part('template-parts/content', $primary_category);
        }
        
        wp_reset_postdata();
    } else {
        echo '<p class="no-posts-found">' . __('No posts found for this category.', 'ricelipka-theme') . '</p>';
    }
    
    $content = ob_get_clean();
    
    wp_send_json_success(array(
        'content' => $content,
        'max_pages' => $query->max_num_pages,
        'current_page' => $paged
    ));
}