<?php
/**
 * Archive template for Events category with chronological ordering
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main events-archive archive-page">
    <div class="container">
        <header class="archive-header">
            <h1 class="archive-title">
                <?php
                $archive_year = get_query_var('archive_year');
                $archive_month = get_query_var('archive_month');
                
                if ($archive_year && $archive_month) {
                    echo sprintf(__('Events Archive: %s %s', 'ricelipka-theme'), 
                        date('F', mktime(0, 0, 0, $archive_month, 1)), 
                        $archive_year
                    );
                } elseif ($archive_year) {
                    echo sprintf(__('Events Archive: %s', 'ricelipka-theme'), $archive_year);
                } else {
                    _e('Events Archive', 'ricelipka-theme');
                }
                ?>
            </h1>
            
            <div class="archive-description">
                <p><?php _e('Browse all events chronologically. Past events are shown in reverse chronological order for easy reference.', 'ricelipka-theme'); ?></p>
            </div>
            
            <div class="archive-navigation">
                <a href="<?php echo esc_url(get_category_link(get_cat_ID('Events'))); ?>" class="back-to-current">
                    ← <?php _e('Back to Current Events', 'ricelipka-theme'); ?>
                </a>
            </div>
        </header>
        
        <!-- Archive Controls -->
        <div class="archive-controls">
            <div class="archive-selectors">
                <div class="archive-selector">
                    <label for="events-year-selector"><?php _e('Year', 'ricelipka-theme'); ?></label>
                    <select id="events-year-selector" class="archive-year-selector" data-category="events">
                        <option value=""><?php _e('All Years', 'ricelipka-theme'); ?></option>
                        <?php
                        $years = ricelipka_get_archive_years('events');
                        foreach ($years as $year) {
                            $selected = ($archive_year == $year) ? 'selected' : '';
                            echo '<option value="' . esc_attr($year) . '" ' . $selected . '>' . esc_html($year) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div class="archive-selector">
                    <label for="events-month-selector"><?php _e('Month', 'ricelipka-theme'); ?></label>
                    <select id="events-month-selector" class="archive-month-selector" data-category="events">
                        <option value=""><?php _e('All Months', 'ricelipka-theme'); ?></option>
                        <?php
                        if ($archive_year) {
                            $months = ricelipka_get_archive_months('events', $archive_year);
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
        
        <!-- Event filter tabs -->
        <div class="event-filters category-nav">
            <ul>
                <li><a href="#" class="filter-btn active" data-filter="all"><?php _e('All Events', 'ricelipka-theme'); ?></a></li>
                <li><a href="#" class="filter-btn" data-filter="upcoming"><?php _e('Upcoming', 'ricelipka-theme'); ?></a></li>
                <li><a href="#" class="filter-btn" data-filter="past"><?php _e('Past Events', 'ricelipka-theme'); ?></a></li>
            </ul>
        </div>
        
        <!-- Event filters removed - using only top-level categories -->
        
        <?php if (have_posts()) : ?>
            <div class="results-count">
                <?php
                global $wp_query;
                $count = $wp_query->found_posts;
                printf(
                    _n('%d event found', '%d events found', $count, 'ricelipka-theme'),
                    $count
                );
                ?>
            </div>
            
            <div class="posts-container events-container archive-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'event-card'); ?>
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'prev_text' => __('← Previous Events', 'ricelipka-theme'),
                'next_text' => __('Next Events →', 'ricelipka-theme'),
                'class' => 'pagination archive-pagination'
            ));
            ?>
            
        <?php else : ?>
            
            <section class="no-results archive-no-results">
                <header class="page-header">
                    <h2 class="page-title"><?php _e('No Events Found', 'ricelipka-theme'); ?></h2>
                </header>
                
                <div class="page-content">
                    <p><?php _e('No events were found for the selected time period. Try adjusting your filters or browse all events.', 'ricelipka-theme'); ?></p>
                    <a href="<?php echo esc_url(get_category_link(get_cat_ID('Events'))); ?>" class="button">
                        <?php _e('View All Events', 'ricelipka-theme'); ?>
                    </a>
                </div>
            </section>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();