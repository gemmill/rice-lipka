<?php
/**
 * The template for displaying the footer
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */
?>

            </main>
        </div><!-- .site-content-wrapper -->
    </div><!-- .site-layout -->

    <footer id="colophon" class="site-footer">
        <div class="site-layout">
            <div class="site-sidebar">
                <!-- Empty sidebar space for alignment -->
            </div>
            <div class="site-content-wrapper">
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
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>