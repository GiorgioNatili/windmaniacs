<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php the_title(); ?></title>
<?php wp_head(); ?>
<?php 
	$url = get_settings('siteurl');
    $cssUrl = $url . '/wp-content/themes/yourflexishop/css/extend.css';
    $jsUrl = $url . '/wp-content/themes/yourflexishop/js/global.js';
    echo '<!-- custom extend css -->
          <link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />
          <!-- /end custom extend css -->';
    echo '<!-- custom global js -->
          <script type="text/javascript" src="' . $jsUrl . '"></script>
          <!-- /end custom global js -->';
?>
<!--[if gte IE 9]>
  <style type="text/css">
    #menu-main-menu {
       filter: none;
    }
  </style>
<![endif]-->
</head>
<body id="<?php prima_option('themelayout') ?>" <?php body_class(); ?>>
  <img alt="full screen background image" src='<?=background_image();?>' id="full-screen-background-image" class="better-background" /> 
<?php if(prima_get_option('usernav')&&class_exists('WP_eCommerce')) get_template_part( 'flexi-usernav' ); ?>
<div id="flexi-wrapper">
  <div id="shadow">
  <?php
   $social_notify = $_GET["social_notify"];
	if($social_notify)
	{
	if ( !is_user_logged_in() ) 
	{
	echo "test";
	echo '<div id="dialog-message" title="Basic modal dialog"><p>Your session is expired!<a href="'. get_site_url().'/p/your-account">login</a></p></div>';
	} 
		
	} 
	
	