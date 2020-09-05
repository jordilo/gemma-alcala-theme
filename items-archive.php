<?php get_header();?>
<?php
$isSidebar = is_active_sidebar('main-portfolio-sidebar');
$totalColumns = get_theme_mod('display_options_columns', '3');
$columnsClass = 'col-md-' . 12 / $totalColumns;
$displayOptions = get_theme_mod('display_options_text');
?>
<div class="row">
	<div class="<?=$isSidebar ? 'col-md-9' : 'col-md-12'?>">

        <div class="row portfolio-archive ">
            <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post();?>
		                <div class="<?=$columnsClass?>">
		                        <?php include 'portfolio-summary-tmpl.php';?>
		                    </div>
		                <?php endwhile;?>
            <?php if (isset($displayOptions) && $displayOptions != '') {?>
                <div class="<?=$columnsClass?> ">
                    <div class="portfolio_quote_text entry-content-portfolio">
                        <h1><?= $displayOptions?></h1>
                    </div>
                </div>
            <?php }?>
            <?php if (!is_singular()): ?>
            <div class="col-md-12">
                <div class="nav-previous alignleft"><?php next_posts_link('Older posts');?></div>
                <div class="nav-next alignright"><?php previous_posts_link('Newer posts');?></div>
            </div>
            <?php endif;?>

            <?php else: ?>

            <div class="alert alert-info">
            <strong>No content in this loop</strong>
            </div>

            <?php endif;?>
        </div>

    </div>
    <?php if ($isSidebar): ?>
        <div class="col-md-3">
            <div id="widget-area" class="widget-area " role="complementary">
                <?php dynamic_sidebar('main-portfolio-sidebar');?>
            </div><!-- .widget-area -->
        </div>
        <?php endif;?>
    </div>
</div>


<?php get_footer();?>