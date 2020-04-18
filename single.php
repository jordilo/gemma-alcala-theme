<?php get_header(); ?>
<?php
$meta = get_post_meta($post->ID);
$exploded = explode(",", $meta['portfolio_gallery'][0]);
?>
<div class="row">
	<div class="col-md-9">
		<?php if(have_posts()) : ?>
		   <?php while(have_posts()) : the_post(); ?>
		   <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		   <?php if(get_option( 'page_on_front' ) != $post->ID){?>
				<?php the_title('<h2 class="post-title-header">','</h2>'); ?>
				<?php } ?>
                 <?php the_content(); ?>
			</div>
			<div class="entry-content-portfolio-single clearfix">
				<?php foreach (array_filter($exploded) as $mediaId) {?>
				<?php $mediaInfo = get_post($mediaId);?>
				<a class="example-image-link"
					href="<?=wp_get_attachment_url($mediaId)?>"
					data-lightbox="portfolio-set"
					data-title="<?=$mediaInfo->post_title?> </br> <span style='font-weight: 200'><?= $mediaInfo->post_content?></span>">
					<img class="example-image" src="<?=wp_get_attachment_thumb_url($mediaId)?>" alt="<?=$mediaInfo->post_title?> " />
					<div class="example-image-title"><div><?=$mediaInfo->post_title?> </br> <?=$mediaInfo->post_content?></div></div>
				</a>
				<?php }?>
			</div> 
			<?php
			if (is_singular()) {
				// support for pages split by nextpage quicktag
				wp_link_pages();


                // Previous/next post navigation.
                the_post_navigation( array(
                    'next_text' => '<span class="meta-nav" aria-hidden="true"></span> ' .
					'<span class="post-title">%title</span>'.
					'<span class="screen-reader-text glyphicon glyphicon-chevron-right"></span> ' ,
                    'prev_text' => '<span class="meta-nav" aria-hidden="true"></span> ' .
					'<span class="screen-reader-text glyphicon glyphicon-chevron-left"></span> ' .
					'<span class="post-title">%title</span>',
                ) );

				// tags anyone?
				the_tags();
			}
			?>
		   <?php endwhile; ?>
		<?php if (!is_singular()) : ?>
			<div class="nav-previous alignleft"><?php next_posts_link( 'Older posts' ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( 'Newer posts' ); ?></div>
		<?php endif; ?>

		<?php else : ?>

		<div class="alert alert-info">
		  <strong>No content in this loop</strong>
		</div>

		<?php endif; ?>
	</div>

	<div class="col-md-3">

		<?php if(!$isMainPage){?>
            <div class="main-sidebar">

             <?php if (is_active_sidebar('blog-sidebar')): ?>
                    <div id="widget-area" class="widget-area" role="complementary">
                        <?php dynamic_sidebar('blog-sidebar');?>
                    </div><!-- .widget-area -->
                <?php endif;?>
            </div>
        <?php } ?>
	</div>

</div>




<?php get_footer(); ?>