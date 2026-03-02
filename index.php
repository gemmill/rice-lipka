<?php
/**
 * The main template file
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header">
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>

            <div class="posts-container">
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <header class="entry-header">
                                <?php
                                // Display post type badge for custom post types
                                $post_type = get_post_type();
                                if (in_array($post_type, array('news', 'projects', 'awards', 'people'))) : ?>
                                    <span class="category-badge category-<?php echo esc_attr($post_type); ?>">
                                        <?php echo esc_html(ucfirst($post_type)); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <div class="entry-meta">
                                    <time class="published" datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                </div>
                            </header>
                            
                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                        
                    </article>
                    
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'prev_text' => __('Previous', 'ricelipka-theme'),
                'next_text' => __('Next', 'ricelipka-theme'),
            ));
            ?>
            
        <?php else : ?>
            
            <section class="no-results">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('Nothing Found', 'ricelipka-theme'); ?></h1>
                </header>
                
                <div class="page-content">
                    <?php if (is_home() && current_user_can('publish_posts')) : ?>
                        <p><?php printf(__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'ricelipka-theme'), esc_url(admin_url('post-new.php'))); ?></p>
                    <?php else : ?>
                        <p><?php _e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'ricelipka-theme'); ?></p>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                </div>
            </section>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();