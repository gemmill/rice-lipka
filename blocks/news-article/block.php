<?php
/**
 * News Article Block Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Get block fields
$publication_date = get_field('publication_date');
$excerpt = get_field('excerpt');
$featured_image = get_field('featured_image');

// Use post title
$news_title = get_the_title();

// Use post excerpt if no custom excerpt
if (!$excerpt) {
    $excerpt = get_the_excerpt();
}

// Get post content for body
$post_content = get_the_content();

// Check if we're in preview mode
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];

// Generate unique ID for this block instance
$block_id = 'news-article-' . uniqid();

// Block wrapper attributes
$block_wrapper_attributes = get_block_wrapper_attributes(array(
    'class' => 'news-article-block',
    'id' => $block_id,
    'data-block-type' => 'news-article'
));

// Enqueue block-specific assets
wp_enqueue_style(
    'news-article-block-style',
    get_template_directory_uri() . '/blocks/news-article/style.css',
    array(),
    wp_get_theme()->get('Version')
);

wp_enqueue_script(
    'news-article-block-script',
    get_template_directory_uri() . '/blocks/news-article/script.js',
    array('jquery'),
    wp_get_theme()->get('Version'),
    true
);
?>

<div <?php echo $block_wrapper_attributes; ?>>
    <?php if ($is_preview) : ?>
        <div class="block-preview-indicator">
            <span>📰 News Article Preview</span>
        </div>
    <?php endif; ?>
    
    <?php if ($subcategory) : ?>
        <span class="news-subcategory subcategory-<?php echo esc_attr($subcategory); ?>">
            <?php echo esc_html(ucfirst(str_replace('_', ' ', $subcategory))); ?>
        </span>
    <?php endif; ?>
    
    <?php if ($featured_image) : ?>
        <div class="news-featured-image">
            <?php
            $image_sizes = array('news-featured', 'large', 'medium_large', 'medium');
            $image_url = $featured_image['url'];
            $image_alt = $featured_image['alt'] ?: $news_title;
            
            // Try to get the best available image size
            foreach ($image_sizes as $size) {
                if (isset($featured_image['sizes'][$size])) {
                    $image_url = $featured_image['sizes'][$size];
                    break;
                }
            }
            ?>
            <img src="<?php echo esc_url($image_url); ?>" 
                 alt="<?php echo esc_attr($image_alt); ?>"
                 loading="lazy"
                 decoding="async">
        </div>
    <?php endif; ?>
    
    <div class="news-content">
        <h2 class="news-title"><?php echo esc_html($news_title); ?></h2>
        
        <?php if ($publication_date) : ?>
            <time class="news-date" datetime="<?php echo esc_attr($publication_date); ?>">
                <?php echo date('F j, Y', strtotime($publication_date)); ?>
            </time>
        <?php else : ?>
            <time class="news-date" datetime="<?php echo get_the_date('c'); ?>">
                <?php echo get_the_date('F j, Y'); ?>
            </time>
        <?php endif; ?>
        
        <?php if ($excerpt) : ?>
            <div class="news-excerpt">
                <?php echo wp_kses_post($excerpt); ?>
            </div>
        <?php endif; ?>
        
        <div class="news-body">
            <?php 
            if ($post_content) {
                echo apply_filters('the_content', $post_content);
            } else {
                echo '<p><em>Add content to see it displayed here...</em></p>';
            }
            ?>
        </div>
        
        <?php if (!$is_preview && !is_admin()) : ?>
            <!-- Social sharing will be added by JavaScript -->
            <div class="news-social-share">
                <h4>Share this article</h4>
                <div class="social-share-buttons">
                    <a href="#" class="social-share-button facebook" aria-label="Share on Facebook">
                        <span>f</span>
                    </a>
                    <a href="#" class="social-share-button twitter" aria-label="Share on Twitter">
                        <span>𝕏</span>
                    </a>
                    <a href="#" class="social-share-button linkedin" aria-label="Share on LinkedIn">
                        <span>in</span>
                    </a>
                    <a href="#" class="social-share-button email" aria-label="Share via Email">
                        <span>✉</span>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ($is_preview) : ?>
        <div class="block-preview-footer">
            <small>This is how your news article will appear to visitors</small>
        </div>
    <?php endif; ?>
</div>

<?php if ($is_preview) : ?>
<style>
.block-preview-indicator {
    background: #0073aa;
    color: white;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: 1rem;
    border-radius: 4px;
}

.block-preview-footer {
    background: #f0f0f1;
    padding: 0.75rem 1rem;
    text-align: center;
    color: #646970;
    border-top: 1px solid #dcdcde;
    margin-top: 1rem;
}

.block-preview-footer small {
    font-size: 0.8125rem;
}
</style>
<?php endif; ?>