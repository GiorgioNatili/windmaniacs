<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php the_title(); ?></title>
<?php wp_head(); ?>

<!-- custom extend css -->
<link rel="stylesheet" type="text/css" href="<?php print get_settings('siteurl'); ?>/wp-content/themes/yourflexishop/css/extend.css" />
<!-- /end custom extend css -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
 <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

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
<script>
jQuery(function() {
jQuery( "#dialog-message" ).dialog({
modal: true,
buttons: {
Ok: function() {
jQuery( this ).dialog( "close" );
}
}
});
});
</script>


</head>
<body id="<?php prima_option('themelayout') ?>" <?php body_class(); ?>>
  <img alt="full screen background image" src='<?=background_image();?>' id="full-screen-background-image" class="better-background" />
<?php if(prima_get_option('usernav')&&class_exists('WP_eCommerce')) get_template_part( 'flexi-usernav' ); ?>
<div id="flexi-wrapper">
  <div id="shadow">
  <div id="bf">not allow</div>
<?php
  echo "allow";
   $social_notify = $_GET["social_notify"];
	if($social_notify)
	{
	if ( !is_user_logged_in() ) 
	{
	echo "test123";
	echo '<div id="dialog-message" title="Basic modal dialog"><p>Your session is expired!<a href="'. get_site_url().'/p/your-account">login</a></p></div>';
	} 
	} 
	
	