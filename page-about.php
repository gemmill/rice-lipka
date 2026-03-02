<?php
/**
 * Template Name: About Page
 * Template for About page
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main about-page">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('about-content'); ?>>
            
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
            
            <?php
            // Get ordered child pages
            $child_pages = ricelipka_get_about_child_pages(get_the_ID());
            
            if ($child_pages && !empty($child_pages)) : ?>
                
                <section class="about-child-pages">
                    <h2><?php _e('Learn More', 'ricelipka-theme'); ?></h2>
                    
                    <div class="child-pages-grid">
                        <?php foreach ($child_pages as $child_page) : ?>
                            <div class="child-page-item">
                                <h3 class="child-page-title">
                                    <a href="<?php echo get_permalink($child_page->ID); ?>">
                                        <?php echo get_the_title($child_page->ID); ?>
                                    </a>
                                </h3>
                                
                                <?php if (has_post_thumbnail($child_page->ID)) : ?>
                                    <div class="child-page-thumbnail">
                                        <a href="<?php echo get_permalink($child_page->ID); ?>">
                                            <?php echo get_the_post_thumbnail($child_page->ID, 'medium'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="child-page-excerpt">
                                    <?php 
                                    $excerpt = get_the_excerpt($child_page->ID);
                                    if ($excerpt) {
                                        echo '<p>' . $excerpt . '</p>';
                                    } else {
                                        // Generate excerpt from content if no manual excerpt
                                        $content = get_post_field('post_content', $child_page->ID);
                                        $excerpt = wp_trim_words($content, 20, '...');
                                        if ($excerpt) {
                                            echo '<p>' . $excerpt . '</p>';
                                        }
                                    }
                                    ?>
                                </div>
                                
                                <div class="child-page-link">
                                    <a href="<?php echo get_permalink($child_page->ID); ?>" class="read-more">
                                        <?php _e('Read More', 'ricelipka-theme'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                </section>
                
            <?php endif; ?>
            
        </article>
        
    <?php endwhile; ?>
    
</main>

<?php
get_sidebar();
get_footer();