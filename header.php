<?php
/**
 * The header for the theme
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php _e('Skip to content', 'ricelipka-theme'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else { ?>
                    <h1 class="site-title site-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) { ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php }
                } ?>
            </div>

            <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php _e('Toggle navigation menu', 'ricelipka-theme'); ?>">
                <span class="hamburger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <span class="sr-only"><?php _e('Menu', 'ricelipka-theme'); ?></span>
            </button>

            <nav id="site-navigation" class="main-navigation" aria-label="<?php _e('Primary navigation', 'ricelipka-theme'); ?>">
                <?php ricelipka_display_custom_menu(); ?>
            </nav>
        </div>
    </header>