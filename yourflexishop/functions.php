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

add_action( 'login_head', 'wpse_41844_favicon' );

function wpse_41844_favicon()
{
?>
<link rel='shortcut icon' href='/favicon.ico'>
<?php
}

/******* tell to facebook to pick the featured image ********/
function insert_image_src_rel_in_head() {
	global $post;
	if ( !is_singular()) //if it is not a post or a page
		return;
	if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
		$default_image="http://www.windmaniacs.com/wp-content/themes/yourflexishop/img/facebook-logo.png"; //replace this with a default image on your server or an image in your media library
		echo '<meta property="og:image" content="' . $default_image . '"/>';
	}
	else{
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
		echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
	}
	echo "
";
}
add_action( 'wp_head', 'insert_image_src_rel_in_head', 5 );

/*/category with radio button instead of checkboxes
function catlist2radio(){zr
	echo '<script type="text/javascript">';
	echo 'jQuery(".categorychecklist")';
	echo '.each(function(){this.type="radio"});</script>';
}

add_action( 'admin_footer', 'catlist2radio' );*/

add_action('admin_head','bettertagging');

function bettertagging(){
  echo '<script type="text/javascript" src="/wp-content/themes/yourflexishop/js/bettertagging.js"></script>';
}
