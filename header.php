<!DOCTYPE html>
<html <?php language_attributes();?>>
  <head>
    <title><?= get_bloginfo('name')?>  <?php wp_title();?></title>
    <meta charset="<?php bloginfo('charset');?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="pingback" href="<?php bloginfo('pingback_url');?>" />

    
    <?php 
      $description = get_bloginfo('description');
      if(get_theme_mod('seo_description_settings')){
        $description = get_theme_mod('seo_description_settings');
      }
    ?>
    <meta name="description" content="<?= $description?>">
    <meta name="keywords" content="<?=get_theme_mod('seo_keywords_settings')?>">
    <meta name="author" content="<?= get_bloginfo('name')?>">
    <meta name="generator" content="wordpress">
    <?php 
      $robots = 'NoIndex,NoFollow';
      if(get_theme_mod('seo_robots_settings')){
        $robots = get_theme_mod('seo_robots_settings');
      }
    ?>
    <meta name="robots" content="<?=$robots?>">
    <meta name="language" content="<?= get_locale()?>">

    <?php wp_head();?>

  <?php if (get_theme_mod('google_track_text')) {?>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?=get_theme_mod('google_track_text')?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '<?=get_theme_mod('google_track_text')?>');
    </script>
  <?php }?>

  <?php 
    if (get_theme_mod('twitter_cards') || get_theme_mod('facebook_id')) {
      $thumbnail = get_the_post_thumbnail_url($post->ID);
      if (!$thumbnail) {
          $thumbnail = get_site_icon_url();
      }
      if (!$thumbnail) {
          $custom_logo_id = get_theme_mod('custom_logo');
          $thumbnail = wp_get_attachment_image_src($custom_logo_id, 'full');
      }
      $description = get_bloginfo('description');
      if(wp_strip_all_tags(get_the_content()) ){
        $description = wp_strip_all_tags(get_the_content()) ;
      }
    }
  ?>
  <?php if (get_theme_mod('twitter_cards')) {?>
      
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="<?=get_theme_mod('twitter_cards')?>" />
        <meta name="twitter:creator" content="<?=get_theme_mod('twitter_cards')?>" />
        <meta property="og:url" content="<?=get_permalink($post->ID)?>" />
        <meta property="og:title" content="<?=$post->post_title?>" />
        <meta property="og:description" content="<?=$description?>" />
        <meta property="og:image" content="<?=$thumbnail?>" />
      <?php }?>
  <?php if (get_theme_mod('facebook_id')) {?>
        <meta property="fb:app_id"          content="<?=get_theme_mod('facebook_id')?>" /> 
        <meta property="og:type"            content="article" /> 
        <meta property="og:url"             content="<?=get_permalink($post->ID)?>" /> 
        <meta property="og:title"           content="<?=$post->post_title?>" /> 
        <meta property="og:image"           content="<?=$thumbnail?>" /> 
        <meta property="og:description"    content="<?=$description?>" />
      <?php }?>

	</head>

  <body <?php body_class(isset($class) ? $class : '');?>>

    <nav class="navbar navbar-default container" role="navigation">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <?php
          $custom_logo_id = get_theme_mod('custom_logo');
          $image = wp_get_attachment_image_src($custom_logo_id, 'full');
        ?>
        <a class="navbar-brand" href="<?php echo home_url(); ?>">
        <?php if ($image[0]) {?>
          <img src="<?=$image[0]?>" class="wp-logo"/>
        <?php }?>
        <?php bloginfo('name');?></a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse">
       <?php wp_nav_menu(array('menu' => 'Main', 'menu_class' => 'nav navbar-nav navbar-left', 'depth' => 3, 'container' => false, 'walker' => new Bootstrap_Walker_Nav_Menu));?>


       <?php if (is_active_sidebar('secondary-menu')): ?>
          <div class="nav navbar-nav navbar-right secondary-menu">
            <?php dynamic_sidebar('secondary-menu');?>
          </div>
        <?php endif;?>
      </div>
      </div><!-- /.navbar-collapse -->
    </nav>

    <?php $isMainPage = get_option('page_on_front') == $post->ID?>
    <div class="slider-component">
      <?php if ($isMainPage) {?>
        <?php echo do_shortcode(get_theme_mod('main_text_block')); ?>
      <?php }?>
    </div>
    <div id="main-container" class="container">