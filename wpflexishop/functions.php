<?php
/**
 * load all flexishop core files
 */
global $prima_theme_data;
$prima_theme_data = get_theme_data( STYLESHEETPATH . '/style.css' );
if ( ! defined( 'PRIMA_THEME_NAME' ) )
	define('PRIMA_THEME_NAME', $prima_theme_data['Name']);
if ( ! defined( 'PRIMA_DOMAIN' ) )
	define('PRIMA_DOMAIN', 'flexishop');
if ( ! defined( 'PRIMA_OPTIONS' ) )
	define('PRIMA_OPTIONS', 'site_basic_options');
require_once( TEMPLATEPATH . '/core/init.php' );

remove_action('wpsc_before_form_of_shopping_cart', 'wpsc_google_checkout_page');
remove_action('wpsc_bottom_of_shopping_cart', 'wpsc_google_checkout_page');
add_action('wpsc_bottom_of_shopping_cart', 'wpsc_google_checkout_page');

