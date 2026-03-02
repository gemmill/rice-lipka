<?php
/**
 * The template for displaying the footer
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */
?>

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="footer-widgets">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>
            
            <div class="site-info">
                <div class="footer-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ));
                    ?>
                </div>
                
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'ricelipka-theme'); ?></p>
                    <p class="powered-by">
                        <?php printf(__('Powered by %s', 'ricelipka-theme'), '<a href="https://wordpress.org/">WordPress</a>'); ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>