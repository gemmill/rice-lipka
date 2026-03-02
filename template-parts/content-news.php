<?php
/**
 * Template part for displaying news posts
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$news_fields = ricelipka_get_post_type_fields(get_the_ID());
$featured_image = $news_fields['featured_image'] ?: (has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium') : null);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('news-item post-item'); ?>>
    
    <?php if ($featured_image) : ?>
        <div class="post-thumbnail news-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php if (is_array($featured_image) && isset($featured_image['url'])) : ?>
                    <img src="<?php echo esc_url($featured_image['url']); ?>" 
                         alt="<?php echo esc_attr($featured_image['alt'] ?: get_the_title()); ?>" />
                <?php else : ?>
                    <?php the_post_thumbnail('medium'); ?>
                <?php endif; ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="post-content news-content">
        <header class="entry-header">
            <h2 class="entry-title news-entry-title">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>
            
            <div class="entry-meta news-meta">
                <?php
                $publication_date = $news_fields['publication_date'];
                $display_date = $publication_date ? date('F j, Y', strtotime($publication_date)) : get_the_date();
                ?>
                <time class="published news-date" datetime="<?php echo esc_attr($publication_date ?: get_the_date('c')); ?>">
                    <?php echo esc_html($display_date); ?>
                </time>
                
                <?php if ($news_fields['subcategory']) : ?>
                    <span class="news-subcategory subcategory-<?php echo esc_attr($news_fields['subcategory']); ?>">
                        <?php echo esc_html(ucfirst(str_replace('_', ' ', $news_fields['subcategory']))); ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>
        
        <div class="entry-summary news-summary">
            <?php
            $excerpt = $news_fields['excerpt'] ?: get_the_excerpt();
            echo wp_kses_post($excerpt);
            ?>
        </div>
        
        <footer class="entry-footer news-footer">
            <a href="<?php the_permalink(); ?>" class="read-more news-read-more">
                <?php _e('Read More', 'ricelipka-theme'); ?>
            </a>
        </footer>
    </div>
    
</article>