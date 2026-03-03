<?php
/**
 * Single Project Template
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-project'); ?>>
                <header class="project-header">
                    <h1 class="project-title"><?php the_title(); ?></h1>
                    
                    <?php
                    $fields = ricelipka_get_post_type_fields();
                    if ($fields) :
                    ?>
                        <div class="project-meta">
                            <?php if (!empty($fields['project_year'])) : ?>
                                <span class="project-year"><?php echo esc_html($fields['project_year']); ?></span>
                            <?php endif; ?>
                            
                            <?php if (!empty($fields['project_type'])) : ?>
                                <span class="project-type">
                                    <a href="<?php echo home_url('/projects/' . $fields['project_type'] . '/'); ?>">
                                        <?php echo esc_html(ricelipka_get_project_type_display($fields['project_type'])); ?>
                                    </a>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($fields['client'])) : ?>
                                <span class="project-client">
                                    <strong>Client:</strong> <?php echo esc_html($fields['client']); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($fields['location'])) : ?>
                                <span class="project-location">
                                    <strong>Location:</strong> <?php echo esc_html($fields['location']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="project-featured-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="project-content">
                    <?php the_content(); ?>
                </div>

                <?php
                // Display image gallery if it exists
                if (!empty($fields['image_gallery'])) :
                ?>
                    <div class="project-gallery">
                        <h2>Project Gallery</h2>
                        <div class="gallery-grid">
                            <?php foreach ($fields['image_gallery'] as $image) : ?>
                                <div class="gallery-item">
                                    <a href="<?php echo esc_url($image['url']); ?>" data-lightbox="project-gallery">
                                        <img src="<?php echo esc_url($image['sizes']['medium']); ?>" 
                                             alt="<?php echo esc_attr($image['alt']); ?>" />
                                    </a>
                                    <?php if (!empty($image['caption'])) : ?>
                                        <p class="gallery-caption"><?php echo esc_html($image['caption']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <nav class="project-navigation">
                    <div class="nav-links">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        ?>
                        
                        <?php if ($prev_post) : ?>
                            <div class="nav-previous">
                                <a href="<?php echo get_permalink($prev_post); ?>" rel="prev">
                                    <span class="nav-subtitle">Previous Project</span>
                                    <span class="nav-title"><?php echo get_the_title($prev_post); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="nav-back">
                            <a href="<?php echo home_url('/projects/'); ?>" class="back-to-projects">
                                All Projects
                            </a>
                        </div>
                        
                        <?php if ($next_post) : ?>
                            <div class="nav-next">
                                <a href="<?php echo get_permalink($next_post); ?>" rel="next">
                                    <span class="nav-subtitle">Next Project</span>
                                    <span class="nav-title"><?php echo get_the_title($next_post); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </nav>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>