<?php
/**
 * Basic page template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main page-template">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Parent Page Content -->
        <article id="post-<?php the_ID(); ?>" <?php post_class('parent-page-content'); ?>>
            
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="entry-featured-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
            
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
            
        </article>
        
        <?php
        // Get child pages
        $child_pages = ricelipka_get_page_child_pages(get_the_ID());
        
        if ($child_pages && !empty($child_pages)) : ?>
            
            <section class="child-pages-full-content">
                
                <?php foreach ($child_pages as $child_page) : ?>
                    
                    <article id="child-post-<?php echo $child_page->ID; ?>" class="child-page-full">
                        
                        <header class="child-entry-header">
                            <h2 class="child-entry-title">
                                <?php echo get_the_title($child_page->ID); ?>
                            </h2>
                        </header>
                        
                        <?php if (has_post_thumbnail($child_page->ID)) : ?>
                            <div class="child-featured-image">
                                <?php echo get_the_post_thumbnail($child_page->ID, 'large'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="child-entry-content">
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
        
    <?php endwhile; ?>
    
</main>

<?php
get_footer();