<?php
/**
 * Contact Page Template
 *
 * Auto-loads for the page with slug "contact". Renders the page content
 * and any child pages as masonry items, matching the news/awards layout.
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="layout">
    <?php get_template_part('template-parts/site-menu'); ?>

    <div class="grid">
        <?php while (have_posts()) : the_post(); ?>
            <div id="contact-masonry" class="masonry">

                <?php if (get_the_content()) : ?>
                    <div class="masonry-item contact-intro">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <?php
                $child_pages = get_pages(array(
                    'parent'      => get_the_ID(),
                    'post_status' => 'publish',
                    'sort_column' => 'menu_order',
                    'sort_order'  => 'ASC',
                ));

                if (!empty($child_pages)) :
                    foreach ($child_pages as $child) :
                        ?>
                        <div class="masonry-item contact-card">
                            <?php if (has_post_thumbnail($child->ID)) : ?>
                                <div class="contact-image-wrapper">
                                    <?php echo get_the_post_thumbnail($child->ID, 'medium', array(
                                        'alt'   => esc_attr($child->post_title),
                                        'class' => 'contact-image',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <div class="contact-content">
                                <h2 class="contact-title"><?php echo esc_html($child->post_title); ?></h2>
                                <?php if (!empty($child->post_content)) : ?>
                                    <div class="contact-body">
                                        <?php echo apply_filters('the_content', $child->post_content); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>

            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
