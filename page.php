<?php get_header();?>
<?php $isMainPage = get_option('page_on_front') == $post->ID?>
<div class="row">
<div class="<?php if ($isMainPage) {?>col-md-12<?php } else {?> col-md-9 <?php }?>">
		<?php if (have_posts()): ?>
		   <?php while (have_posts()): the_post();?>
						   <div id="post-<?php the_ID();?>" <?php post_class();?>>
						   <?php if (!$isMainPage) {?>
				                <?php the_title('<h2 class="post-title-header">', '</h2>');?>
				            <?php }?>
								 <?php the_content();?>
							</div>
						   <?php endwhile;?>
           <?php if (!$isMainPage) {?>
                <?php if (!is_singular()): ?>
                    <div class="nav-previous alignleft"><?php next_posts_link('Older posts');?></div>
                    <div class="nav-next alignright"><?php previous_posts_link('Newer posts');?></div>
                <?php endif;?>
            <?php } else {?>
                <div class='row portfolio-thumbnail-wrap'>
                <?php

                        $args = array(
                            'post_type' => array('portfolio' , 'lighting'),
                            'meta_key' => 'item-order-priority',
                            'orderby'   => 'meta_value_num',
                            'order' => 'DESC',
                            'posts_per_page' => 5
                            );

                    $loop = new WP_Query($args);
       
                    while ($loop->have_posts()): $loop->the_post();?>
                    <div class="col-md-4">
                       <?php include 'portfolio-summary-tmpl.php';?>
                    </div>
                    <?php endwhile;?>
                    <div class="col-md-4">
                        <?php 
                    global $q_config;

                    $language = $q_config['language'] ?$q_config['language'] : get_locale();?>
                    <div class="portfolio_quote_text">
                        <?= get_theme_mod( 'portfolio_quote_block_' . $language ) ?> </div>
                    </div>
                </div>
                <div class="text-center show-more-wrap"><a href="<?= get_post_type_archive_link('portfolio' , 'lighting')?>"><?= __('Show more' , 'textdomain')?></a></div>

                <?php }?>
            <?php else: ?>

		<div class="alert alert-info">
		  <strong>No content in this loop</strong>
		</div>

		<?php endif;?>
	</div>
    <?php if (!$isMainPage) {?>
            <div class="col-md-3 main-sidebar">

             <?php if (is_active_sidebar('page-sidebar')): ?>
                    <div id="widget-area" class="widget-area " role="complementary">
                        <?php dynamic_sidebar('page-sidebar');?>
                    </div><!-- .widget-area -->
                <?php endif;?>
            </div>
    <?php }?>

</div>




<?php get_footer();?>