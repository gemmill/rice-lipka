<?php
/**
 * The template for displaying single posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                
                <header class="entry-header">
                    <?php
                    // Display category badge
                    $primary_category = ricelipka_get_post_primary_category();
                    if ($primary_category) : ?>
                        <span class="category-badge category-<?php echo esc_attr($primary_category); ?>">
                            <?php echo esc_html(ucfirst($primary_category)); ?>
                        </span>
                    <?php endif; ?>
                    
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <div class="entry-meta">
                        <time class="published" datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo get_the_date(); ?>
                        </time>
                        
                        <?php if (get_the_modified_date() !== get_the_date()) : ?>
                            <time class="updated" datetime="<?php echo get_the_modified_date('c'); ?>">
                                <?php printf(__('Updated: %s', 'ricelipka-theme'), get_the_modified_date()); ?>
                            </time>
                        <?php endif; ?>
                    </div>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <?php
                // Display category-specific fields before content
                $category_fields = ricelipka_get_category_fields();
                $primary_category = ricelipka_get_post_primary_category();
                
                if ($category_fields && $primary_category) :
                    get_template_part('template-parts/single', $primary_category, array('fields' => $category_fields));
                endif;
                ?>
                
                <div class="entry-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . __('Pages:', 'ricelipka-theme'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
                
                <footer class="entry-footer">
                    <?php
                    // Display categories and tags
                    $categories_list = get_the_category_list(', ');
                    if ($categories_list) {
                        printf('<span class="cat-links">' . __('Categories: %1$s', 'ricelipka-theme') . '</span>', $categories_list);
                    }
                    
                    $tags_list = get_the_tag_list('', ', ');
                    if ($tags_list) {
                        printf('<span class="tags-links">' . __('Tags: %1$s', 'ricelipka-theme') . '</span>', $tags_list);
                    }
                    ?>
                </footer>
                
            </article>
            
            <?php
            // Post navigation
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . __('Previous:', 'ricelipka-theme') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . __('Next:', 'ricelipka-theme') . '</span> <span class="nav-title">%title</span>',
            ));
            
            // Comments
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>
            
        <?php endwhile; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();