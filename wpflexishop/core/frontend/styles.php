<?php

remove_action('wp_head', 'prima_load_dynamic_style', 1101);
add_action('wp_head', 'prima_load_custom_styles', 1001);
function prima_load_custom_styles() {
	echo '<style type="text/css">/* Custom Styles */'."\n";
	do_action('prima_styles');
	echo '</style>'."\n";
}
/**
 * undocumented
 */
add_action('wp_head', 'prima_load_stylesheet', 5);
function prima_load_stylesheet() {
	$layout = prima_get_option('themelayout');
	echo '<link rel="stylesheet" href="'.trailingslashit(CHILD_URL).'style.css" type="text/css" media="screen" />'."\n";
	if( $layout && $layout != 'simple')
		echo '<link rel="stylesheet" href="'.prima_childtheme_file($layout.'layout.css').'" type="text/css" media="screen" />'."\n";
}
add_action('wp_head', 'prima_load_wpsc_stylesheet', 5);
function prima_load_wpsc_stylesheet() {
	if(!class_exists('WP_eCommerce')) return;
	wp_deregister_style( 'wp-e-commerce-dynamic' );
	wp_deregister_style( 'wpsc-theme-css' );
	wp_deregister_style( 'wpsc-theme-css-compatibility' );
	wp_enqueue_style( 'wpsc-theme-css', prima_childtheme_file('wpsc-FlexiShop.css') );
	wp_enqueue_style( 'wpsc-theme-css-compatibility', prima_childtheme_file('compatibility.css') );
}
add_action('wp_head', 'prima_load_shortcode_stylesheet', 5);
function prima_load_shortcode_stylesheet() {
	wp_enqueue_style( 'wpflexishop-shortcodes', prima_childtheme_file('shortcodes.css') );
}

add_action('get_header', 'prima_prettyphoto_styles');
function prima_prettyphoto_styles() {
	wp_enqueue_style( 'jquery.prettyPhoto', trailingslashit(PRIMA_CORE_JS_URL).'prettyphoto/prettyPhoto.css', false, '3.0.1', 'screen' );
}

add_action('prima_styles', 'prima_design_css_styles');
function prima_design_css_styles() {
	$layout = prima_get_option('themelayout');
	if( $layout )
		require_once( trailingslashit(PRIMA_FRONTEND_DIR).$layout.'layout.php' );
}

add_action('prima_styles', 'prima_custom_css_styles', 1001);
function prima_custom_css_styles() {
	$output = prima_minify_css(prima_get_option('customcss'));
	if( is_ssl() ) $output = str_replace('http://', 'https://', $output);
	echo $output;
}

/**
 * Quick and dirty way to mostly minify CSS.
 * Credits: Gary Jones
 */
function prima_minify_css($css) {
    // Normalize whitespace
    $css = preg_replace('/\s+/', ' ', $css);
    // Remove comment blocks, everything between /* and */, unless
    // preserved with /*! ... */
    $css = preg_replace('/\/\*[^\!](.*?)\*\//', '', $css);
    // Remove space after , : ; { }
    $css = preg_replace('/(,|:|;|\{|}) /', '$1', $css);
    // Remove space before , ; { }
    $css = preg_replace('/ (,|;|\{|})/', '$1', $css);
    // Strips leading 0 on decimal values (converts 0.5px into .5px)
    $css = preg_replace('/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css);
    // Strips units if value is 0 (converts 0px to 0)
    $css = preg_replace('/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css);
    // Converts all zeros value into short-hand
    $css = preg_replace('/0 0 0 0/', '0', $css);
    // Ensures image path is correct, if we're serving .css file from subfolder
    // $css = preg_replace('/url\(([\'"]?)images\//', 'url(${1}' . CHILD_URL . '/images/', $css);
    return $css;
}

// add_action('prima_styles', 'prima_compatibility_css_styles');
function prima_compatibility_css_styles() {
	$output = '';
	$output .= 'body.page-template-special #leader{ padding:0px; position: relative; }';
	$output .= 'body.page-template-special #header .margin{ border-bottom:0px; }';
	$output .= '#user-nav { z-index: 999; display: block; position: absolute; top: 0; left: 0; height: 50px; width: 100%; }';
	$output .= '#user-nav ul { float: none; z-index: auto; }';
	$output .= '#leader h1.page-special-title { padding:40px 0 30px; }';
	$output .= '#header #logo, #header #topnav { float: none; }';
	$output .= '#header h2 { padding: 0; margin: 0; font-weight: normal; line-height: 1em; }';
	$output .= '#boxed #header h2 { padding-bottom: 10px; }';
	$output .= '#header #logo a { padding: 0; margin: 0; font-weight: normal; line-height: 1em; }';
	$output .= '#slider-controls a.pager-link { margin-top: 10px; line-height: 17px; }';
	$output .= 'ul.product-list { margin-bottom: 20px; }';
	$output .= 'div.blog-overview div.post-meta { margin-bottom: 15px; position: relative; }';
	$output .= 'a.comment-count { position: absolute; top: -5px; right: 0; }';
	$output .= '#blog-panel ul li.post { clear: both; }';
	$output = prima_minify_css($output);
	echo $output;
}

add_action('prima_styles', 'prima_additionaldesign_css_styles');
function prima_additionaldesign_css_styles() {
	$output = '';
	$layout = prima_get_option('themelayout') ? prima_get_option('themelayout') : 'simple';
	$options = get_option(PRIMA_OPTIONS);
	
	if ( prima_get_option('sliderheight') ) :
		$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
		if ($layout == 'simple')
			$output .= '#features, ul.feature-list { height: '.$height.'px !important; position: relative; }';
		elseif ($layout == 'full')
			$output .= '#full #features, #full ul.feature-list { height: '.$height.'px !important; position: relative; }';
		elseif ($layout == 'boxed')
			$output .= '#boxed #features, #boxed #features .bx-wrapper, #boxed #features .bx-wrapper .bx-window, #boxed #featured-slider ul, #boxed #featured-slider ul li { height: '.$height.'px !important; position: relative; }';
	endif;

	if ( prima_get_option('sliderproductwidth') ) :
		if (prima_get_option('themelayout') == 'boxed') $width = 896;
		else $width = 980;
		$prodimgwidth = prima_get_option('sliderproductwidth') ? prima_get_option('sliderproductwidth') : 500;
		$prodtextwidth = $width-$prodimgwidth-120;
		$output .= '#'.$layout.' div.product-image { top: auto; width: '.$prodimgwidth.'px; margin: 0; padding: 0 60px 0 0 }';
		$output .= '#'.$layout.' div.product-content { width: '.$prodtextwidth.'px; margin: 0; padding: 60px 0; }';
	endif;
	
	if( $options['headermenucol'] && $options['headermenucol'] != '#' ) {
		$output .= '#header #topnav li a { color:'.$options['headermenucol'].' !important; }';
	}
	if( $options['headermenubghovercol'] && $options['headermenubghovercol'] != '#' ) {
		$output .= '#header #topnav li a:hover, #header #topnav li li a:hover { background:'.$options['headermenubghovercol'].' !important; }';
	}
	if( $options['headermenuhovercol'] && $options['headermenuhovercol'] != '#' ) {
		$output .= '#header #topnav li a:hover, #header #topnav li li a:hover { color:'.$options['headermenuhovercol'].' !important; }';
	}
	if( $options['headersubmenubgcol'] && $options['headersubmenubgcol'] != '#' ) {
		$output .= '#header #topnav li li a { background:'.$options['headersubmenubgcol'].' !important; }';
	}
	if( $options['headersubmenucol'] && $options['headersubmenucol'] != '#' ) {
		$output .= '#header #topnav li li a { color:'.$options['headersubmenucol'].' !important; }';
	}
	if( $options['headersubmenubordercol'] && $options['headersubmenubordercol'] != '#' ) {
		$output .= '#header #topnav li li a { border-color:'.$options['headersubmenubordercol'].' !important; }';
	}
	if( $options['topbarbgcol'] && $options['topbarbgcol'] != '#' ) {
		$output .= '#top-header { background:'.$options['topbarbgcol'].' !important; }';
	}
	if( $options['topbarmenubgcol'] && $options['topbarmenubgcol'] != '#' ) {
		$output .= '#cart-top, #top-header-nav h4.top-nav-header { background:'.$options['topbarmenubgcol'].' !important; }';
	}
	if( $options['topbarmenucol'] && $options['topbarmenucol'] != '#' ) {
		$output .= '#cart-top a span.cartcount, #top-header-nav h4.top-nav-header a { color:'.$options['topbarmenucol'].' !important; }';
	}	
	if( $options['topbarcontentbgcol'] && $options['topbarcontentbgcol'] != '#' ) {
		$output .= '#small-cart, #cart-top:hover, #top-header #header-categories div.header-categories-drop, #top-header-nav h4.top-nav-header:hover { background:'.$options['topbarcontentbgcol'].' !important; }';
		$output .= '#top-header #header-categories div.header-categories-drop div.categories-group h4 {background:none;}';
	}
	if( $options['topbarcontenttitlecol'] && $options['topbarcontenttitlecol'] != '#' ) {
		$output .= '#top-header #header-categories div.header-categories-drop div.categories-group h4 a, #top-header-nav #small-cart-header h4, #small-cart th, #top-header-nav #cart-top a, form.wpsc_empty_the_cart { color:'.$options['topbarcontenttitlecol'].' !important; }';
	}	
	if( $options['topbarcontentcol'] && $options['topbarcontentcol'] != '#' ) {
		$output .= '#top-header #header-categories div.header-categories-drop div.categories-group ul li, #top-header #header-categories div.header-categories-drop div.categories-group ul li a, #small-cart td, #small-cart p.empty, #small-cart span.cart_message { color:'.$options['topbarcontentcol'].' !important; }';
	}	
	if( $options['topbarbordercol'] && $options['topbarbordercol'] != '#' ) {
		$output .= '#top-header #header-categories div.header-categories-drop div.categories-group, #cart-top, #small-cart #small-cart-header .cart-message, #small-cart p.empty, #cart-top table.shoppingcart, #small-cart th, #small-cart td, #small-cart span.cart_message { border-color:'.$options['topbarbordercol'].' !important; }';
	}	
	if( $options['topbarcountcol'] && $options['topbarcountcol'] != '#' ) {
		$output .= '#small-cart span.cartcount, #top-header-nav #small-cart a.checkout-link { color:'.$options['topbarcountcol'].' !important; }';
		$output .= '#top-header-nav #small-cart a.checkout-link { background: none; padding-left: 0; text-decoration: underline; }';
	}	
	if( $options['pricedisplaybgcol'] && $options['pricedisplaybgcol'] != '#' ) {
		$output .= 'li.product-listing div.wpsc_product_price span.pricedisplay { background:'.$options['pricedisplaybgcol'].' !important; }';
	}	
	if( $options['pricedisplaycol'] && $options['pricedisplaycol'] != '#' ) {
		$output .= 'li.product-listing div.wpsc_product_price span.pricedisplay { color:'.$options['pricedisplaycol'].'; }';
	}	
	if( $options['salepricedisplaycol'] && $options['salepricedisplaycol'] != '#' ) {
		$output .= 'li.product-listing div.wpsc_product_price span.sale-price { color:'.$options['salepricedisplaycol'].'; }';
	}	
	if( $options['checkouttitlebgcol'] && $options['checkouttitlebgcol'] != '#' ) {
		$output .= '#shopping-cart h2.review-order, #shopping-cart-form h2.checking-out { background:'.$options['checkouttitlebgcol'].'; }';
	}	
	if( $options['checkouttitlecol'] && $options['checkouttitlecol'] != '#' ) {
		$output .= '#shopping-cart h2.review-order, #shopping-cart-form h2.checking-out { color:'.$options['checkouttitlecol'].' !important; }';
	}	
	if( $options['checkoutcol'] && $options['checkoutcol'] != '#' ) {
		$output .= '#checkout_page_container td, #checkout_page_container .wpsc_shipping_quote_name label, #checkout_page_container .wpsc_shipping_quote_price label, #checkout_page_container .wpsc_shipping_quote_radio label, #checkout_page_container table label { color:'.$options['checkoutcol'].'; }';
	}	
	if( $options['checkoutrowbgcol'] && $options['checkoutrowbgcol'] != '#' ) {
		$output .= '#checkout_page_container td { background:'.$options['checkoutrowbgcol'].'; }';
	}	
	if( $options['checkoutaltrowbgcol'] && $options['checkoutaltrowbgcol'] != '#' ) {
		$output .= '#checkout_page_container tr:nth-child(even) td, #checkout_page_container  tr.even td { background:'.$options['checkoutaltrowbgcol'].'; }';
	}	
	if( $options['checkoutbordercol'] && $options['checkoutbordercol'] != '#' ) {
		$output .= '#checkout_page_container td, #checkout_page_container th { border-color:'.$options['checkoutbordercol'].'; }';
	}	
	if( $options['fancybgcol'] && $options['fancybgcol'] != '#' ) {
		$output .= '#fancy_notification { background:'.$options['fancybgcol'].' !important; border-color:'.$options['fancybgcol'].' !important; }';
	}	
	if( $options['fancycol'] && $options['fancycol'] != '#' ) {
		$output .= '#fancy_notification { color:'.$options['fancycol'].' !important; }';
	}	
	if( $options['fancycheckoutcol'] && $options['fancycheckoutcol'] != '#' ) {
		$output .= '#fancy_notification #fancy_notification_content a.go_to_checkout { color:'.$options['fancycheckoutcol'].' !important; background:none; padding-left:0; }';
	}	
	if( $options['fancycontinuecol'] && $options['fancycontinuecol'] != '#' ) {
		$output .= '#fancy_notification #fancy_notification_content a.continue_shopping { color:'.$options['fancycontinuecol'].' !important; }';
	}	
	$output = prima_minify_css($output);
	if( is_ssl() ) $output = str_replace('http://', 'https://', $output);
	echo $output;
}

add_action('prima_styles', 'prima_admin_bar_logo');
function prima_admin_bar_logo() {
	$logo = prima_get_option( "adminlogo" );
	if ( !$logo ) $logo = PRIMA_CORE_CSS_URL.'/images/prima.png';
	echo '#wp-admin-bar-wp-logo > .ab-item .ab-icon, #wpadminbar.nojs #wp-admin-bar-wp-logo:hover > .ab-item .ab-icon, #wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon { background-image: url('.$logo.') !important; background-position: center center; }';	
}
