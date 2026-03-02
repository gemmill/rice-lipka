<?php
/**
 * Archive template for News category with chronological ordering
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main news-archive archive-page">
    <div class="container">
        <header class="archive-header">
            <h1 class="archive-title">
                <?php
                $archive_year = get_query_var('archive_year');
                $archive_month = get_query_var('archive_month');
                
                if ($archive_year && $archive_month) {
                    echo sprintf(__('News Archive: %s %s', 'ricelipka-theme'), 
                        date('F', mktime(0, 0, 0, $archive_month, 1)), 
                        $archive_year
                    );
                } elseif ($archive_year) {
                    echo sprintf(__('News Archive: %s', 'ricelipka-theme'), $archive_year);
                } else {
                    _e('News Archive', 'ricelipka-theme');
                }
                ?>
            </h1>
            
            <div class="archive-description">
                <p><?php _e('Browse all news articles chronologically. Use the filters below to find specific content.', 'ricelipka-theme'); ?></p>
            </div>
            
            <div class="archive-navigation">
                <a href="<?php echo esc_url(get_category_link(get_cat_ID('News'))); ?>" class="back-to-current">
                    ← <?php _e('Back to Current News', 'ricelipka-theme'); ?>
                </a>
            </div>
        </header>
        
        <!-- Archive Controls -->
        <div class="archive-controls">
            <div class="archive-selectors">
                <div class="archive-selector">
                    <label for="news-year-selector"><?php _e('Year', 'ricelipka-theme'); ?></label>
                    <select id="news-year-selector" class="archive-year-selector" data-category="news">
                        <option value=""><?php _e('All Years', 'ricelipka-theme'); ?></option>
                        <?php
                        $years = ricelipka_get_archive_years('news');
                        foreach ($years as $year) {
                            $selected = ($archive_year == $year) ? 'selected' : '';
                            echo '<option value="' . esc_attr($year) . '" ' . $selected . '>' . esc_html($year) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div class="archive-selector">
                    <label for="news-month-selector"><?php _e('Month', 'ricelipka-theme'); ?></label>
                    <select id="news-month-selector" class="archive-month-selector" data-category="news">
                        <option value=""><?php _e('All Months', 'ricelipka-theme'); ?></option>
                        <?php
                        if ($archive_year) {
                            $months = ricelipka_get_archive_months('news', $archive_year);
                            foreach ($months as $month) {
                                $selected = ($archive_month == $month->month) ? 'selected' : '';
                                echo '<option value="' . esc_attr($month->month) . '" ' . $selected . '>' . esc_html($month->month_name) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- News subcategory filters -->
        <div class="news-filters subcategory-filters category-nav">
            <ul>
                <li><a href="#" class="filter-btn active" data-subcategory="all"><?php _e('All News', 'ricelipka-theme'); ?></a></li>
                <li><a href="#" class="filter-btn" data-subcategory="project_updates"><?php _e('Project Updates', 'ricelipka-theme'); ?></a></li>
                <li><a href="#" class="filter-btn" data-subcategory="event_announcements"><?php _e('Event Announcements', 'ricelipka-theme'); ?></a></li>
                <li><a href="#" class="filter-btn" data-subcategory="award_notifications"><?php _e('Award Notifications', 'ricelipka-theme'); ?></a></li>
                <li><a href="#" class="filter-btn" data-subcategory="firm_news"><?php _e('Firm News', 'ricelipka-theme'); ?></a></li>
                <li><a href="#" class="filter-btn" data-subcategory="press_releases"><?php _e('Press Releases', 'ricelipka-theme'); ?></a></li>
                <li><a href="#" class="filter-btn" data-subcategory="community_involvement"><?php _e('Community Involvement', 'ricelipka-theme'); ?></a></li>
            </ul>
        </div>
        
        <?php if (have_posts()) : ?>
            <div class="results-count">
                <?php
                global $wp_query;
                $count = $wp_query->found_posts;
                printf(
                    _n('%d article found', '%d articles found', $count, 'ricelipka-theme'),
                    $count
                );
                ?>
            </div>
            
            <div class="posts-container news-container posts-grid archive-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'news-card'); ?>
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'prev_text' => __('← Previous News', 'ricelipka-theme'),
                'next_text' => __('Next News →', 'ricelipka-theme'),
                'class' => 'pagination archive-pagination'
            ));
            ?>
            
        <?php else : ?>
            
            <section class="no-results archive-no-results">
                <header class="page-header">
                    <h2 class="page-title"><?php _e('No News Found', 'ricelipka-theme'); ?></h2>
                </header>
                
                <div class="page-content">
                    <p><?php _e('No news articles were found for the selected time period. Try adjusting your filters or browse all news.', 'ricelipka-theme'); ?></p>
                    <a href="<?php echo esc_url(get_category_link(get_cat_ID('News'))); ?>" class="button">
                        <?php _e('View All News', 'ricelipka-theme'); ?>
                    </a>
                </div>
            </section>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();