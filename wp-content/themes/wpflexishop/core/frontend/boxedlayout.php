<?php 
$options = get_option(PRIMA_OPTIONS);
$output = '';
/* background colours */
if( $options['bodysetting'] == "color") :
	$output .= 'body#boxed {';
		$output .= 'background-image:none !important;';
		if ( $options['backgroundcol'] && $options['backgroundcol'] != '#' ) $output .= 'background-color:'.$options['backgroundcol'].' !important; ';
		if ( $options['paracolor'] ) $output .= 'color:'.$options['paracolor'].' !important; ';
	$output .= '}';
elseif( $options['bodysetting'] == "image") :
	$output .= 'body#boxed {';
		if ( $options['backgroundcol'] && $options['backgroundcol'] != '#' ) $output .= 'background-color:'.$options['backgroundcol'].' !important; ';
		if ( $options['paracolor'] && $options['paracolor'] != '#' ) $output .= 'color:'.$options['paracolor'].' !important; ';
		if( $options['bg_image'] ) $output .= 'background-image: url('.$options['bg_image'].') !important;';
		if( $options['bg_repeat'] ) $output .= 'background-repeat: '.$options['bg_repeat'].' !important;';
		if( $options['bg_position'] ) $output .= 'background-position: '.$options['bg_position'].' !important;';
		if( $options['bg_attachment'] ) $output .= 'background-attachment: '.$options['bg_attachment'].' !important;';
	$output .= '}';
endif;
if( $options['boxedcolor'] && $options['boxedcolor'] != '#' ) {
	$output .= '#flexi-wrapper, #boxed .margin { background-color:'.$options['boxedcolor'].' !important; }';
	$output .= '#boxed #top-header .margin { background:none !important; }';
}
if( $options['copyrightcol'] && $options['copyrightcol'] != '#' ) {
	$output .= '#boxed #copyright .margin { background-color:'.$options['copyrightcol'].' !important; }';
}
/* font colours */
if( $options['headingcol'] && $options['headingcol'] != '#' ) {
	$output .= '#boxed h1, #boxed h2, #boxed h3, #boxed h4, #boxed h1 a, #boxed h2 a, #boxed h3 a, #boxed table.logdisplay strong, #boxed table.logdisplay tr.toprow td, #boxed table.logdisplay tr.toprow2 td, table.customer_details td:first-child, #checkout_page_container table.checkout_cart tr.header th { 
		color:'.$options['headingcol'].' !important; 
	}';
}
if( $options['paracolor'] && $options['paracolor'] != '#' ) {
	$output .= '#boxed p{ color:'.$options['paracolor'].' !important; }';
	$output .= '#boxed div.blog-overview div.post-meta {
		border-top:1px dotted '.$options['paracolor'].' !important;
		border-bottom:1px dotted '.$options['paracolor'].' !important;
	}';
	$output .= '#boxed div.wpsc_page_numbers {
		border-top:1px dotted '.$options['paracolor'].' !important;
	}';
}
if( $options['linkcol'] && $options['linkcol'] != '#' ) {
	$output .= '#boxed a { color:'.$options['linkcol'].' !important; }';
}
if( $options['headerlinkcol'] && $options['headerlinkcol'] != '#' ) {
	$output .= '#boxed #header a, #boxed #user-nav a { color:'.$options['headerlinkcol'].' !important; }';
}
if( $options['footerheaderbgcol'] && $options['footerheaderbgcol'] != '#' ) {
	$output .= '#boxed #footer-top h3.widget-title { background:'.$options['footerheaderbgcol'].' !important; }';
}
if( $options['footerheadercol'] && $options['footerheadercol'] != '#' ) {
	$output .= '#boxed #footer-top h3.widget-title, #boxed #footer-top h3.widget-title a { color:'.$options['footerheadercol'].' !important; }';
}
if( $options['footerfontcol'] && $options['footerfontcol'] != '#' ) {
	$output .= '#boxed #footer-top, #boxed #footer-top p { color:'.$options['footerfontcol'].' !important; }';
}
if( $options['footerlinkcol'] && $options['footerlinkcol'] != '#' ) {
	$output .= '#boxed #footer-top a, #boxed #footer-top a:hover { color:'.$options['footerlinkcol'].' !important; }';
}
if( $options['footerbottomheadercol'] && $options['footerbottomheadercol'] != '#' ) {
	$output .= '#boxed #footer-bottom h3, #boxed #footer-bottom h3 a { color:'.$options['footerbottomheadercol'].' !important; }';
}
if( $options['footerbottomfontcol'] && $options['footerbottomfontcol'] != '#' ) {
	$output .= '#boxed #footer-bottom, #boxed #footer-bottom p { color:'.$options['footerbottomfontcol'].' !important; }';
}
if( $options['footerbottomlinkcol'] && $options['footerbottomlinkcol'] != '#' ) {
	$output .= '#boxed #footer-bottom a, #boxed #footer-bottom a:hover { color:'.$options['footerbottomlinkcol'].' !important; }';
}
if( $options['copyrightfontcol'] && $options['copyrightfontcol'] != '#' ) {
	$output .= '#boxed #copyright p{ color:'.$options['copyrightfontcol'].' !important; }';
}
$output = prima_minify_css($output);
if( is_ssl() ) $output = str_replace('http://', 'https://', $output);
echo $output;