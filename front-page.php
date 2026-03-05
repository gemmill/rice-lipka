<?php
/**
 * Front Page Template - Home Page
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

        <!-- Column 1: Tagline -->
        <div class="home-tagline">
            <?php
            // Get site tagline/description
            $tagline = get_bloginfo('description');
            if ($tagline) {
                echo '<p class="site-tagline">' . esc_html($tagline) . '</p>';
            } else {
                // Fallback tagline if none is set
                echo '<p class="site-tagline">Architecture that responds to place, program, and purpose.</p>';
            }
            ?>
        </div>

        <!-- Column 2: Recent Projects -->
        <div class="home-projects">
            <h2 class="section-title">Recent Work</h2>
            
            <?php
            // Debug: Check if we're on the right template
            if (defined('WP_DEBUG') && WP_DEBUG) {
                echo "<!-- DEBUG: Using front-page.php template -->";
            }
            
            // Query for 5 most recent projects
            $projects_query = new WP_Query(array(
                'post_type' => 'projects',
                'posts_per_page' => 5,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            // Debug output
            if (defined('WP_DEBUG') && WP_DEBUG) {
                echo "<!-- DEBUG: Projects query found " . $projects_query->found_posts . " posts -->";
            }
            
            if ($projects_query->have_posts()) : ?>
                <div class="home-projects-list">
                    <?php while ($projects_query->have_posts()) : $projects_query->the_post(); ?>
                        <?php get_template_part('template-parts/item-project', null, array(
                            'class' => 'project-item',
                            'layout' => 'default',
                            'image_size' => 'medium'
                        )); ?>
                    <?php endwhile; ?>
                </div>
                
                <div class="view-all-projects">
                    <a href="<?php echo home_url('/work/'); ?>" class="view-all-link">View All Projects</a>
                </div>
                
            <?php else : ?>
                <p class="no-projects">No projects available.</p>
                <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
                    <!-- DEBUG: No projects found. Check if projects post type exists and has published posts. -->
                <?php endif; ?>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        </div>

        <!-- Column 3: Recent News -->
        <div class="home-news">
            <h2 class="section-title">Recent News</h2>
            
            <?php
            // Query for 5 most recent news posts (regular posts)
            $news_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 5,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            if ($news_query->have_posts()) : ?>
                <div class="home-news-list">
                    <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                        <article class="home-news-item">
                            <div class="news-content">
                                <h3 class="news-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="news-meta">
                                    <time class="published" datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date('M j, Y'); ?>
                                    </time>
                                </div>
                                
                                <div class="news-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <div class="view-all-news">
                    <a href="<?php echo home_url('/news/'); ?>" class="view-all-link">View All News</a>
                </div>
                
            <?php else : ?>
                <p class="no-news">No news available.</p>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        </div>

<?php
get_footer();