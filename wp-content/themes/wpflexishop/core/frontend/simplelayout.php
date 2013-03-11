<?php 
$options = get_option(PRIMA_OPTIONS);
$output = '';
/* background colours */
if( $options['bodysetting'] == "color") :
	$output .= 'body#simple {';
		$output .= 'background-image:none !important;';
		if ( $options['backgroundcol'] && $options['backgroundcol'] != '#' ) $output .= 'background-color:'.$options['backgroundcol'].' !important; ';
		if ( $options['paracolor'] && $options['paracolor'] != '#' ) $output .= 'color:'.$options['paracolor'].' !important; ';
	$output .= '}';
elseif( $options['bodysetting'] == "image") :
	$output .= 'body#simple {';
		if ( $options['backgroundcol'] && $options['backgroundcol'] != '#' ) $output .= 'background-color:'.$options['backgroundcol'].' !important; ';
		if ( $options['paracolor'] && $options['paracolor'] != '#' ) $output .= 'color:'.$options['paracolor'].' !important; ';
		if( $options['bg_image'] ) $output .= 'background-image: url('.$options['bg_image'].') !important;';
		if( $options['bg_repeat'] ) $output .= 'background-repeat: '.$options['bg_repeat'].' !important;';
		if( $options['bg_position'] ) $output .= 'background-position: '.$options['bg_position'].' !important;';
		if( $options['bg_attachment'] ) $output .= 'background-attachment: '.$options['bg_attachment'].' !important;';
	$output .= '}';
endif;
/* font colours */
if( $options['headingcol'] && $options['headingcol'] != '#' ) {
	$output .= '#simple h1, #simple h2, #simple h3, #simple h4, #simple h1 a, #simple h2 a, #simple h3 a, #simple table.logdisplay strong, #simple table.logdisplay tr.toprow td, #simple table.logdisplay tr.toprow2 td, table.customer_details td:first-child, #checkout_page_container table.checkout_cart tr.header th{ 
		color:'.$options['headingcol'].' !important; 
	}';
}
if( $options['paracolor'] && $options['paracolor'] != '#' ) {
	$output .= '#simple p{ color:'.$options['paracolor'].' !important; }';
	$output .= '#simple div.blog-overview div.post-meta {
		border-top:1px dotted '.$options['paracolor'].' !important;
		border-bottom:1px dotted '.$options['paracolor'].' !important;
	}';
	$output .= '#simple div.wpsc_page_numbers {
		border-top:1px dotted '.$options['paracolor'].' !important;
	}';
}
if( $options['linkcol'] && $options['linkcol'] != '#' ) {
	$output .= '#simple a { color:'.$options['linkcol'].' !important; }';
}
if( $options['headerlinkcol'] && $options['headerlinkcol'] != '#' ) {
	$output .= '#simple #header a, #simple #user-nav a { color:'.$options['headerlinkcol'].' !important; }';
}
if( $options['footerheaderbgcol'] && $options['footerheaderbgcol'] != '#' ) {
	$output .= '#simple #footer-top h3.widget-title { background:'.$options['footerheaderbgcol'].' !important; }';
}
if( $options['footerheadercol'] && $options['footerheadercol'] != '#' ) {
	$output .= '#simple #footer-top h3.widget-title, #simple #footer-top h3.widget-title a { color:'.$options['footerheadercol'].' !important; }';
}
if( $options['footerfontcol'] && $options['footerfontcol'] != '#' ) {
	$output .= '#simple #footer-top, #simple #footer-top p { color:'.$options['footerfontcol'].' !important; }';
}
if( $options['footerlinkcol'] && $options['footerlinkcol'] != '#' ) {
	$output .= '#simple #footer-top a, #simple #footer-top a:hover { color:'.$options['footerlinkcol'].' !important; }';
}
if( $options['footerbottomheadercol'] && $options['footerbottomheadercol'] != '#' ) {
	$output .= '#simple #footer-bottom h3, #simple #footer-bottom h3 a { color:'.$options['footerbottomheadercol'].' !important; }';
}
if( $options['footerbottomfontcol'] && $options['footerbottomfontcol'] != '#' ) {
	$output .= '#simple #footer-bottom, #simple #footer-bottom p { color:'.$options['footerbottomfontcol'].' !important; }';
}
if( $options['footerbottomlinkcol'] && $options['footerbottomlinkcol'] != '#' ) {
	$output .= '#simple #footer-bottom a, #simple #footer-bottom a:hover { color:'.$options['footerbottomlinkcol'].' !important; }';
}
if( $options['copyrightfontcol'] && $options['copyrightfontcol'] != '#' ) {
	$output .= '#simple #copyright p{ color:'.$options['copyrightfontcol'].' !important; }';
}
$output = prima_minify_css($output);
if( is_ssl() ) $output = str_replace('http://', 'https://', $output);
echo $output;