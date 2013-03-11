<?php
/**
 * load all flexishop core files
 */
global $prima_theme_data, $wp_rewrite;
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

/**
 * Custom JS
 */
function wdm_scripts(){
  wp_enqueue_script(
    'spinjs',
    '/wp-content/themes/yourflexishop/js/spin.min.js',
    array('jquery')
  );

  wp_enqueue_script(
    'wdmglobal',
    '/wp-content/themes/yourflexishop/js/global.js',
    array('jquery')
  );

  wp_enqueue_script(
    'bettertagging',
    '/wp-content/themes/yourflexishop/js/bettertagging.js',
    array('jquery')
  );
}

add_action( 'wp_head', 'insert_image_src_rel_in_head', 5 );
add_action('wp_enqueue_scripts', 'wdm_scripts');


/**
 * Rewrite WDM users permalink
 */
$wp_rewrite->author_base = 'maniac';
$wp_rewrite->flush_rules();

function wdm_ajax_select(){
  $form_select = '';
  $wdmcat = $_POST['wdmcat'];
  $variable_select = wdm_get_variable_select($wdmcat);
  foreach ($variable_select as $term){
    $form_select .= wdm_form_select($term->taxonomy,$term->id,$term->name,$term->taxonomy,$term->term_id,FALSE,TRUE);
  }
  die($form_select);
}



function wdm_custom_type_to_query() {
  if (! is_admin() ){
          global $wp_query;
          if ( is_author() || is_archive()) {
            $wp_query->set( 'post_type',  array('wpsc-product') );
          }
        }
}

//creating Ajax call for WordPress
add_action('wp_ajax_nopriv_wdm_ajax_select', 'wdm_ajax_select');
add_action('wp_ajax_wdm_ajax_select', 'wdm_ajax_select');
add_action( 'pre_get_posts', 'wdm_custom_type_to_query' );








//Aggiunta campo wind/kite in userprofile


add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>

	<h3>Kind of maniac</h3>

	<table class="form-table">

		<tr>
			<th><label for="twitter">tipo di maniaco</label></th>

			<td>
                          
                          
                          <select id="display_addiction">
                            <option value="<?php echo esc_attr( get_the_author_meta( 'windsurfer', $user->ID ) ); ?>">Windsurfer</option>
                            <option value="<?php echo esc_attr( get_the_author_meta( 'kitesurfer', $user->ID ) ); ?>">Kitesurfer</option>
                          </select>
			  <span class="description">Please select your addiction.</span>
			</td>
		</tr>

	</table>
<?php }


//Salvataggio valore wind/kite in userprofile

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'windsurfer', $_POST['windsurfer'] );
        update_usermeta( $user_id, 'kitesurfer', $_POST['kitesurfer'] );
}
