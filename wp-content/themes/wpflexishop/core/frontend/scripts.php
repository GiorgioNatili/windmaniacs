<?php

// we output it directly to header for better performance 
add_action('wp_head', 'prima_wpsc_user_dynamic_js');
function prima_wpsc_user_dynamic_js() {
?>
<script type='text/javascript'>
/* <![CDATA[ */
jQuery.noConflict();

var wpsc_ajax = {
	ajaxurl: "<?php echo admin_url( 'admin-ajax.php' ); ?>"
};

/* base url */
var base_url = "<?php echo home_url('/'); ?>";
var WPSC_URL = "<?php echo WPSC_URL; ?>";
var WPSC_IMAGE_URL = "<?php echo WPSC_IMAGE_URL; ?>";
var WPSC_DIR_NAME = "<?php echo WPSC_DIR_NAME; ?>";
var WPSC_CORE_IMAGES_URL = "<?php echo WPSC_CORE_IMAGES_URL; ?>";

/* LightBox Configuration start*/
var fileLoadingImage = "<?php echo WPSC_CORE_IMAGES_URL; ?>/loading.gif";
var fileBottomNavCloseImage = "<?php echo WPSC_CORE_IMAGES_URL; ?>/closelabel.gif";
var fileThickboxLoadingImage = "<?php echo WPSC_CORE_IMAGES_URL; ?>/loadingAnimation.gif";
var resizeSpeed = 9;  // controls the speed of the image resizing (1=slowest and 10=fastest)
var borderSize = 10;  //if you adjust the padding in the CSS, you will need to update this variable

/* FlexiShop config */
var flexiSearchText = "<?php _e('Search Products...',PRIMA_DOMAIN) ?>"; // support GoldCart search form

/* ]]> */
</script>
<?php
}

add_action('get_header', 'prima_wpec_scripts_footer');
function prima_wpec_scripts_footer() {
	if(!class_exists('WP_eCommerce')) return;
	global $wp_styles, $wpsc_theme_url, $wp_query;
	if ( has_filter( 'wpsc_enqueue_user_script_and_css' ) && apply_filters( 'wpsc_mobile_scripts_css_filters', false ) ) {
		/* nothing here */
	} else {
		// for better performance
		wp_dequeue_script( 'wp-e-commerce-dynamic' );

		$version_identifier = WPSC_VERSION . "." . WPSC_MINOR_VERSION;
		$siteurl = get_option( 'siteurl' );
		wp_deregister_script( 'wp-e-commerce' );
		wp_enqueue_script( 'wp-e-commerce', WPSC_CORE_JS_URL.'/wp-e-commerce.js', array( 'jquery' ), $version_identifier, true );
		wp_deregister_script( 'infieldlabel' );
		wp_enqueue_script( 'infieldlabel', WPSC_CORE_JS_URL.'/jquery.infieldlabel.min.js', array( 'jquery' ), $version_identifier, true );
		wp_deregister_script( 'wp-e-commerce-ajax-legacy' );
		wp_enqueue_script( 'wp-e-commerce-ajax-legacy', WPSC_CORE_JS_URL.'/ajax.js', false, $version_identifier, true );
		wp_deregister_script( 'livequery' );
		wp_enqueue_script( 'livequery', WPSC_URL.'/wpsc-admin/js/jquery.livequery.js', array( 'jquery' ), '1.0.3', true );
		wp_deregister_script( 'jquery-rating' );
		wp_enqueue_script( 'jquery-rating', WPSC_CORE_JS_URL.'/jquery.rating.js', array( 'jquery' ), $version_identifier, true );
		wp_deregister_script( 'wp-e-commerce-legacy' );
		wp_enqueue_script( 'wp-e-commerce-legacy', WPSC_CORE_JS_URL.'/user.js', array( 'jquery' ), $version_identifier, true );
		$lightbox = get_option('wpsc_lightbox', 'thickbox');
		if( $lightbox == 'thickbox' ) {
			wp_dequeue_script( 'wpsc-thickbox' );
		} elseif( $lightbox == 'colorbox' ) {
			wp_dequeue_script( 'colorbox-min' );
			wp_dequeue_script( 'wpsc_colorbox' );
		}
	}
}

add_action('wp_footer', 'prima_load_custom_scripts', 1001);
function prima_load_custom_scripts() {
	echo '<script type="text/javascript">/* Custom Scripts */'."\n";
	do_action('prima_scripts'); // backward compatibility
	do_action('prima_custom_scripts');
	echo '</script>'."\n";
}

add_action('get_header', 'prima_load_scripts');
function prima_load_scripts() {
	if (is_singular() && get_option('thread_comments') && comments_open())
		wp_enqueue_script('comment-reply');
	wp_enqueue_script('superfish', PRIMA_JS_URL.'/superfish.js', array('jquery'), '1.4.8', TRUE);
	wp_enqueue_script('jquery-easing', PRIMA_JS_URL.'/jquery.easing.1.3.js', array('jquery'), '1.3', TRUE);
	wp_enqueue_script('jquery-bxSlider', PRIMA_JS_URL.'/jquery.bxSlider.min.js', array('jquery'), '3.0', TRUE);
	wp_enqueue_script('primathemes', PRIMA_JS_URL.'/primathemes.js', array('jquery'), '1.2.4', TRUE);
}

add_action('get_header', 'prima_load_prettyphoto_scripts');
function prima_load_prettyphoto_scripts() {
	wp_enqueue_script('jquery-prettyPhoto', PRIMA_CORE_JS_URL.'/prettyphoto/jquery.prettyPhoto.js', array('jquery'), '3.0.1', TRUE);
}

// add_action('get_header', 'prima_load_jplayer_scripts');
function prima_load_jplayer_scripts() {
	wp_enqueue_script('jplayer', trailingslashit(PRIMA_CORE_JS_URL).'jquery.jplayer.min.js', false, '2.0.0', TRUE);
}

// add_action('prima_scripts', 'prima_dynamic_auto_prettyphoto_scripts');
function prima_dynamic_auto_prettyphoto_scripts() { 
?>
jQuery(document).ready(function($){
	jQuery('a[href$="jpg"], a[href$="JPG"], a[href$="jpeg"], a[href$="JPEG"], a[href$="png"], a[href$="gif"], a[href$="bmp"]').not('.image_thumb_clickable').prettyPhoto({social_tools:false});
});
<?php
}

add_action('prima_scripts', 'prima_dynamic_slider_scripts');
function prima_dynamic_slider_scripts() { 
	//if ( !is_page_template('frontpage.php') ) return;
	$slidertrans = prima_get_option('slidertrans') ? prima_get_option('slidertrans') : "fade";
	$sliderspeed = prima_get_option('sliderspeed') ? (int)prima_get_option('sliderspeed') : "1500";
	$sliderpause = prima_get_option('sliderpause') ? (int)prima_get_option('sliderpause') : "4000";
	$sliderauto = prima_get_option('sliderauto') ? "true" : "false";
?>
jQuery(document).ready(function($) {
    $('ul.feature-list').bxSlider({
        mode: '<?php echo $slidertrans ?>',
        auto: <?php echo $sliderauto ?>,
        easing: 'jswing',
        autoControls: true,
        autoHover: true,
        pager: true,
        speed: <?php echo $sliderspeed ?>,
        pause: <?php echo $sliderpause ?>,
        controls: true,
        pagerSelector: '#slider-controls'
    });
});
<?php
}

add_action('prima_scripts', 'prima_dynamic_producthover_scripts');
function prima_dynamic_producthover_scripts() { 
	global $wp_query;
	global $prima_productspage_id;
	$product_category = get_query_var( 'wpsc_product_category' );
	$product_tag = get_query_var ('product_tag' );
	$id = $wp_query->get_queried_object_id();
	if( get_query_var( 'post_type' ) == 'wpsc-product' || $product_category || $product_tag || ( $id && $id == $prima_productspage_id )) :
	if ( prima_get_option('productsimagehover') == 'buynow' ) :
?>
jQuery(document).ready(function($) {
    $('ul.product-list li div.product-meta').hover(function(){
        $(this).find('img').fadeTo('slow', 0.3);
        $(this).find('input.wpsc_buy_button').show();
    },
    function(){
        $(this).find('img').fadeTo('slow', 1);
        $(this).find('input.wpsc_buy_button').hide();
    });
});
<?php else : ?>
jQuery(document).ready(function($) {
	$('ul.product-list li div.product-meta').hover(function(){
		$(this).find('img').fadeTo('slow', 0.3);
	},
	function(){
		$(this).find('img').fadeTo('slow', 1);
	});
});
<?php
	endif;
	endif;
}

add_action('wp_footer', 'prima_print_shortcodes_js', 1000);
function prima_print_shortcodes_js() {
	global $prima_shortcodes_js;
	if ( is_array( $prima_shortcodes_js ) ) {
		foreach ( $prima_shortcodes_js as $js ) {
			echo $js;
		}
	}
}

add_action('prima_custom_scripts', 'prima_print_shortcodes_scripts', 1000);
function prima_print_shortcodes_scripts() {
	global $prima_shortcodes_scripts;
	echo $prima_shortcodes_scripts;
}