<?php
/**
 * Template part for displaying projects in archive view
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('project-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="project-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="project-content">
        <h2 class="project-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        
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
                        <?php echo esc_html(ricelipka_get_project_type_display($fields['project_type'])); ?>
                    </span>
                <?php endif; ?>
                
                <?php if (!empty($fields['client'])) : ?>
                    <span class="project-client"><?php echo esc_html($fields['client']); ?></span>
                <?php endif; ?>
                
                <?php if (!empty($fields['location'])) : ?>
                    <span class="project-location"><?php echo esc_html($fields['location']); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="project-excerpt">
            <?php the_excerpt(); ?>
        </div>
        
        <div class="project-link">
            <a href="<?php the_permalink(); ?>" class="read-more">View Project</a>
        </div>
    </div>
</article>