<div class="entry-content-portfolio-single clearfix row">
    <?php foreach (array_filter($exploded) as $mediaId) {?>
    <?php $mediaInfo = get_post($mediaId);?>
    <a class="example-image-link col-xs-2 col-md-3"
        href="<?=wp_get_attachment_url($mediaId)?>"
        data-lightbox="portfolio-set"
        data-title="<?=$mediaInfo->post_title?> </br> <span style='font-weight: 200'><?=$mediaInfo->post_content?></span>">
        <div class="example-image-link-bg"style="background-image: url('<?=wp_get_attachment_thumb_url($mediaId)?>')"></div>
        <div class="example-image-title"><div><?=$mediaInfo->post_title?> </br> <?=$mediaInfo->post_content?></div></div>
    </a>
    <?php }?>
</div>