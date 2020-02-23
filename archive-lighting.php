<?php get_header();?>

<div class="row">
	<div class="col-md-12">

        <div class="row portfolio-archive ">
            <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post();?>

	                <div class="col-md-3">
	                        <?php include 'portfolio-summary-tmpl.php';?>
	                    </div>
	                <?php endwhile;?>
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

</div>


<?php get_footer();?>