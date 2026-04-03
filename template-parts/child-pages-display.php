<?php
/**
 * Template part for displaying child pages with full content
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$page_id = $args['page_id'] ?? get_the_ID();
$child_pages = ricelipka_get_page_child_pages($page_id);

if ($child_pages && !empty($child_pages)) : ?>
    
    <div id="about-masonry" class="masonry">
        <?php foreach ($child_pages as $child_page) : ?>
           <div class="masonry-item">
               
                 
                        <h2 class="child-page-title">
                            <?php echo get_the_title($child_page->ID); ?>
                        </h2>
           
                    <?php if (has_post_thumbnail($child_page->ID)) : ?>
                        <div class="child-page-thumbnail">
                            <?php echo get_the_post_thumbnail($child_page->ID, 'large'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="child-page-content">
                        <?php 
                        // Get the full content of the child page
                        $child_content = get_post_field('post_content', $child_page->ID);
                        
                        // Apply WordPress content filters (shortcodes, etc.) with error handling
                        try {
                            $child_content = apply_filters('the_content', $child_content);
                            
                            // Use WordPress's built-in content sanitization
                            $child_content = wp_kses_post($child_content);
                            
                            // Additional cleanup for content that might break JavaScript parsing
                            // Remove any unclosed HTML tags at the end of content
                            $child_content = preg_replace('/<[^>]*$/', '', $child_content);
                            
                            // Remove any potential script tags or problematic content
                            $child_content = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $child_content);
                            $child_content = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $child_content);
                            
                            // Ensure content doesn't break HTML structure
                            $child_content = balanceTags($child_content);
                            
                            echo $child_content;
                        } catch (Exception $e) {
                            echo '<p>Error loading content for: ' . esc_html(get_the_title($child_page->ID)) . '</p>';
                            // Don't output the raw content as it might contain the problematic code
                        }
                        ?>
                    </div>
              
           </div>
        <?php endforeach; ?>
    </div>
    
<?php else : ?>
    <div id="about-masonry" class="masonry">
        <div class="masonry-item">
            <div class="child-page-item">
                <h2 class="child-page-title">No Child Pages</h2>
                <div class="child-page-content">
                    <p>No child pages found for this page.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>