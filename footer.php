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
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>