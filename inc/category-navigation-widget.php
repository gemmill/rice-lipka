<?php
/**
 * Category Navigation Widget
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Category Navigation Widget Class
 */
class RiceLipka_Category_Navigation_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'ricelipka_category_navigation',
            __('Category Navigation', 'ricelipka-theme'),
            array(
                'description' => __('Display hierarchical category navigation with subcategories', 'ricelipka-theme'),
                'classname' => 'ricelipka-category-navigation-widget'
            )
        );
    }
    
    /**
     * Widget output
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Categories', 'ricelipka-theme');
        $show_counts = !empty($instance['show_counts']) ? $instance['show_counts'] : false;
        $show_subcategories = !empty($instance['show_subcategories']) ? $instance['show_subcategories'] : true;
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        
        $this->display_category_navigation($show_counts, $show_subcategories);
        
        echo $args['after_widget'];
    }
    
    /**
     * Display category navigation
     */
    private function display_category_navigation($show_counts = false, $show_subcategories = true) {
        $hierarchy = ricelipka_get_category_hierarchy();
        
        if (empty($hierarchy)) {
            return;
        }
        
        echo '<nav class="widget-category-navigation">';
        echo '<ul class="widget-primary-categories">';
        
        foreach ($hierarchy as $slug => $data) {
            $category_url = get_category_link($data['category']->term_id);
            $count_display = $show_counts ? sprintf(' <span class="count">(%d)</span>', $data['post_count']) : '';
            
            echo sprintf(
                '<li class="widget-primary-category category-%s">',
                esc_attr($slug)
            );
            
            echo sprintf(
                '<a href="%s" class="primary-category-link">%s%s</a>',
                esc_url($category_url),
                esc_html($data['category']->name),
                $count_display
            );
            
            // Display subcategories if enabled and available
            if ($show_subcategories && !empty($data['subcategories'])) {
                echo '<ul class="widget-subcategories">';
                
                foreach ($data['subcategories'] as $subcategory) {
                    $sub_url = get_category_link($subcategory->term_id);
                    $sub_count = $show_counts ? sprintf(' <span class="count">(%d)</span>', $subcategory->count) : '';
                    
                    echo sprintf(
                        '<li class="widget-subcategory"><a href="%s">%s%s</a></li>',
                        esc_url($sub_url),
                        esc_html($subcategory->name),
                        $sub_count
                    );
                }
                
                echo '</ul>';
            }
            
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
    
    /**
     * Widget form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Categories', 'ricelipka-theme');
        $show_counts = !empty($instance['show_counts']) ? $instance['show_counts'] : false;
        $show_subcategories = !empty($instance['show_subcategories']) ? $instance['show_subcategories'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php _e('Title:', 'ricelipka-theme'); ?>
            </label>
            <input class="widefat" 
                   id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <input class="checkbox" 
                   type="checkbox" 
                   <?php checked($show_counts); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_counts')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_counts')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_counts')); ?>">
                <?php _e('Show post counts', 'ricelipka-theme'); ?>
            </label>
        </p>
        
        <p>
            <input class="checkbox" 
                   type="checkbox" 
                   <?php checked($show_subcategories); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_subcategories')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_subcategories')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_subcategories')); ?>">
                <?php _e('Show subcategories', 'ricelipka-theme'); ?>
            </label>
        </p>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_counts'] = (!empty($new_instance['show_counts'])) ? 1 : 0;
        $instance['show_subcategories'] = (!empty($new_instance['show_subcategories'])) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Register the widget
 */
function ricelipka_register_category_navigation_widget() {
    register_widget('RiceLipka_Category_Navigation_Widget');
}
add_action('widgets_init', 'ricelipka_register_category_navigation_widget');

/**
 * Shortcode for category navigation
 */
function ricelipka_category_navigation_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_counts' => 'false',
        'show_subcategories' => 'true',
        'current_category' => ''
    ), $atts, 'category_navigation');
    
    $show_counts = ($atts['show_counts'] === 'true');
    $show_subcategories = ($atts['show_subcategories'] === 'true');
    $current_category = sanitize_text_field($atts['current_category']);
    
    ob_start();
    
    $hierarchy = ricelipka_get_category_hierarchy();
    
    if (!empty($hierarchy)) {
        echo '<nav class="shortcode-category-navigation">';
        echo '<ul class="shortcode-primary-categories">';
        
        foreach ($hierarchy as $slug => $data) {
            $is_current = ($current_category === $slug);
            $category_url = get_category_link($data['category']->term_id);
            $count_display = $show_counts ? sprintf(' <span class="count">(%d)</span>', $data['post_count']) : '';
            
            echo sprintf(
                '<li class="shortcode-primary-category category-%s %s">',
                esc_attr($slug),
                $is_current ? 'current' : ''
            );
            
            echo sprintf(
                '<a href="%s" class="primary-category-link">%s%s</a>',
                esc_url($category_url),
                esc_html($data['category']->name),
                $count_display
            );
            
            // Display subcategories if enabled and available
            if ($show_subcategories && !empty($data['subcategories'])) {
                echo '<ul class="shortcode-subcategories">';
                
                foreach ($data['subcategories'] as $subcategory) {
                    $sub_url = get_category_link($subcategory->term_id);
                    $sub_count = $show_counts ? sprintf(' <span class="count">(%d)</span>', $subcategory->count) : '';
                    
                    echo sprintf(
                        '<li class="shortcode-subcategory"><a href="%s">%s%s</a></li>',
                        esc_url($sub_url),
                        esc_html($subcategory->name),
                        $sub_count
                    );
                }
                
                echo '</ul>';
            }
            
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
    
    return ob_get_clean();
}
add_shortcode('category_navigation', 'ricelipka_category_navigation_shortcode');

/**
 * Template function for category navigation
 */
function ricelipka_display_category_navigation($args = array()) {
    $defaults = array(
        'show_counts' => false,
        'show_subcategories' => true,
        'current_category' => '',
        'echo' => true
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $output = ricelipka_generate_category_navigation($args['current_category']);
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}