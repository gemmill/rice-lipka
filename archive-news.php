<?php
/**
 * News Archive Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>
    
    <div class="grid">
        <div id="news-masonry" class="masonry">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="masonry-item">
                        <?php get_template_part('template-parts/item-news'); ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        
        <div id="news-loading" class="news-loading" style="display: none;">
            <p>Loading more news...</p>
        </div>
        
        <?php if (!have_posts()) : ?>
            <div class="no-news">
                <h2><?php _e('No news found', 'ricelipka-theme'); ?></h2>
                <p><?php _e('No news articles have been published yet.', 'ricelipka-theme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Pass data to JavaScript
window.endlessScrollData = {
    ajaxUrl: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
    currentPage: <?php echo intval(get_query_var('paged') ? get_query_var('paged') : 1); ?>,
    maxPages: <?php echo intval($wp_query->max_num_pages); ?>,
    nonce: '<?php echo esc_js(wp_create_nonce('ricelipka_nonce')); ?>'
};
console.log('News page data:', window.endlessScrollData);
</script>

<?php get_footer(); ?>