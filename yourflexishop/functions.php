<?php
/**
 * load all flexishop core files
 */
global $prima_theme_data;
$prima_theme_data = get_theme_data( STYLESHEETPATH . '/style.css' );
define('PRIMA_THEME_NAME', $prima_theme_data['Name']);
define('PRIMA_DOMAIN', 'flexishop');
define('PRIMA_OPTIONS', 'site_basic_options');
require_once( TEMPLATEPATH . '/core/init.php' );

remove_action('wpsc_before_form_of_shopping_cart', 'wpsc_google_checkout_page');
remove_action('wpsc_bottom_of_shopping_cart', 'wpsc_google_checkout_page');
add_action('wpsc_bottom_of_shopping_cart', 'wpsc_google_checkout_page');

/* Add a search form in the top menu - Not using anymore */
/*add_filter('wp_nav_menu_items','add_search_box', 10, 2);

function add_search_box($items, $args) {

        ob_start();
        get_search_form();
        $searchform = ob_get_contents();
        ob_end_clean();

        $items .= '<div class="custom-search-field">' . $searchform . '</div>';

    return $items;
}*/

/* add your custom code here */

//add_image_size( 'product-thumb', 185, 185, true ); //300 pixels wide (and unlimited height)
