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
    
    <section class="page-child-pages-full">
        
        <?php foreach ($child_pages as $child_page) : ?>
            <article class="child-page-item-full">
                <header class="child-page-header">
                    <h2 class="child-page-title">
                        <?php echo get_the_title($child_page->ID); ?>
                    </h2>
                </header>
                
                <?php if (has_post_thumbnail($child_page->ID)) : ?>
                    <div class="child-page-thumbnail">
                        <?php echo get_the_post_thumbnail($child_page->ID, 'large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="child-page-content">
                    <?php 
                    // Get the full content of the child page
                    $child_content = get_post_field('post_content', $child_page->ID);
                    
                    // Apply WordPress content filters (shortcodes, etc.)
                    $child_content = apply_filters('the_content', $child_content);
                    
                    echo $child_content;
                    ?>
                </div>
                
            </article>
        <?php endforeach; ?>
        
    </section>
    
<?php endif; ?>