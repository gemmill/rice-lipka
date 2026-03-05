<?php
/**
 * News Item Component
 * 
 * Reusable component for displaying a news item in listings
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Get news data
$news_id = get_the_ID();
$news_title = get_the_title();
$news_permalink = get_the_permalink();
$news_date = get_the_date('M j, Y');
$news_content = get_the_content();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('news-item'); ?>>
    
    <a href="<?php echo esc_url($news_permalink); ?>" class="news-link">
        <div class="news-image-wrapper">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium', array(
                    'alt' => esc_attr($news_title),
                    'class' => 'news-image'
                )); ?>
            <?php else : ?>
                <div class="news-placeholder">
                </div>
            <?php endif; ?>
        </div>
    </a>
    
    <div class="news-content">
        <div class="news-date">
            <time datetime="<?php echo get_the_date('c'); ?>">
                <?php echo esc_html($news_date); ?>
            </time>
        </div>
        
        <h2 class="news-title">
            <a href="<?php echo esc_url($news_permalink); ?>"><?php echo esc_html($news_title); ?></a>
        </h2>
        
        <?php if ($news_content) : ?>
            <div class="news-excerpt">
                <?php echo wp_trim_words($news_content, 25, '...'); ?>
            </div>
        <?php endif; ?>
    </div>
    
</article>