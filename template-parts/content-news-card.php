<?php
/**
 * Template part for displaying news content cards
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

$news_fields = ricelipka_get_post_type_fields(get_the_ID());
$featured_image = $news_fields['featured_image'] ?: (has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium') : null);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('news-item post-item post-card'); ?> data-subcategory="<?php echo esc_attr($news_fields['subcategory'] ?: 'all'); ?>">
    
    <?php if ($featured_image) : ?>
        <div class="post-thumbnail news-thumbnail post-card-image">
            <a href="<?php the_permalink(); ?>">
                <?php if (is_array($featured_image) && isset($featured_image['url'])) : ?>
                    <img src="<?php echo esc_url($featured_image['url']); ?>" 
                         alt="<?php echo esc_attr($featured_image['alt'] ?: get_the_title()); ?>" 
                         loading="lazy" />
                <?php else : ?>
                    <?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
                <?php endif; ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="post-content news-content post-card-content">
        <div class="post-card-meta">
            <?php
            $publication_date = $news_fields['publication_date'];
            $display_date = $publication_date ? date('F j, Y', strtotime($publication_date)) : get_the_date();
            $sort_date = $publication_date ?: get_the_date('Y-m-d');
            ?>
            <time class="published news-date post-card-date" 
                  datetime="<?php echo esc_attr($publication_date ?: get_the_date('c')); ?>"
                  data-sort-date="<?php echo esc_attr($sort_date); ?>">
                📅 <?php echo esc_html($display_date); ?>
            </time>
            
            <?php if ($news_fields['subcategory']) : ?>
                <span class="news-subcategory post-card-category subcategory-<?php echo esc_attr($news_fields['subcategory']); ?>">
                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $news_fields['subcategory']))); ?>
                </span>
            <?php endif; ?>
        </div>
        
        <header class="entry-header">
            <h2 class="entry-title news-entry-title post-card-title">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>
        </header>
        
        <div class="entry-summary news-summary post-card-excerpt">
            <?php
            // Use ACF excerpt if available, otherwise WordPress excerpt
            $excerpt = $news_fields['excerpt'] ?: get_the_excerpt();
            echo wp_kses_post($excerpt);
            ?>
        </div>
        
        <footer class="entry-footer news-footer post-card-footer">
            <a href="<?php the_permalink(); ?>" class="read-more news-read-more read-more-btn">
                <?php _e('Read More', 'ricelipka-theme'); ?> →
            </a>
            
            <div class="post-card-tags">
                <?php
                $tags = get_the_tags();
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<a href="' . get_tag_link($tag->term_id) . '" class="post-tag">' . esc_html($tag->name) . '</a>';
                    }
                }
                ?>
            </div>
        </footer>
    </div>
    
</article>