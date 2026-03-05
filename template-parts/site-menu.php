<?php
/**
 * Site Menu Component
 * 
 * Reusable component for displaying site title and navigation menu
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */
?>

<div class="column menu">
    
    <h1 class="site-title">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    </h1>

    <?php ricelipka_display_custom_menu(); ?>
</div>