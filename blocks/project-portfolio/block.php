<?php
/**
 * Project Portfolio Block Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int $post_id The post ID this block is saved to.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Create id attribute allowing for custom "anchor" value.
$id = 'project-portfolio-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'project-portfolio-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get ACF fields
$completion_status = get_field('completion_status');
$completion_percentage = get_field('completion_percentage');
$project_type = get_field('project_type');
$client = get_field('client');
$location = get_field('location');
$image_gallery = get_field('image_gallery');
$project_metadata = get_field('project_metadata');

// Use post title
$project_name = get_the_title();

// Handle preview mode
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];
if ($is_preview) {
    $project_name = 'Sample Architectural Project';
    $completion_status = $block['data']['completion_status'] ?? 'completed';
    $completion_percentage = $block['data']['completion_percentage'] ?? 100;
    $project_type = $block['data']['project_type'] ?? 'civic';
    $client = $block['data']['client'] ?? 'Sample Client Organization';
    $location = $block['data']['location'] ?? 'Sample City, State';
    
    // Create sample gallery for preview
    $image_gallery = array(
        array(
            'ID' => 0,
            'url' => 'https://via.placeholder.com/600x400/007bff/ffffff?text=Project+Image+1',
            'alt' => 'Sample project exterior view',
            'title' => 'Project Exterior',
            'sizes' => array('project-gallery' => 'https://via.placeholder.com/600x400/007bff/ffffff?text=Project+Image+1')
        ),
        array(
            'ID' => 0,
            'url' => 'https://via.placeholder.com/600x400/28a745/ffffff?text=Project+Image+2',
            'alt' => 'Sample project interior view',
            'title' => 'Project Interior',
            'sizes' => array('project-gallery' => 'https://via.placeholder.com/600x400/28a745/ffffff?text=Project+Image+2')
        ),
        array(
            'ID' => 0,
            'url' => 'https://via.placeholder.com/600x400/dc3545/ffffff?text=Project+Image+3',
            'alt' => 'Sample project detail view',
            'title' => 'Project Detail',
            'sizes' => array('project-gallery' => 'https://via.placeholder.com/600x400/dc3545/ffffff?text=Project+Image+3')
        )
    );
    
    $project_metadata = array(
        'square_footage' => '15000',
        'budget' => '$2.5M',
        'start_date' => '2023-01-15',
        'end_date' => '2024-06-30'
    );
}

// Don't render if no project name
if (empty($project_name) && !$is_preview) {
    if (is_admin()) {
        echo '<div class="acf-block-placeholder"><p>' . __('Please enter a post title to display this block.', 'ricelipka-theme') . '</p></div>';
    }
    return;
}

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    
    <!-- Project Header -->
    <header class="project-header">
        <h2 class="project-name"><?php echo esc_html($project_name); ?></h2>
        
        <div class="project-meta">
            <?php if ($completion_status) : ?>
                <span class="project-status status-<?php echo esc_attr($completion_status); ?>">
                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $completion_status))); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($completion_status === 'in_progress' && $completion_percentage) : ?>
                <div class="completion-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo esc_attr($completion_percentage); ?>%"></div>
                    </div>
                    <span class="progress-text"><?php echo esc_html($completion_percentage); ?>% Complete</span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="project-details">
            <?php if ($project_type) : ?>
                <span><strong><?php _e('Type:', 'ricelipka-theme'); ?></strong> <?php echo esc_html(ucfirst(str_replace('_', ' ', $project_type))); ?></span>
            <?php endif; ?>
            
            <?php if ($client) : ?>
                <span><strong><?php _e('Client:', 'ricelipka-theme'); ?></strong> <?php echo esc_html($client); ?></span>
            <?php endif; ?>
            
            <?php if ($location) : ?>
                <span><strong><?php _e('Location:', 'ricelipka-theme'); ?></strong> <?php echo esc_html($location); ?></span>
            <?php endif; ?>
        </div>
    </header>
    
    <!-- Project Description -->
    <?php if (get_the_content() && !$is_preview) : ?>
        <div class="project-description">
            <?php the_content(); ?>
        </div>
    <?php elseif ($is_preview) : ?>
        <div class="project-description">
            <p><?php _e('This is a sample architectural project showcasing our expertise in civic architecture. The project demonstrates our commitment to sustainable design and community-focused spaces.', 'ricelipka-theme'); ?></p>
            <p><?php _e('Key features include innovative use of natural light, sustainable materials, and accessible design principles that serve the community for generations to come.', 'ricelipka-theme'); ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Project Gallery -->
    <?php if ($image_gallery && is_array($image_gallery) && count($image_gallery) > 0) : ?>
        <div class="project-gallery">
            <div class="gallery-grid">
                <?php foreach ($image_gallery as $index => $image) : 
                    $image_id = $is_preview ? 0 : $image['ID'];
                    $image_url = $is_preview ? $image['url'] : wp_get_attachment_image_url($image_id, 'project-medium');
                    $image_large = $is_preview ? $image['url'] : wp_get_attachment_image_url($image_id, 'project-large');
                    $image_xlarge = $is_preview ? $image['url'] : wp_get_attachment_image_url($image_id, 'project-xlarge');
                    $image_alt = $is_preview ? $image['alt'] : get_post_meta($image_id, '_wp_attachment_image_alt', true);
                    $image_title = $is_preview ? $image['title'] : get_the_title($image_id);
                    
                    // Generate WebP URLs if available
                    $webp_url = $is_preview ? '' : ricelipka_get_webp_url($image_url);
                    $webp_large = $is_preview ? '' : ricelipka_get_webp_url($image_large);
                    
                    // Generate responsive srcset
                    $srcset = $is_preview ? '' : ricelipka_generate_responsive_srcset($image_id, array('project-medium', 'project-large', 'project-xlarge'));
                    $webp_srcset = $is_preview ? '' : ricelipka_generate_webp_srcset($image_id, array('project-medium', 'project-large', 'project-xlarge'));
                    
                    // Extract category from alt text for filtering
                    $category = ricelipka_extract_image_category($image_alt);
                    
                    // Determine loading strategy
                    $loading_strategy = $index < 2 ? 'eager' : 'lazy';
                    $fetch_priority = $index < 2 ? 'high' : 'auto';
                ?>
                    <div class="gallery-item" data-category="<?php echo esc_attr($category); ?>">
                        <?php if (!$is_preview && $webp_url) : ?>
                            <!-- WebP version with fallback -->
                            <picture>
                                <?php if ($webp_srcset) : ?>
                                    <source 
                                        srcset="<?php echo esc_attr($webp_srcset); ?>" 
                                        sizes="(max-width: 767px) 100vw, (max-width: 1024px) 50vw, 33vw"
                                        type="image/webp"
                                    >
                                <?php endif; ?>
                                <?php if ($srcset) : ?>
                                    <source 
                                        srcset="<?php echo esc_attr($srcset); ?>" 
                                        sizes="(max-width: 767px) 100vw, (max-width: 1024px) 50vw, 33vw"
                                    >
                                <?php endif; ?>
                                <img 
                                    src="<?php echo esc_url($image_url); ?>" 
                                    alt="<?php echo esc_attr($image_alt ?: $image_title); ?>"
                                    data-full="<?php echo esc_url($image_large); ?>"
                                    data-category="<?php echo esc_attr($category); ?>"
                                    loading="<?php echo esc_attr($loading_strategy); ?>"
                                    fetchpriority="<?php echo esc_attr($fetch_priority); ?>"
                                    decoding="async"
                                    class="responsive-image"
                                    <?php if ($loading_strategy === 'lazy') : ?>
                                        data-lazy="true"
                                        data-src="<?php echo esc_url($image_url); ?>"
                                        <?php if ($srcset) : ?>
                                            data-srcset="<?php echo esc_attr($srcset); ?>"
                                        <?php endif; ?>
                                        <?php if ($webp_url) : ?>
                                            data-webp="<?php echo esc_url($webp_url); ?>"
                                        <?php endif; ?>
                                        <?php if ($webp_srcset) : ?>
                                            data-webp-srcset="<?php echo esc_attr($webp_srcset); ?>"
                                        <?php endif; ?>
                                    <?php endif; ?>
                                />
                            </picture>
                        <?php else : ?>
                            <!-- Standard image with lazy loading -->
                            <img 
                                src="<?php echo $loading_strategy === 'lazy' ? 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E' : esc_url($image_url); ?>" 
                                alt="<?php echo esc_attr($image_alt ?: $image_title); ?>"
                                data-full="<?php echo esc_url($image_large); ?>"
                                data-category="<?php echo esc_attr($category); ?>"
                                loading="<?php echo esc_attr($loading_strategy); ?>"
                                fetchpriority="<?php echo esc_attr($fetch_priority); ?>"
                                decoding="async"
                                class="responsive-image <?php echo $loading_strategy === 'lazy' ? 'loading-placeholder' : ''; ?>"
                                <?php if ($loading_strategy === 'lazy') : ?>
                                    data-lazy="true"
                                    data-src="<?php echo esc_url($image_url); ?>"
                                    <?php if ($srcset) : ?>
                                        data-srcset="<?php echo esc_attr($srcset); ?>"
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?php if ($srcset) : ?>
                                        srcset="<?php echo esc_attr($srcset); ?>"
                                        sizes="(max-width: 767px) 100vw, (max-width: 1024px) 50vw, 33vw"
                                    <?php endif; ?>
                                <?php endif; ?>
                            />
                        <?php endif; ?>
                        
                        <!-- Image overlay with category and title -->
                        <div class="gallery-overlay">
                            <span class="image-category"><?php echo esc_html(ucfirst($category)); ?></span>
                            <?php if ($image_title) : ?>
                                <span class="image-title"><?php echo esc_html($image_title); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Gallery Controls -->
            <div class="gallery-controls">
                <button class="filter-btn active" data-filter="all"><?php _e('All', 'ricelipka-theme'); ?></button>
                <button class="filter-btn" data-filter="exterior"><?php _e('Exterior', 'ricelipka-theme'); ?></button>
                <button class="filter-btn" data-filter="interior"><?php _e('Interior', 'ricelipka-theme'); ?></button>
                <button class="filter-btn" data-filter="detail"><?php _e('Details', 'ricelipka-theme'); ?></button>
                <button class="filter-btn" data-filter="construction"><?php _e('Construction', 'ricelipka-theme'); ?></button>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Project Metadata -->
    <?php if ($project_metadata && (
        !empty($project_metadata['square_footage']) || 
        !empty($project_metadata['budget']) || 
        !empty($project_metadata['start_date']) || 
        !empty($project_metadata['end_date'])
    )) : ?>
        <div class="project-metadata">
            <h3><?php _e('Project Details', 'ricelipka-theme'); ?></h3>
            <dl class="metadata-list">
                <?php if (!empty($project_metadata['square_footage'])) : ?>
                    <dt><?php _e('Square Footage', 'ricelipka-theme'); ?></dt>
                    <dd><?php echo esc_html(number_format($project_metadata['square_footage'])); ?> sq ft</dd>
                <?php endif; ?>
                
                <?php if (!empty($project_metadata['budget'])) : ?>
                    <dt><?php _e('Budget', 'ricelipka-theme'); ?></dt>
                    <dd><?php echo esc_html($project_metadata['budget']); ?></dd>
                <?php endif; ?>
                
                <?php if (!empty($project_metadata['start_date'])) : ?>
                    <dt><?php _e('Start Date', 'ricelipka-theme'); ?></dt>
                    <dd><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($project_metadata['start_date']))); ?></dd>
                <?php endif; ?>
                
                <?php if (!empty($project_metadata['end_date'])) : ?>
                    <dt><?php _e('End Date', 'ricelipka-theme'); ?></dt>
                    <dd><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($project_metadata['end_date']))); ?></dd>
                <?php endif; ?>
            </dl>
        </div>
    <?php endif; ?>
    
</div>

<?php
/**
 * Helper function to extract image category from alt text
 */
function ricelipka_extract_image_category($alt) {
    if (empty($alt)) {
        return 'all';
    }
    
    $alt_lower = strtolower($alt);
    
    $categories = array(
        'exterior' => array('exterior', 'outside', 'facade', 'building', 'front'),
        'interior' => array('interior', 'inside', 'room', 'lobby', 'hall'),
        'detail' => array('detail', 'close-up', 'closeup', 'feature', 'element'),
        'construction' => array('construction', 'progress', 'building', 'work', 'site')
    );
    
    foreach ($categories as $category => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($alt_lower, $keyword) !== false) {
                return $category;
            }
        }
    }
    
    return 'all';
}
?>