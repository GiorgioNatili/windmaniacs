<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php the_title(); ?></title>
<?php wp_head(); ?>
<?php
    $url = get_settings('siteurl');
    $cssUrl = $url . '/wp-content/themes/yourflexishop/css/extend.css';
    //$jsUrl = $url . '/wp-content/themes/yourflexishop/js/global.js';
    echo '<!-- custom extend css -->
          <link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />
          <!-- /end custom extend css -->';
    /*echo '<!-- custom global js -->
          <script type="text/javascript" src="' . $jsUrl . '"></script>
          <!-- /end custom global js -->'; */
?>
<!--[if gte IE 9]>
  <style type="text/css">
    #menu-main-menu {
       filter: none;
    }
  </style>
<![endif]-->

<script language="javascript" type="text/javascript">
        function limitText(limitField, limitCount, limitNum) {
            if (limitField.value.length > limitNum) {
                limitField.value = limitField.value.substring(0, limitNum);
            } else {
                limitCount.value = limitNum - limitField.value.length;
            }
        }
</script>
        
        
</head>
<body id="<?php prima_option('themelayout') ?>" <?php body_class(); ?>>
  <img alt="full screen background image" src='<?=background_image();?>' id="full-screen-background-image" class="better-background" />
<?php if(prima_get_option('usernav')&&class_exists('WP_eCommerce')) get_template_part( 'flexi-usernav' ); ?>
<div id="flexi-wrapper">
  <div id="shadow">
