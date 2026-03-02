<?php
/**
 * The template for displaying News category archives
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main news-archive category-archive">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <header class="page-header news-header category-header">
                <h1 class="page-title news-title category-title">
                    <?php single_cat_title(); ?>
                </h1>
                
                <?php
                $category = get_queried_object();
                if ($category->description) : ?>
                    <div class="category-description news-description">
                        <?php echo wp_kses_post($category->description); ?>
                    </div>
                <?php endif; ?>
                
                <div class="category-count">
                    <?php
                    $count = $category->count;
                    printf(
                        _n('%d article', '%d articles', $count, 'ricelipka-theme'),
                        $count
                    );
                    ?>
                </div>
                
                <!-- Archive Controls -->
                <div class="archive-controls" style="display: none;">
                    <a href="#" class="archive-toggle"><?php _e('Archive & Filters', 'ricelipka-theme'); ?></a>
                    
                    <div class="archive-content" style="display: none;">
                        <div class="archive-selectors">
                            <div class="archive-selector">
                                <label for="news-year-selector"><?php _e('Year', 'ricelipka-theme'); ?></label>
                                <select id="news-year-selector" class="archive-year-selector" data-category="news">
                                    <option value=""><?php _e('All Years', 'ricelipka-theme'); ?></option>
                                    <?php
                                    $years = ricelipka_get_archive_years('news');
                                    foreach ($years as $year) {
                                        $selected = (get_query_var('archive_year') == $year) ? 'selected' : '';
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
                                    $current_year = get_query_var('archive_year') ?: date('Y');
                                    $months = ricelipka_get_archive_months('news', $current_year);
                                    foreach ($months as $month) {
                                        $selected = (get_query_var('archive_month') == $month->month) ? 'selected' : '';
                                        echo '<option value="' . esc_attr($month->month) . '" ' . $selected . '>' . esc_html($month->month_name) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="date-range-controls">
                            <div class="date-range-selector">
                                <label for="date-range-selector"><?php _e('Date Range', 'ricelipka-theme'); ?></label>
                                <select id="date-range-selector">
                                    <option value=""><?php _e('All Time', 'ricelipka-theme'); ?></option>
                                    <?php
                                    $date_ranges = ricelipka_get_date_ranges();
                                    foreach ($date_ranges as $key => $range) {
                                        echo '<option value="' . esc_attr($key) . '">' . esc_html($range['label']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="custom-date-range">
                                <div class="date-range-selector">
                                    <label for="start-date"><?php _e('From', 'ricelipka-theme'); ?></label>
                                    <input type="date" id="start-date" name="start_date">
                                </div>
                                <div class="date-range-selector">
                                    <label for="end-date"><?php _e('To', 'ricelipka-theme'); ?></label>
                                    <input type="date" id="end-date" name="end_date">
                                </div>
                            </div>
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
            </header>
            
            <div class="posts-container news-container posts-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'news-card'); ?>
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            echo '<div class="pagination-wrapper">';
            the_posts_pagination(array(
                'prev_text' => __('← Previous News', 'ricelipka-theme'),
                'next_text' => __('Next News →', 'ricelipka-theme'),
                'class' => 'pagination'
            ));
            echo '</div>';
            ?>
            
        <?php else : ?>
            
            <section class="no-results news-no-results">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('No News Found', 'ricelipka-theme'); ?></h1>
                </header>
                
                <div class="page-content">
                    <p><?php _e('There are currently no news articles available. Please check back later.', 'ricelipka-theme'); ?></p>
                </div>
            </section>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();