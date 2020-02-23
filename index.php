<?php get_header();?>

<div class="row">
    <h1>hiola</h1>
    <?= have_posts() ?>
    <h1>hiola</h1>
	<div class="col-md-12">

		<?php if (have_posts()): ?>
		   <?php while (have_posts()): the_post();?>
				   <?php
    $image = get_the_post_thumbnail_url($post->ID);
    if (!$image) {
        $custom_logo_id = get_theme_mod('custom_logo');
        $images = wp_get_attachment_image_src($custom_logo_id, 'full');
        $image = get_site_icon_url();
    }
    ?>
				   <div  class="col-md-4 blog-summary" id="post-<?php the_ID();?>" <?php post_class();?>>
				   <?php if (get_option('page_on_front') != $post->ID) {?>
					<a href="<?=get_post_permalink($post->ID)?>" >
						<div class="blog-wrap" style="background-image:url(<?=$image?>)">
								<?php the_title('<h2>', '</h2>');?>
								<?php }?>
							</div>
						</a>
					</div>
					<?php
    if (is_singular()) {
        // support for pages split by nextpage quicktag
        wp_link_pages();

        if (comments_open() || get_comments_number()):
            comments_template();
        endif;

        if ($post->post_type === 'blog') {
            // Previous/next post navigation.
            the_post_navigation(array(
                'next_text' => '<span class="meta-nav" aria-hidden="true">' . __('Next', 'twentyfifteen') . '</span> ' .
                '<span class="screen-reader-text">' . __('Next post:', 'twentyfifteen') . '</span> ' .
                '<span class="post-title">%title</span>',
                'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __('Previous', 'twentyfifteen') . '</span> ' .
                '<span class="screen-reader-text">' . __('Previous post:', 'twentyfifteen') . '</span> ' .
                '<span class="post-title">%title</span>',
            ));
        }

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
		  <strong>There are not content available…</strong>
		</div>

		<?php endif;?>
	</div>

	<!-- <div class="col-md-4">

		<?php
if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar')): //  Sidebar name
    ?>
				<?php
endif;
?>
	</div> -->

</div>




<?php get_footer();?>