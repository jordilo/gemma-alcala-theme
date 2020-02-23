<?php wp_footer();?>
        </div> <!-- close main container -->
        <div class="footer-widget">
            <div class="container">
                <?php if (is_active_sidebar('footer-column-1')): ?>
                    <div id="widget-area" class="widget-area col-md-3" role="complementary">
                        <?php dynamic_sidebar('footer-column-1');?>
                    </div><!-- .widget-area -->
                <?php endif;?>
                <?php if (is_active_sidebar('footer-column-2')): ?>
                    <div id="widget-area" class="widget-area col-md-6" role="complementary">
                        <?php dynamic_sidebar('footer-column-2');?>
                    </div><!-- .widget-area -->
                <?php endif;?>
                <?php if (is_active_sidebar('footer-column-3')): ?>
                    <div id="widget-area" class="widget-area col-md-3" role="complementary">
                        <?php dynamic_sidebar('footer-column-3');?>
                    </div><!-- .widget-area -->
                <?php endif;?>
            </div>
        </div>
        <?php if( get_theme_mod( 'footer_text_block') != "" ){?>
            <p class="footer-text-copyright">
                © <?php echo date("Y"); ?> <?php echo get_theme_mod( 'footer_text_block'); ?>
            </p>
        <?php } ?>
    </body>
</html>


