<?php get_header();?>
<?php
$meta = get_post_meta($post->ID);
$exploded = explode(",", $meta['portfolio_gallery'][0]);
$isSidebar = is_active_sidebar('portfolio-sidebar');
?>
<div class="row">
    <?php
$thumbnailUrl = get_the_post_thumbnail_url($post, 'full');
if ($thumbnailUrl) {
    ?>
    <div class="featured-image__header col-md-12" style="background-image:url(<?=$thumbnailUrl?>)">
        <?php }?>
    </div>
	<div class="<?=$isSidebar ? 'col-md-9' : 'col-md-12'?>">
        <script>
            lightbox.option({
            'resizeDuration': 600,
            'wrapAround': true
            })
        </script>
		<?php if (have_posts()): ?>
		   <?php while (have_posts()): the_post();?>
						   <div id="post-<?php the_ID();?>" <?php post_class();?>>
						   <?php if (get_option('page_on_front') != $post->ID) {?>
								<?php the_title('<h2 class="post-title-header">', '</h2>');?>
								<?php }?>
				                 <?php the_content();?>
							</div>
							<?php include 'gallery.php'?>
							<?php
    if (is_singular()) {
        // support for pages split by nextpage quicktag
        wp_link_pages();

        // Previous/next post navigation.
        the_post_navigation(array(
            'next_text' => '<span class="meta-nav" aria-hidden="true"></span> ' .
            '<span class="post-title">%title</span>' .
            '<span class="screen-reader-text glyphicon glyphicon-chevron-right"></span> ',
            'prev_text' => '<span class="meta-nav" aria-hidden="true"></span> ' .
            '<span class="screen-reader-text glyphicon glyphicon-chevron-left"></span> ' .
            '<span class="post-title">%title</span>',
        ));

        // tags anyone?
        the_tags();
    }
    ?>
						   <?php endwhile;?>

		<?php if (!is_singular()): ?>
			<div class="nav-previous alignleft"><?php next_posts_link('Older posts');?></div>
			<div class="nav-next alignright"><?php previous_posts_link('Newer posts');?></div>
		<?php endif;?>

		<?php else: ?>

		<div class="alert alert-info">
		  <strong>No content in this loop</strong>
		</div>

		<?php endif;?>
	</div>

<?php if ($isSidebar) {?>
	<div class="col-md-3">

            <div class="main-sidebar">

             <?php if (is_active_sidebar('portfolio-sidebar')): ?>
                    <div id="widget-area" class="widget-area" role="complementary">
                        <?php dynamic_sidebar('portfolio-sidebar');?>
                    </div><!-- .widget-area -->
                <?php endif;?>
            </div>
	</div>
<?php }?>

</div>




<?php get_footer();?>