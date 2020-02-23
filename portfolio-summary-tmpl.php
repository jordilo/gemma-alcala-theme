<?php 

    $image = get_the_post_thumbnail_url($post->ID);

    if(!$image){
        $meta = get_post_meta($post->ID);
        $exploded = explode("," ,$meta['portfolio_gallery'][0]);
        $image = wp_get_attachment_thumb_url($exploded[0]);
    }
?>

<div class="entry-content-portfolio" style="background-image:url(<?= $image ?>)">
    <a href="<?=get_post_permalink($post->ID)?>" >
        <?php
    the_title();
    ?>
    </a>
</div>