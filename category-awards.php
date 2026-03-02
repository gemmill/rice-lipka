<?php
/**
 * The template for displaying Awards category archives
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main awards-archive category-archive">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <header class="page-header awards-header category-header">
                <h1 class="page-title awards-title category-title">
                    <?php single_cat_title(); ?>
                </h1>
                
                <?php
                $category = get_queried_object();
                if ($category->description) : ?>
                    <div class="category-description awards-description">
                        <?php echo wp_kses_post($category->description); ?>
                    </div>
                <?php endif; ?>
                
                <div class="category-count">
                    <?php
                    $count = $category->count;
                    printf(
                        _n('%d award', '%d awards', $count, 'ricelipka-theme'),
                        $count
                    );
                    ?>
                </div>
                
                <!-- Award filters removed - using only top-level categories -->
                    <ul>
                        <li><a href="#" class="filter-btn active" data-filter="all"><?php _e('All Awards', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="design_excellence"><?php _e('Design Excellence', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="sustainability"><?php _e('Sustainability Awards', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="innovation"><?php _e('Innovation Awards', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="community_impact"><?php _e('Community Impact', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="professional_recognition"><?php _e('Professional Recognition', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-filter="project_awards"><?php _e('Project Awards', 'ricelipka-theme'); ?></a></li>
                    </ul>
                </div>
            </header>
            
            <div class="posts-container awards-container">
                <?php while (have_posts()) : the_post(); ?>
                    
                    <?php
                    // Get ACF fields for awards
                    $award_fields = ricelipka_get_category_fields(get_the_ID());
                    $associated_project = $award_fields['associated_project'];
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('award-item post-item'); ?>>
                        
                        <div class="award-content-wrapper">
                            <?php
                            // Display recognition image or featured image
                            $recognition_image = $award_fields['recognition_image'];
                            $display_image = null;
                            
                            if ($recognition_image && is_array($recognition_image)) {
                                $display_image = $recognition_image;
                            } elseif (has_post_thumbnail()) {
                                $display_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');
                            }
                            ?>
                            
                            <?php if ($display_image) : ?>
                                <div class="post-thumbnail award-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if (is_array($display_image) && isset($display_image['url'])) : ?>
                                            <img src="<?php echo esc_url($display_image['url']); ?>" 
                                                 alt="<?php echo esc_attr($display_image['alt'] ?: get_the_title()); ?>" />
                                        <?php else : ?>
                                            <?php the_post_thumbnail('medium'); ?>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content award-content">
                                <header class="entry-header">
                                    <h2 class="entry-title award-entry-title">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo esc_html($award_fields['award_name'] ?: get_the_title()); ?>
                                        </a>
                                    </h2>
                                    
                                    <div class="entry-meta award-meta">
                                        <?php if ($award_fields['awarding_organization']) : ?>
                                            <div class="awarding-organization">
                                                <strong><?php _e('Awarded by:', 'ricelipka-theme'); ?></strong>
                                                <?php echo esc_html($award_fields['awarding_organization']); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($award_fields['date_received']) : ?>
                                            <time class="award-date" datetime="<?php echo esc_attr($award_fields['date_received']); ?>">
                                                <?php echo date('F j, Y', strtotime($award_fields['date_received'])); ?>
                                            </time>
                                        <?php endif; ?>
                                    </div>
                                </header>
                                
                                <?php if ($associated_project && is_object($associated_project)) : ?>
                                    <div class="associated-project">
                                        <strong><?php _e('Project:', 'ricelipka-theme'); ?></strong>
                                        <a href="<?php echo get_permalink($associated_project->ID); ?>" class="project-link">
                                            <?php echo esc_html($associated_project->post_title); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="entry-summary award-summary">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <footer class="entry-footer award-footer">
                                    <div class="award-actions">
                                        <a href="<?php the_permalink(); ?>" class="read-more award-read-more">
                                            <?php _e('View Award Details', 'ricelipka-theme'); ?>
                                        </a>
                                        
                                        <?php if ($associated_project && is_object($associated_project)) : ?>
                                            <a href="<?php echo get_permalink($associated_project->ID); ?>" class="view-project">
                                                <?php _e('View Project', 'ricelipka-theme'); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </footer>
                            </div>
                        </div>
                        
                    </article>
                    
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'prev_text' => __('Previous Awards', 'ricelipka-theme'),
                'next_text' => __('Next Awards', 'ricelipka-theme'),
            ));
            ?>
            
        <?php else : ?>
            
            <section class="no-results awards-no-results">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('No Awards Found', 'ricelipka-theme'); ?></h1>
                </header>
                
                <div class="page-content">
                    <p><?php _e('There are currently no awards to display. Please check back later.', 'ricelipka-theme'); ?></p>
                </div>
            </section>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();