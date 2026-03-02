<?php
/**
 * Award Information Block Template
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
$id = 'award-information-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'award-information-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get ACF fields
$awarding_organization = get_field('awarding_organization');
$associated_project = get_field('associated_project');
$date_received = get_field('date_received');
$recognition_image = get_field('recognition_image');

// Use post title
$award_name = get_the_title();

// Handle preview mode
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];
if ($is_preview) {
    $award_name = 'Excellence in Civic Architecture Award';
    $awarding_organization = $block['data']['awarding_organization'] ?? 'American Institute of Architects';
    $date_received = $block['data']['date_received'] ?? date('Y-m-d', strtotime('-3 months'));
    
    // Create sample recognition image for preview
    $recognition_image = array(
        'ID' => 0,
        'url' => 'https://via.placeholder.com/400x300/ffd700/000000?text=Award+Certificate',
        'alt' => 'Sample award certificate',
        'title' => 'Excellence in Architecture Award',
        'sizes' => array('award-certificate' => 'https://via.placeholder.com/400x300/ffd700/000000?text=Award+Certificate')
    );
    
    // Create sample associated project for preview
    $associated_project = (object) array(
        'ID' => 0,
        'post_title' => 'Sample Civic Center Project',
        'post_excerpt' => 'A groundbreaking civic architecture project that exemplifies sustainable design and community engagement.',
        'guid' => '#'
    );
}

// Don't render if no award name
if (empty($award_name) && !$is_preview) {
    if (is_admin()) {
        echo '<div class="acf-block-placeholder"><p>' . __('Please enter a post title to display this block.', 'ricelipka-theme') . '</p></div>';
    }
    return;
}

// Calculate award age for timeline
$award_age = null;
$award_year = null;
if ($date_received) {
    $award_timestamp = strtotime($date_received);
    $award_year = date('Y', $award_timestamp);
    $award_age = floor((time() - $award_timestamp) / (365.25 * 24 * 60 * 60));
}

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" 
     data-award-year="<?php echo esc_attr($award_year); ?>"
     data-award-age="<?php echo esc_attr($award_age); ?>">
    
    <?php if ($is_preview) : ?>
        <div class="block-preview-indicator">
            <span>🏆 Award Information Preview</span>
        </div>
    <?php endif; ?>
    
    <!-- Award Header -->
    <header class="award-header">
        <div class="award-title-section">
            <h2 class="award-name"><?php echo esc_html($award_name); ?></h2>
            
            <?php if ($awarding_organization) : ?>
                <div class="awarding-organization">
                    <span class="organization-label"><?php _e('Awarded by:', 'ricelipka-theme'); ?></span>
                    <span class="organization-name"><?php echo esc_html($awarding_organization); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($date_received) : ?>
                <div class="award-date">
                    <span class="date-icon">📅</span>
                    <time datetime="<?php echo esc_attr($date_received); ?>">
                        <?php echo date_i18n('F Y', strtotime($date_received)); ?>
                    </time>
                    <?php if ($award_age !== null && $award_age > 0) : ?>
                        <span class="award-age">(<?php echo $award_age; ?> <?php echo $award_age === 1 ? __('year ago', 'ricelipka-theme') : __('years ago', 'ricelipka-theme'); ?>)</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($recognition_image) : ?>
            <div class="award-certificate">
                <?php
                $image_url = $is_preview ? $recognition_image['url'] : wp_get_attachment_image_url($recognition_image['ID'], 'award-certificate');
                $image_full = $is_preview ? $recognition_image['url'] : wp_get_attachment_image_url($recognition_image['ID'], 'large');
                $image_alt = $is_preview ? $recognition_image['alt'] : get_post_meta($recognition_image['ID'], '_wp_attachment_image_alt', true);
                $image_title = $is_preview ? $recognition_image['title'] : get_the_title($recognition_image['ID']);
                ?>
                <div class="certificate-container">
                    <img src="<?php echo esc_url($image_url); ?>" 
                         alt="<?php echo esc_attr($image_alt ?: $award_name . ' certificate'); ?>"
                         data-full="<?php echo esc_url($image_full); ?>"
                         class="certificate-image"
                         loading="lazy"
                         decoding="async">
                    <button class="certificate-zoom" aria-label="<?php esc_attr_e('View certificate full size', 'ricelipka-theme'); ?>">
                        <span class="zoom-icon">🔍</span>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </header>
    
    <!-- Award Description -->
    <?php if (get_the_content() && !$is_preview) : ?>
        <div class="award-description">
            <?php the_content(); ?>
        </div>
    <?php elseif ($is_preview) : ?>
        <div class="award-description">
            <p><?php _e('This prestigious award recognizes excellence in architectural design and innovation. Our project was selected from hundreds of submissions for its outstanding contribution to civic architecture and sustainable design practices.', 'ricelipka-theme'); ?></p>
            <p><?php _e('The recognition highlights our commitment to creating spaces that serve communities while respecting environmental considerations and promoting social equity through thoughtful design.', 'ricelipka-theme'); ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Associated Project -->
    <?php if ($associated_project) : ?>
        <div class="associated-project">
            <h3 class="project-section-title">
                <span class="project-icon">🏗️</span>
                <?php _e('Awarded Project', 'ricelipka-theme'); ?>
            </h3>
            
            <div class="project-card">
                <?php if (!$is_preview) : ?>
                    <h4 class="project-title">
                        <a href="<?php echo esc_url(get_permalink($associated_project->ID)); ?>" 
                           class="project-link">
                            <?php echo esc_html($associated_project->post_title); ?>
                        </a>
                    </h4>
                    
                    <?php if ($associated_project->post_excerpt) : ?>
                        <p class="project-excerpt"><?php echo esc_html($associated_project->post_excerpt); ?></p>
                    <?php endif; ?>
                    
                    <div class="project-actions">
                        <a href="<?php echo esc_url(get_permalink($associated_project->ID)); ?>" 
                           class="view-project-btn">
                            <?php _e('View Project Details', 'ricelipka-theme'); ?>
                            <span class="project-arrow">→</span>
                        </a>
                        
                        <?php
                        // Get project gallery for preview
                        $project_gallery = get_field('image_gallery', $associated_project->ID);
                        if ($project_gallery && is_array($project_gallery) && count($project_gallery) > 0) :
                        ?>
                            <button class="project-gallery-preview" 
                                    data-project-id="<?php echo esc_attr($associated_project->ID); ?>">
                                <span class="gallery-icon">🖼️</span>
                                <?php _e('View Gallery', 'ricelipka-theme'); ?>
                                <span class="gallery-count">(<?php echo count($project_gallery); ?>)</span>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <h4 class="project-title">
                        <a href="#" class="project-link">
                            <?php echo esc_html($associated_project->post_title); ?>
                        </a>
                    </h4>
                    
                    <p class="project-excerpt"><?php echo esc_html($associated_project->post_excerpt); ?></p>
                    
                    <div class="project-actions">
                        <a href="#" class="view-project-btn">
                            <?php _e('View Project Details', 'ricelipka-theme'); ?>
                            <span class="project-arrow">→</span>
                        </a>
                        
                        <button class="project-gallery-preview">
                            <span class="gallery-icon">🖼️</span>
                            <?php _e('View Gallery', 'ricelipka-theme'); ?>
                            <span class="gallery-count">(8)</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Timeline Visualization -->
    <?php if ($award_year) : ?>
        <div class="award-timeline">
            <h3 class="timeline-title">
                <span class="timeline-icon">📈</span>
                <?php _e('Achievement Timeline', 'ricelipka-theme'); ?>
            </h3>
            
            <div class="timeline-container">
                <div class="timeline-line"></div>
                
                <div class="timeline-item award-milestone" data-year="<?php echo esc_attr($award_year); ?>">
                    <div class="timeline-marker award-marker">
                        <span class="marker-icon">🏆</span>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date"><?php echo esc_html($award_year); ?></div>
                        <div class="timeline-event"><?php echo esc_html($award_name); ?></div>
                        <div class="timeline-organization"><?php echo esc_html($awarding_organization); ?></div>
                    </div>
                </div>
                
                <?php if ($associated_project && !$is_preview) : 
                    // Get project completion date if available
                    $project_completion = get_field('project_metadata', $associated_project->ID);
                    $project_year = null;
                    if ($project_completion && !empty($project_completion['end_date'])) {
                        $project_year = date('Y', strtotime($project_completion['end_date']));
                    }
                    
                    if ($project_year && $project_year <= $award_year) :
                ?>
                    <div class="timeline-item project-milestone" data-year="<?php echo esc_attr($project_year); ?>">
                        <div class="timeline-marker project-marker">
                            <span class="marker-icon">🏗️</span>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-date"><?php echo esc_html($project_year); ?></div>
                            <div class="timeline-event"><?php _e('Project Completed', 'ricelipka-theme'); ?></div>
                            <div class="timeline-project"><?php echo esc_html($associated_project->post_title); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php elseif ($is_preview) : ?>
                    <div class="timeline-item project-milestone" data-year="<?php echo esc_attr($award_year - 1); ?>">
                        <div class="timeline-marker project-marker">
                            <span class="marker-icon">🏗️</span>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-date"><?php echo esc_html($award_year - 1); ?></div>
                            <div class="timeline-event"><?php _e('Project Completed', 'ricelipka-theme'); ?></div>
                            <div class="timeline-project"><?php echo esc_html($associated_project->post_title); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Recognition Gallery -->
    <?php 
    // Get other awards for cross-referencing (in real implementation)
    if (!$is_preview) {
        $related_awards = get_posts(array(
            'post_type' => 'post',
            'category_name' => 'awards',
            'posts_per_page' => 3,
            'exclude' => array(get_the_ID()),
            'meta_query' => array(
                array(
                    'key' => 'awarding_organization',
                    'value' => $awarding_organization,
                    'compare' => 'LIKE'
                )
            )
        ));
    } else {
        // Sample related awards for preview
        $related_awards = array(
            (object) array(
                'ID' => 1,
                'post_title' => 'Sustainable Design Excellence Award',
                'post_date' => date('Y-m-d H:i:s', strtotime('-1 year')),
                'guid' => '#'
            ),
            (object) array(
                'ID' => 2,
                'post_title' => 'Community Impact Recognition',
                'post_date' => date('Y-m-d H:i:s', strtotime('-2 years')),
                'guid' => '#'
            )
        );
    }
    
    if ($related_awards && count($related_awards) > 0) :
    ?>
        <div class="recognition-gallery">
            <h3 class="gallery-title">
                <span class="gallery-icon">🏅</span>
                <?php _e('Related Recognition', 'ricelipka-theme'); ?>
            </h3>
            
            <div class="awards-grid">
                <?php foreach ($related_awards as $related_award) : ?>
                    <div class="award-card">
                        <h4 class="related-award-title">
                            <?php if (!$is_preview) : ?>
                                <a href="<?php echo esc_url(get_permalink($related_award->ID)); ?>">
                                    <?php echo esc_html($related_award->post_title); ?>
                                </a>
                            <?php else : ?>
                                <a href="#"><?php echo esc_html($related_award->post_title); ?></a>
                            <?php endif; ?>
                        </h4>
                        
                        <div class="related-award-date">
                            <?php echo date_i18n('Y', strtotime($related_award->post_date)); ?>
                        </div>
                        
                        <?php if (!$is_preview) : ?>
                            <div class="related-award-organization">
                                <?php echo esc_html(get_field('awarding_organization', $related_award->ID)); ?>
                            </div>
                        <?php else : ?>
                            <div class="related-award-organization">
                                <?php echo esc_html($awarding_organization); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Cross-referencing and Sharing -->
    <div class="award-actions">
        <?php if ($associated_project) : ?>
            <div class="cross-reference-actions">
                <h4><?php _e('Explore More', 'ricelipka-theme'); ?></h4>
                
                <?php if (!$is_preview) : ?>
                    <a href="<?php echo esc_url(get_permalink($associated_project->ID)); ?>" 
                       class="action-btn project-btn">
                        <span class="btn-icon">🏗️</span>
                        <?php _e('View Full Project', 'ricelipka-theme'); ?>
                    </a>
                <?php else : ?>
                    <a href="#" class="action-btn project-btn">
                        <span class="btn-icon">🏗️</span>
                        <?php _e('View Full Project', 'ricelipka-theme'); ?>
                    </a>
                <?php endif; ?>
                
                <a href="<?php echo esc_url(get_category_link(get_cat_ID('awards'))); ?>" 
                   class="action-btn awards-btn">
                    <span class="btn-icon">🏆</span>
                    <?php _e('All Awards', 'ricelipka-theme'); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="share-actions">
            <h4><?php _e('Share Achievement', 'ricelipka-theme'); ?></h4>
            
            <button class="action-btn share-btn" data-share-type="award">
                <span class="btn-icon">📤</span>
                <?php _e('Share Award', 'ricelipka-theme'); ?>
            </button>
            
            <button class="action-btn print-btn">
                <span class="btn-icon">🖨️</span>
                <?php _e('Print Certificate', 'ricelipka-theme'); ?>
            </button>
        </div>
    </div>
    
    <!-- Certificate Lightbox Modal (hidden by default) -->
    <div class="certificate-modal" id="certificate-modal-<?php echo esc_attr($block['id']); ?>" style="display: none;">
        <div class="certificate-modal-content">
            <div class="certificate-modal-header">
                <h3><?php echo esc_html($award_name); ?></h3>
                <button class="certificate-modal-close" aria-label="<?php esc_attr_e('Close certificate view', 'ricelipka-theme'); ?>">&times;</button>
            </div>
            <div class="certificate-modal-body">
                <?php if ($recognition_image) : ?>
                    <img src="<?php echo esc_url($is_preview ? $recognition_image['url'] : wp_get_attachment_image_url($recognition_image['ID'], 'large')); ?>" 
                         alt="<?php echo esc_attr($image_alt ?: $award_name . ' certificate'); ?>"
                         class="certificate-full-image">
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Share Modal (hidden by default) -->
    <div class="share-modal" id="share-modal-<?php echo esc_attr($block['id']); ?>" style="display: none;">
        <div class="share-modal-content">
            <div class="share-modal-header">
                <h3><?php _e('Share Award', 'ricelipka-theme'); ?></h3>
                <button class="share-modal-close" aria-label="<?php esc_attr_e('Close share modal', 'ricelipka-theme'); ?>">&times;</button>
            </div>
            <div class="share-modal-body">
                <div class="share-options">
                    <a href="#" class="share-option facebook" data-share="facebook">
                        <span class="share-icon">f</span>
                        <?php _e('Facebook', 'ricelipka-theme'); ?>
                    </a>
                    <a href="#" class="share-option twitter" data-share="twitter">
                        <span class="share-icon">𝕏</span>
                        <?php _e('Twitter', 'ricelipka-theme'); ?>
                    </a>
                    <a href="#" class="share-option linkedin" data-share="linkedin">
                        <span class="share-icon">in</span>
                        <?php _e('LinkedIn', 'ricelipka-theme'); ?>
                    </a>
                    <a href="#" class="share-option email" data-share="email">
                        <span class="share-icon">✉</span>
                        <?php _e('Email', 'ricelipka-theme'); ?>
                    </a>
                    <button class="share-option copy-link" data-share="copy">
                        <span class="share-icon">🔗</span>
                        <?php _e('Copy Link', 'ricelipka-theme'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($is_preview) : ?>
        <div class="block-preview-footer">
            <small><?php _e('This is how your award information will appear to visitors', 'ricelipka-theme'); ?></small>
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