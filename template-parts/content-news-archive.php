<?php
/**
 * Template part for displaying posts in news archive view
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('news-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="news-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="news-content">
        <h2 class="news-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        
        <div class="news-meta">
            <span class="news-date"><?php echo get_the_date(); ?></span>
        </div>
        
        <div class="news-excerpt">
            <?php the_excerpt(); ?>
        </div>
        
        <div class="news-link">
            <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
        </div>
    </div>
</article>