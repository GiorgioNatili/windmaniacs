<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php $post_id = $wp_query->get_queried_object_id();
			$title =  get_the_title($post_id); ?>
<title><?php echo $title; ?></title>
<?php wp_head(); ?>
</head>
<body id="<?php prima_option('themelayout') ?>" <?php body_class(); ?>>
<?php /*echo qtrans_generateLanguageSelectCode('text');*/ ?>
<?php if(prima_get_option('usernav')&&class_exists('WP_eCommerce')) get_template_part( 'flexi-usernav' ); ?>
<div id="flexi-wrapper">