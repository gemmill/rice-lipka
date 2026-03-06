<?php
/**
 * Site Menu Component
 * 
 * Reusable component for displaying site title and navigation menu
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */
?>

<div class="column menu">
    <?php 
    $menu_items = ricelipka_create_custom_menu();
    $current_url = home_url($_SERVER['REQUEST_URI']);
    
    // Remove trailing slash for consistent comparison
    $current_url = rtrim($current_url, '/');
    
    echo '<ul class="primary-menu">';
    
    // Add homepage link with site title as first menu item
    $home_url = rtrim(home_url('/'), '/');
    $home_active_class = ($current_url === $home_url) ? ' current-menu-item' : '';
    
    echo '<li class="menu-item home-link' . $home_active_class . '">';
    echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a>';
    echo '</li>';
    
    // Add the rest of the menu items
    foreach ($menu_items as $key => $item) {
        $active_class = '';
        $ancestor_class = '';
        $has_submenu = isset($item['submenu']) && !empty($item['submenu']);
        
        // Clean item URL for comparison
        $item_url = rtrim($item['url'], '/');
        
        // Check if current page matches this menu item exactly
        if ($current_url === $item_url) {
            $active_class = ' current-menu-item';
        }
        // Check if current page is under this menu section (ancestor)
        elseif ($has_submenu) {
            // Check if current URL starts with parent URL
            if (strpos($current_url, $item_url) === 0) {
                $ancestor_class = ' current-menu-ancestor';
            } else {
                // Check if current URL matches any submenu item
                foreach ($item['submenu'] as $sub_item) {
                    $sub_item_url = rtrim($sub_item['url'], '/');
                    if ($current_url === $sub_item_url || strpos($current_url, $sub_item_url . '/') === 0) {
                        $ancestor_class = ' current-menu-ancestor';
                        break;
                    }
                }
            }
        }
        
        echo '<li class="menu-item' . $active_class . $ancestor_class . ($has_submenu ? ' has-submenu' : '') . '">';
        echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>';
        
        if ($has_submenu) {
            echo '<ul class="submenu">';
            foreach ($item['submenu'] as $sub_key => $sub_item) {
                $sub_active_class = '';
                $sub_item_url = rtrim($sub_item['url'], '/');
                
                // Check for exact match or if current URL starts with submenu URL
                if ($current_url === $sub_item_url || strpos($current_url, $sub_item_url . '/') === 0) {
                    $sub_active_class = ' current-menu-item';
                }
                
                echo '<li class="submenu-item' . $sub_active_class . '">';
                echo '<a href="' . esc_url($sub_item['url']) . '">' . esc_html($sub_item['title']) . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo '</li>';
    }
    
    echo '</ul>';
    ?>
</div>