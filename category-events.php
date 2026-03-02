<?php
/**
 * The template for displaying Events category archives
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main events-archive category-archive">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <header class="page-header events-header category-header">
                <h1 class="page-title events-title category-title">
                    <?php single_cat_title(); ?>
                </h1>
                
                <?php
                $category = get_queried_object();
                if ($category->description) : ?>
                    <div class="category-description events-description">
                        <?php echo wp_kses_post($category->description); ?>
                    </div>
                <?php endif; ?>
                
                <div class="category-count">
                    <?php
                    $count = $category->count;
                    printf(
                        _n('%d event', '%d events', $count, 'ricelipka-theme'),
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
                                <label for="events-year-selector"><?php _e('Year', 'ricelipka-theme'); ?></label>
                                <select id="events-year-selector" class="archive-year-selector" data-category="events">
                                    <option value=""><?php _e('All Years', 'ricelipka-theme'); ?></option>
                                    <?php
                                    $years = ricelipka_get_archive_years('events');
                                    foreach ($years as $year) {
                                        $selected = (get_query_var('archive_year') == $year) ? 'selected' : '';
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
                                    $current_year = get_query_var('archive_year') ?: date('Y');
                                    $months = ricelipka_get_archive_months('events', $current_year);
                                    foreach ($months as $month) {
                                        $selected = (get_query_var('archive_month') == $month->month) ? 'selected' : '';
                                        echo '<option value="' . esc_attr($month->month) . '" ' . $selected . '>' . esc_html($month->month_name) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
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
                
                <!-- Event type filters -->
                <div class="event-type-filters subcategory-filters category-nav">
                    <ul>
                        <li><a href="#" class="filter-btn active" data-subcategory="all"><?php _e('All Types', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-subcategory="conferences"><?php _e('Conferences', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-subcategory="workshops"><?php _e('Workshops', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-subcategory="exhibitions"><?php _e('Exhibitions', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-subcategory="lectures"><?php _e('Lectures', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-subcategory="community_events"><?php _e('Community Events', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-subcategory="awards_ceremonies"><?php _e('Awards Ceremonies', 'ricelipka-theme'); ?></a></li>
                        <li><a href="#" class="filter-btn" data-subcategory="networking"><?php _e('Networking Events', 'ricelipka-theme'); ?></a></li>
                    </ul>
                </div>
            </header>
            
            <div class="posts-container events-container">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'event-card'); ?>
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'prev_text' => __('Previous Events', 'ricelipka-theme'),
                'next_text' => __('Next Events', 'ricelipka-theme'),
            ));
            ?>
            
        <?php else : ?>
            
            <section class="no-results events-no-results">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('No Events Found', 'ricelipka-theme'); ?></h1>
                </header>
                
                <div class="page-content">
                    <p><?php _e('There are currently no events scheduled. Please check back later for upcoming events.', 'ricelipka-theme'); ?></p>
                </div>
            </section>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();