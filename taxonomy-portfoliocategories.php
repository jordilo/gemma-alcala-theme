<?php get_header();?>
oh la ala
<?php the_terms( $post->ID, 'portfoliocategories', 'Topics: ', ', ', ' ' ); ?>
<div class="row">
	<div class="col-md-12">

        <div class="row portfolio-archive ">
            <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post();?>

                <div class="col-md-3">
                        <?php include 'portfolio-summary-tmpl.php';?>
                    </div>
                <?php endwhile;?>
                <div class="nav-previous alignleft">A<?php next_posts_link('Older posts');?></div>
                <div class="nav-next alignright">A<?php previous_posts_link('Newer posts');?></div>
            <?php if (!is_singular()): ?>
            <?php endif;?>

            <?php else: ?>

            <div class="alert alert-info">
            <strong>No content in this loop dfg </strong>
            </div>

            <?php endif;?>
        </div>

    </div>

</div>


<?php get_footer();?>